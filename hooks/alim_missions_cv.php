<?php
function lire_cv_consultant(){

}
/**
 * Appelé pour insérer une nouvelle mission.
 *
 * @param $data_fct
 * Contient les données du consultant
 * @param $site_mission_fct
 *
 * @param $periode_fct
 *
 * @param $objet_mission_fct
 *
 * @param $detail_mission_fct
 *
 * @param $environnement_fct
 *
 * @return
 * A boolean TRUE to perform the insert operation, or FALSE to cancel it.
 */

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
    $site_mission_fct = ltrim($site_mission_fct, "\x00..\x1F");
    $donneesCV['site_mission'] = $site_mission_fct;
        if($donneesCV['site_mission'] == empty_lookup_value) { $donneesCV['site_mission'] = ''; }
    $donneesCV['periode'] = $periode_fct;
        if($donneesCV['periode'] == empty_lookup_value) { $donneesCV['periode'] = ''; }
    //    $donneesCV['date_debut'] = intval($data_fct['date_debutYear']) . '-' . intval($data_fct['date_debutMonth']) . '-' . intval($data_fct['date_debutDay']);
    $donneesCV['date_debut'] = '1901-01-01';
    $donneesCV['date_debut'] = periode_en_date($periode_fct, "debut");

    $donneesCV['date_debut'] = parseMySQLDate($donneesCV['date_debut'], '');
//    $donneesCV['date_fin'] = intval($data_fct['date_finYear']) . '-' . intval($data_fct['date_finMonth']) . '-' . intval($data_fct['date_finDay']);
    $donneesCV['date_fin'] = '2999-12-31';
    $donneesCV['date_fin'] = periode_en_date($periode_fct, "fin");
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

/**
 * Appelé pour mettre à jour une nouvelle mission.
 *
 * @param $data_fct
 * Contient les données du consultant
 * @param $site_mission_fct
 *
 * @param $periode_fct
 *
 * @param $objet_mission_fct
 *
 * @param $detail_mission_fct
 *
 * @param $environnement_fct
 *
 * @return
 * A boolean TRUE to perform the insert operation, or FALSE to cancel it.
 */

