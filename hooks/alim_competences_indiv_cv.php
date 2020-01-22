<?php
function competences_individuelles_cv_insert($data_fct, $competence_indiv_fct) {
    global $Translation;

    // mm: can member insert record?
    $arrPerm = getTablePermissions('competences_individuelles');
    if(!$arrPerm[1]) return false;

    $donneesCV = array();
    $donneesCV['Competences_specifiques'] = $competence_indiv_fct;
        if($donneesCV['Competences_specifiques'] == empty_lookup_value) { $donneesCV['Competences_specifiques'] = ''; }
    $donneesCV['competence_mis_en_oeuvre'] = '1';
    $donneesCV['niveau'] = '1';
    $donneesCV['consultant_id'] = $data_fct['id_consultant'];
        if($donneesCV['consultant_id'] == empty_lookup_value) { $donneesCV['consultant_id'] = ''; }
    $donneesCV['commentaires'] = 'A compléter';
    $donneesCV['tags'] = '';
    $donneesCV['Documents_capitalises'] = PrepareUploadedFile('Documents_capitalises', 102400,'txt|doc|docx|docm|odt|pdf|rtf', false, '');
    if($donneesCV['competence_mis_en_oeuvre']== '') {
        echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Competence r&#233;f. mis en oeuvre': " . $Translation['field not null'] . '<br><br>';
        echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
        exit;
    }
    if($donneesCV['niveau']== '') {
        echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Niveau de la comp&#233;tence': " . $Translation['field not null'] . '<br><br>';
        echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
        exit;
    }
    if($donneesCV['consultant_id']== '') {
        echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'detenu par': " . $Translation['field not null'] . '<br><br>';
        echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
        exit;
    }

    /* for empty upload fields, when saving a copy of an existing record, copy the original upload field */
    if($data_fct['SelectedID']) {
        $res = sql("select * from competences_individuelles where id_comp_indiv='" . makeSafe($data_fct['SelectedID']) . "'", $eo);
        if($row = db_fetch_assoc($res)) {
            if(!$donneesCV['Documents_capitalises']) $donneesCV['Documents_capitalises'] = $row['Documents_capitalises'];
        }
    }

    // hook: competences_individuelles_before_insert
    if(function_exists('competences_individuelles_before_insert')) {
        $args = array();
        if(!competences_individuelles_before_insert($donneesCV, getMemberInfo(), $args)) { return false; }
    }

    $error = '';
    // set empty fields to NULL
    $donneesCV = array_map(function($v) { return ($v === '' ? NULL : $v); }, $donneesCV);
    insert('competences_individuelles', backtick_keys_once($donneesCV), $error);
    if($error)
        die("{$error}<br><a href=\"#\" onclick=\"history.go(-1);\">{$Translation['< back']}</a>");

    $recID = db_insert_id(db_link());

    // hook: competences_individuelles_after_insert
    if(function_exists('competences_individuelles_after_insert')) {
        $res = sql("select * from `competences_individuelles` where `id_comp_indiv`='" . makeSafe($recID, false) . "' limit 1", $eo);
        if($row = db_fetch_assoc($res)) {
            $donneesCV = array_map('makeSafe', $row);
        }
        $donneesCV['selectedID'] = makeSafe($recID, false);
        $args=array();
        if(!competences_individuelles_after_insert($donneesCV, getMemberInfo(), $args)) { return $recID; }
    }

    // mm: save ownership data
    set_record_owner('competences_individuelles', $recID, getLoggedMemberID());

    // if this record is a copy of another record, copy children if applicable
    if(!empty($data_fct['SelectedID'])) competences_individuelles_copy_children($recID, $data_fct['SelectedID']);

    return $recID;
}

?>