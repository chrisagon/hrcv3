<?php
function missions_cv_insert($data_fct, $site_mission_fct, $periode_fct, $objet_mission_fct, $detail_mission_fct, $environnement_fct) {
    global $Translation;

    // mm: can member insert record?
    $arrPerm = getTablePermissions('missions');
    if(!$arrPerm[1]) return false;

    $donneesCV = array();
    $donneesCV['id_consultant'] = $data_fct['id_consultant'];
        if($donneesCV['id_consultant'] == empty_lookup_value) { $donneesCV['id_consultant'] = '1'; }
    $donneesCV['rattache_a_filiere'] = 1;
        if($donneesCV['rattache_a_filiere'] == empty_lookup_value) { $donneesCV['rattache_a_filiere'] = '1'; }
    $donneesCV['site_mission'] = $site_mission_fct;
        if($donneesCV['site_mission'] == empty_lookup_value) { $donneesCV['site_mission'] = ''; }
    $donneesCV['periode'] = $periode_fct;
        if($donneesCV['periode'] == empty_lookup_value) { $donneesCV['periode'] = ''; }
//    $donneesCV['date_debut'] = intval($data_fct['date_debutYear']) . '-' . intval($data_fct['date_debutMonth']) . '-' . intval($data_fct['date_debutDay']);
    $donneesCV['date_debut'] = '1901-01-01';
    $donneesCV['date_debut'] = parseMySQLDate($donneesCV['date_debut'], '');
//    $donneesCV['date_fin'] = intval($data_fct['date_finYear']) . '-' . intval($data_fct['date_finMonth']) . '-' . intval($data_fct['date_finDay']);
    $donneesCV['date_fin'] = '2999-12-31';
    $donneesCV['date_fin'] = parseMySQLDate($donneesCV['date_fin'], '');
    $donneesCV['description_mission'] = br2nl($objet_mission_fct);
    $donneesCV['description_detaille'] = $detail_mission_fct;
        if($donneesCV['description_detaille'] == empty_lookup_value) { $donneesCV['description_detaille'] = ''; }
    $donneesCV['client'] = 1;
        if($donneesCV['client'] == empty_lookup_value) { $donneesCV['client'] = '1'; }
    $donneesCV['environnement'] = $environnement_fct;
        if($donneesCV['environnement'] == empty_lookup_value) { $donneesCV['environnement'] = '1'; }

    // hook: missions_before_insert
    if(function_exists('missions_before_insert')) {
        $args = array();
        if(!missions_before_insert($donneesCV, getMemberInfo(), $args)) { return false; }
    }

    $error = '';
    // set empty fields to NULL
    $donneesCV = array_map(function($v) { return ($v === '' ? NULL : $v); }, $donneesCV);
    insert('missions', backtick_keys_once($donneesCV), $error);
    if($error)
        die("{$error}<br><a href=\"#\" onclick=\"history.go(-1);\">{$Translation['< back']}</a>");

    $recID = db_insert_id(db_link());

    // hook: missions_after_insert
    if(function_exists('missions_after_insert')) {
        $res = sql("select * from `missions` where `id_mission`='" . makeSafe($recID, false) . "' limit 1", $eo);
        if($row = db_fetch_assoc($res)) {
            $donneesCV = array_map('makeSafe', $row);
        }
        $donneesCV['selectedID'] = makeSafe($recID, false);
        $args=array();
        if(!missions_after_insert($donneesCV, getMemberInfo(), $args)) { return $recID; }
    }

    // mm: save ownership data
    set_record_owner('missions', $recID, getLoggedMemberID());

    // if this record is a copy of another record, copy children if applicable
    if(!empty($data_fct['SelectedID'])) missions_copy_children($recID, $data_fct['SelectedID']);

    return $recID;
}

    ?>