function missions_cv_update($id_mission_fct, $id_consultant_fct, $site_mission_fct, $periode_fct, $objet_mission_fct, $detail_mission_fct, $environnement_fct) {
    global $Translation;

    // mm: can member edit record?
    $arrPerm=getTablePermissions('missions');
    $ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='missions' and pkValue='".makeSafe($id_mission_fct)."'");
    $ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='missions' and pkValue='".makeSafe($id_mission_fct)."'");
    if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3) { // allow update?
        // update allowed, so continue ...
    }else{
        return false;
    }

    $donneesCV['id_consultant'] = makeSafe($id_consultant_fct);
        if($donneesCV['id_consultant'] == empty_lookup_value) { $donneesCV['id_consultant'] = ''; }
    $donneesCV['site_mission'] = makeSafe($site_mission_fct);
        if($donneesCV['site_mission'] == empty_lookup_value) { $donneesCV['site_mission'] = ''; }
    $donneesCV['periode'] = makeSafe($periode_fct);
        if($donneesCV['periode'] == empty_lookup_value) { $donneesCV['periode'] = ''; }
    $donneesCV['date_debut'] = '1901-01-01';
    $donneesCV['date_debut'] = periode_en_date($periode_fct, "debut");
    $donneesCV['date_debut'] = parseMySQLDate($donneesCV['date_debut'], '');
    $donneesCV['date_fin'] = '2999-12-31';
    $donneesCV['date_fin'] = periode_en_date($periode_fct, "fin");
    $donneesCV['date_fin'] = parseMySQLDate($donneesCV['date_fin'], '');
    $donneesCV['description_mission'] = br2nl(makeSafe($objet_mission_fct));
    if($donneesCV['description_mission']=='') {
        echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Objet de la mission': {$Translation['field not null']}<br><br>";
        echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
        exit;
    }
    $donneesCV['description_detaille'] = makeSafe($detail_mission_fct);
        if($donneesCV['description_detaille'] == empty_lookup_value) { $donneesCV['description_detaille'] = ''; }
    $donneesCV['environnement'] = makeSafe($environnement_fct);
        if($donneesCV['environnement'] == empty_lookup_value) { $donneesCV['environnement'] = ''; }
    $donneesCV['selectedID'] = makeSafe($id_mission_fct);

    // hook: missions_before_update
    if(function_exists('missions_before_update')) {
        $args = array();
        if(!missions_before_update($data, getMemberInfo(), $args)) { return false; }
    }

    $o = array('silentErrors' => true);
    sql('update `missions` set       `id_consultant`=' . (($donneesCV['id_consultant'] !== '' && $donneesCV['id_consultant'] !== NULL) ? "'{$donneesCV['id_consultant']}'" : 'NULL') . ', `site_mission`=' . (($donneesCV['site_mission'] !== '' && $donneesCV['site_mission'] !== NULL) ? "'{$donneesCV['site_mission']}'" : 'NULL') . ', `periode`=' . (($donneesCV['periode'] !== '' && $donneesCV['periode'] !== NULL) ? "'{$donneesCV['periode']}'" : 'NULL') . ', `date_debut`=' . (($donneesCV['date_debut'] !== '' && $donneesCV['date_debut'] !== NULL) ? "'{$donneesCV['date_debut']}'" : 'NULL') . ', `date_fin`=' . (($donneesCV['date_fin'] !== '' && $donneesCV['date_fin'] !== NULL) ? "'{$donneesCV['date_fin']}'" : 'NULL') . ', `description_mission`=' . (($donneesCV['description_mission'] !== '' && $donneesCV['description_mission'] !== NULL) ? "'{$donneesCV['description_mission']}'" : 'NULL') . ', `description_detaille`=' . (($donneesCV['description_detaille'] !== '' && $donneesCV['description_detaille'] !== NULL) ? "'{$donneesCV['description_detaille']}'" : 'NULL') . ', `client`=' . (($donneesCV['client'] !== '' && $donneesCV['client'] !== NULL) ? "'{$donneesCV['client']}'" : 'NULL') . ', `environnement`=' . (($donneesCV['environnement'] !== '' && $donneesCV['environnement'] !== NULL) ? "'{$donneesCV['environnement']}'" : 'NULL') . ', `competences_utilisees`=' . (($donneesCV['competences_utilisees'] !== '' && $donneesCV['competences_utilisees'] !== NULL) ? "'{$donneesCV['competences_utilisees']}'" : 'NULL') . ', `tags`=' . (($donneesCV['tags'] !== '' && $donneesCV['tags'] !== NULL) ? "'{$donneesCV['tags']}'" : 'NULL') . " where `id_mission`='".makeSafe($id_mission_fct)."'", $o);
    if($o['error']!='') {
        echo $o['error'];
        echo '<a href="missions_view.php?SelectedID='.urlencode($id_mission_fct)."\">{$Translation['< back']}</a>";
        exit;
    }


    // hook: missions_after_update
    if(function_exists('missions_after_update')) {
        $res = sql("SELECT * FROM `missions` WHERE `id_mission`='{$donneesCV['selectedID']}' LIMIT 1", $eo);
        if($row = db_fetch_assoc($res)) {
            $data = array_map('makeSafe', $row);
        }
        $donneesCV['selectedID'] = $donneesCV['id_mission'];
        $args = array();
        if(!missions_after_update($data, getMemberInfo(), $args)) { return; }
    }

    // mm: update ownership data
    sql("update `membership_userrecords` set `dateUpdated`='" . time() . "' where `tableName`='missions' and `pkValue`='" . makeSafe($id_mission_fct) . "'", $eo);

}


function periode_en_date ($periode_ana, $debutoufin){
    $periode_ana = str_ireplace("D\'"," ",$periode_ana);
    $periode_ana = str_ireplace("dâ€™"," ",$periode_ana);
    $periode_ana = str_ireplace("Depuis"," ",$periode_ana);
    $periode_ana = str_ireplace("De"," ",$periode_ana);
    $periode_ana = str_ireplace("à"," ",$periode_ana);
    $periode_ana = str_ireplace("Ã "," ",$periode_ana);
    $indice = 0;
    $mois_deb = "01";
    $mois_fin = "12";
    $annee_deb = "1900";
    $annee_fin = "2999";
/*        $error_message = "Chaine periode = ".$indice.var_export($periode_ana, true)."\r";
        error_log($error_message, 3, "./mes-erreurs.log");
*/    foreach( explode(' ',$periode_ana) as $chaine){
        $chaine = strtolower($chaine);
/*        $error_message = "Chaine = ".$indice.var_export($chaine, true)."\r";
        error_log($error_message, 3, "./mes-erreurs.log");
*/        switch ($chaine) {
            case "janvier" :
                $mois = '01';
                $indice = $indice + 1;
                break;
            case "février" :
                $mois = '02';
                $indice = $indice + 1;
                break;
            case "mars" :
                $mois = '03';
                $indice = $indice + 1;
                break;
            case "avril" :
                $mois = '04';
                $indice = $indice + 1;
                break;
            case "mai" :
                $mois = '05';
                $indice = $indice + 1;
                break;
            case "juin" :
                $mois = '06';
                $indice = $indice + 1;
                break;
            case "juillet" :
                $mois = '07';
                $indice = $indice + 1;
                break;
            case "aout" :
                $mois = '08';
                $indice = $indice + 1;
                break;
            case "aout" :
                $mois = '08';
                $indice = $indice + 1;
                break;
            case "septembre" :
                $mois = '09';
                $indice = $indice + 1;
                break;
            case "octobre" :
                $mois = '10';
                $indice = $indice + 1;
                break;
            case "novembre" :
                $mois = '11';
                $indice = $indice + 1;
                break;
            case "décembre" :
                $mois = '12';
                $indice = $indice + 1;
                break;
            case "dÃ©cembre" :
                $mois = '12';
                $indice = $indice + 1;
                break;
            default:
                $mois = 'NT';
/*                $error_message = $indice."Rien trouvé ".$mois." : suite = période de fin  ?\r";
                 error_log($error_message, 3, "./mes-erreurs.log");*/
        }
        if ($indice == 1 and !empty($mois) and is_numeric($mois)){$mois_deb = $mois;}
        if ($indice > 1 and !empty($mois) and is_numeric($mois)){$mois_fin = $mois;}
        if ($indice > 1 and !empty($chaine) and is_numeric($chaine) and $mois == 'NT'){$annee_fin = $chaine;}
        if ($indice == 1 and !empty($chaine) and is_numeric($chaine) and $mois == 'NT' and $annee_deb == '1900'){
            $annee_deb = $chaine;
            $indice = 2;}
        if ($indice > 0 and !empty($chaine) and is_numeric($chaine) and $mois == 'NT' and $annee_deb <> '1900'){
            $annee_fin = $chaine;
            $indice = 2;}
        if ($indice == 0 and !empty($chaine) and is_numeric($chaine) and $mois == 'NT'){
            $annee_deb = $chaine;
            $indice = 1;}
        if ($indice > 1 and $annee_deb == "2999"){$annee_deb = $annee_fin;}
/*        $error_message = "im =".$indice." MOIS = ".$mois." >> mois deb = ".$mois_deb." annee deb =".$annee_deb." mois fin = ".$mois_fin." année fin = ".$annee_fin. " \r";
    error_log($error_message, 3, "./mes-erreurs.log");
*/
    }
    switch ($debutoufin){
        case "debut" :
            $date_deb = $annee_deb."-".$mois_deb."-01";
/*            $error_message = "date deb".$date_deb;
             error_log($error_message, 3, "./mes-erreurs.log");
*/            return $date_deb;
            break;
        case "fin" :
            $jour_fin = '30';
            if ($mois_fin == '01' || $mois_fin == '03' || $mois_fin == '05' || $mois_fin == '07' || $mois_fin == '08' || $mois_fin == '10' || $mois_fin == '12'){$jour_fin = '31';}
            if ($mois_fin == '02'){$jour_fin = '28';}
            $date_fin = $annee_fin."-".$mois_fin."-".$jour_fin;
/*            $error_message = "date fin".$date_fin;
             error_log($error_message, 3, "./mes-erreurs.log");*/
            return $date_fin;
            break;
    }
}
    ?>