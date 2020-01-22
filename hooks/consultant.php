<?php

/**
 * @file
 * This file contains hook functions that get called when data operations are performed on 'consultant' table.
 * For example, when a new record is added, when a record is edited, when a record is deleted, … etc.
 */
$currDir2 = dirname( __FILE__ );
include "$currDir2/extraire_cv.php";
include "$currDir2/alim_missions_cv.php";
include "$currDir2/alim_competences_indiv_cv.php";
// error message to be logged
$error_message = "Ceci est un message erreur!\r";

// path of the log file where errors need to be logged
$log_file = "./mes-erreurs.log";

// logging error message to given log file

//error_log($error_message, 3, $log_file);
/**
 * Called before rendering the page. This is a very powerful hook that allows you to control all aspects of how the page is rendered.
 *
 * @param $options
 * (passed by reference) a DataList object that sets options for rendering the page.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/DataList
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * True to render the page. False to cancel the operation (which could be useful for error handling to display
 * an error message to the user and stop displaying any data).
 */

function consultant_init( &$options, $memberInfo, &$args )
{

    return true;
}

/**
 * Called before displaying page content. Can be used to return a customized header template for the table.
 *
 * @param $contentType
 * specifies the type of view that will be displayed. Takes one the following values:
 * 'tableview', 'detailview', 'tableview+detailview', 'print-tableview', 'print-detailview', 'filters'
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * String containing the HTML header code. If empty, the default 'header.php' is used. If you want to include
 * the default header besides your customized header, include the <%%HEADER%%> placeholder in the returned string.
 */

function consultant_header( $contentType, $memberInfo, &$args )
{
    $header = '';

    switch ( $contentType ) {
        case 'tableview':
            $header = '';
            break;

        case 'detailview':
            $header = '';
            break;

        case 'tableview+detailview':
            $header = '';
            break;

        case 'print-tableview':
            $header = '';
            break;

        case 'print-detailview':
            $header = '';
            break;

        case 'filters':
            $header = '';
            break;
    }

    return $header;
}

/**
 * Called after displaying page content. Can be used to return a customized footer template for the table.
 *
 * @param $contentType
 * specifies the type of view that will be displayed. Takes one the following values:
 * 'tableview', 'detailview', 'tableview+detailview', 'print-tableview', 'print-detailview', 'filters'
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * String containing the HTML footer code. If empty, the default 'footer.php' is used. If you want to include
 * the default footer besides your customized footer, include the <%%FOOTER%%> placeholder in the returned string.
 */

function consultant_footer( $contentType, $memberInfo, &$args )
{
    $footer = '';

    switch ( $contentType ) {
        case 'tableview':
            $footer = '';
            break;

        case 'detailview':
            $footer = '';
            break;

        case 'tableview+detailview':
            $footer = '';
            break;

        case 'print-tableview':
            $footer = '';
            break;

        case 'print-detailview':
            $footer = '';
            break;

        case 'filters':
            $footer = '';
            break;
    }

    return $footer;
}

/**
 * Called before executing the insert query.
 *
 * @param $data
 * An associative array where the keys are field names and the values are the field data values to be inserted into the new record.
 * Note: if a field is set as read-only or hidden in detail view, it can't be modified through $data. You should use a direct SQL statement instead.
 * For this table, the array items are:
 *     $data['Matricule'], $data['Prenom'], $data['Nom'], $data['email'], $data['adresse_postale'], $data['coache_par'], $data['emploi_fonctionnel']
 * $data array is passed by reference so that modifications to it apply to the insert query.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * A boolean TRUE to perform the insert operation, or FALSE to cancel it.
 */

function consultant_before_insert( &$data, $memberInfo, &$args )
{
    return true;
}

/**
 * Called after executing the insert query (but before executing the ownership insert query).
 *
 * @param $data
 * An associative array where the keys are field names and the values are the field data values that were inserted into the new record.
 * For this table, the array items are:
 *     $data['Matricule'], $data['Prenom'], $data['Nom'], $data['email'], $data['adresse_postale'], $data['saisie_par'], $data['coache_par'], $data['emploi_fonctionnel']
 * Also includes the item $data['selectedID'] which stores the value of the primary key for the new record.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * A boolean TRUE to perform the ownership insert operation or FALSE to cancel it.
 * Warning: if a FALSE is returned, the new record will have no ownership info.
 */

function consultant_after_insert( $data, $memberInfo, &$args )
{

/* Après insertion d'un nouveau consultant, vérifier si CV a été téléchargé
si OUI alors lire le CV pour alimenter les missions */

    /* chemin du CV */
    $currDir3  = dirname( __FILE__, 2 );
    $chemin_cv =
        $currDir3 . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . $data['cv_hrc'];

/*$error_message = $chemin_cv." Fichier non trouve !\r";
error_log($error_message, 3, "./mes-erreurs.log");*/

    if ( isset( $chemin_cv ) && !file_exists( $chemin_cv ) ) {
        echo $chemin_cv . " Fichier non trouve !";
        $error_message = $chemin_cv . " Fichier non trouve !\r";
        error_log( $error_message, 3, "./mes-erreurs.log" );
        exit;
    }

    $docObj = new DocxConversion( $chemin_cv );
    // extraire_cv.php
    $docText = $docObj->convertToText();

    /* On stocke le texte converti dans un tableau pour analyser plus facilement chaque ligne  */
    $tablo_danalyse = [];

    foreach ( explode( "|", $docText ) as $ligne ) {
        if ( !empty( $ligne ) ) {
            $tablo_danalyse[] = $ligne;
        }
    }

// boucle sur missions pour :

// alim_missions_cv.php
    foreach ( $tablo_danalyse as $ligne ) {
        /*$error_message = "230: Ligne analyser : ".$ligne. "\r";
        error_log($error_message, 3, "./mes-erreurs.log");*/
        $site_mission     = prendre_chaine_entre( $ligne, 'MISSION :', 'µ' );
        $site_mission_fct = ltrim( $site_mission, "\x00..\x1F" );

        if ( !empty( $site_mission_fct ) ) {$site_mission_upd =
                substr( $site_mission_fct, 0, 64 );}

        $periode = prendre_chaine_entre( $ligne, 'PERIODE :',
            '/FINBLOK' );

        if ( !empty( $periode ) ) {$periode_upd = substr( $periode, 0, 39 );}

        $objet_mission = prendre_chaine_entre( $ligne, 'OBJET :', '/FINBLOK'
        );

        if ( !empty( $objet_mission ) ) {$objet_mission_upd = $objet_mission;}

        $detail_mission = prendre_chaine_entre( $ligne, 'DETAIL :', '/FINBLOK'
        );

        if ( !empty( $detail_mission ) ) {$detail_mission_upd = $detail_mission;}

        $environnement = prendre_chaine_entre( $ligne, 'ENVIRONNEMENT :',
            '/FINBLOK' );

        if ( !empty( $environnement ) ) {$environnement_upd =
                substr( $environnement, 0, 254 );}
// si toutes les rubriques de la mission sont renseigné alors on ajoute la mission
        if ( !empty( $site_mission_upd ) && !empty( $periode_upd ) &&
            !empty( $objet_mission_upd ) && !empty( $detail_mission_upd ) ) {
            missions_cv_insert( $data, $site_mission_upd, $periode_upd,
                $objet_mission_upd,
                $detail_mission_upd, $environnement_upd );
            /* Réinitialisation des variables de stockage */
            $site_mission_upd   = '';
            $periode_upd        = '';
            $objet_mission_upd  = '';
            $detail_mission_upd = '';
            $environnement_upd  = '';
        }

        // INSERTION des Compétences individuelles décrites dans le CV
        $competence_indiv = prendre_chaine_entre( $ligne, 'COMPETENCES :',
            '/FINBLOK' );

        if ( !empty( $competence_indiv ) ) {
            $competence_indiv_upd = substr( $competence_indiv, 0, 40 );
            competences_individuelles_cv_insert( $data, $competence_indiv_upd );
        }
    }
    return true;
}

/**
 * Called before executing the update query.
 *
 * @param $data
 * An associative array where the keys are field names and the values are the field data values.
 * Note: if a field is set as read-only or hidden in detail view, it can't be modified through $data. You should use a direct SQL statement instead.
 * For this table, the array items are:
 *     $data['Matricule'], $data['Prenom'], $data['Nom'], $data['email'], $data['adresse_postale'], $data['coache_par'], $data['emploi_fonctionnel']
 * Also includes the item $data['selectedID'] which stores the value of the primary key for the record to be updated.
 * $data array is passed by reference so that modifications to it apply to the update query.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * True to perform the update operation or false to cancel it.
 */

function consultant_before_update( &$data, $memberInfo, &$args )
{

    return true;
}

/**
 * Called after executing the update query and before executing the ownership update query.
 *
 * @param $data
 * An associative array where the keys are field names and the values are the field data values.
 * For this table, the array items are:
 *     $data['id_consultant'], $data['Matricule'], $data['Prenom'], $data['Nom'], $data['email'], $data['adresse_postale'], $data['saisie_par'], $data['coache_par'], $data['emploi_fonctionnel']
 * Also includes the item $data['selectedID'] which stores the value of the primary key for the record.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * True to perform the ownership update operation or false to cancel it.
 */

function consultant_after_update( $data, $memberInfo, &$args )
{
    /* Après mise à jour d'un consultant, vérifier si CV a été télécharger
    si OUI alors lire le CV pour alimenter et mettre à jour les missions */
    /* chemin du CV */
    $currDir3  = dirname( __FILE__, 2 );
    $chemin_cv =
        $currDir3 . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . $data['cv_hrc'];

    if ( isset( $chemin_cv ) && !file_exists( $chemin_cv ) ) {
        echo $chemin_cv . " Fichier non trouve !";
        $error_message = $chemin_cv . " Fichier non trouve !\r";
        error_log( $error_message, 3, "./mes-erreurs.log" );
        exit;
    }

    $docObj = new DocxConversion( $chemin_cv );
    // extraire_cv.php
    $docText = $docObj->convertToText();

    /* On stocke le texte converti dans un tableau pour analyser plus facilement chaque ligne  */
    $tablo_danalyse = [];

    foreach ( explode( "|", $docText ) as $ligne ) {
        if ( !empty( $ligne ) ) {
            $tablo_danalyse[] = $ligne;
        }
    }

// boucle sur missions pour alimenter les rubriques de la mission :
    foreach ( $tablo_danalyse as $ligne ) {
        $site_mission     = prendre_chaine_entre( $ligne, 'MISSION :', 'µ' );
        $site_mission_fct = ltrim( $site_mission, "\x00..\x1F" );

        if ( !empty( $site_mission_fct ) ) {$site_mission_upd =
                substr( $site_mission_fct, 0, 64 );}

        $periode = prendre_chaine_entre( $ligne, 'PERIODE :',
            '/FINBLOK' );

        if ( !empty( $periode ) ) {$periode_upd = substr( $periode, 0, 39 );}

        $objet_mission = prendre_chaine_entre( $ligne, 'OBJET :', '/FINBLOK'
        );

        if ( !empty( $objet_mission ) ) {$objet_mission_upd = $objet_mission;}

        $detail_mission = prendre_chaine_entre( $ligne, 'DETAIL :', '/FINBLOK'
        );

        if ( !empty( $detail_mission ) ) {$detail_mission_upd = $detail_mission;}

        $environnement = prendre_chaine_entre( $ligne, 'ENVIRONNEMENT :',
            '/FINBLOK' );

        if ( !empty( $environnement ) ) {$environnement_upd =
                substr( $environnement, 0, 254 );}
// Si toutes les rubriques de la mission sont renseignée alors
        if ( !empty( $site_mission_upd ) && !empty( $periode_upd ) &&
            !empty( $objet_mission_upd ) && !empty( $detail_mission_upd ) ) {
/* Rechercher les missions à partir de la clé id_consultant + periode + site_mission
si id_mission trouvé alors update
sinon insert mission */
            $res =
                sql( "select `id_mission` from `missions` where `id_consultant`='" .
                makeSafe( $data['id_consultant'], false ) . "'" . " and `periode`= '" . makeSafe( $periode_upd,
                    false ) . "'" . " and `site_mission` = '" . makeSafe( $site_mission_upd, false ) . "' limit 1",
                $eo );

            if ( $row = db_fetch_assoc( $res ) ) {
                $data_mission = array_map( 'makeSafe', $row );
            }

            if ( !empty( $data_mission ) ) {
                missions_cv_update( $data_mission['id_mission'],
                    $data['id_consultant'], $site_mission_upd, $periode_upd, $objet_mission_upd,
                    $detail_mission_upd, $environnement_upd );
            } else {
                missions_cv_insert( $data, $site_mission_upd, $periode_upd,
                    $objet_mission_upd,
                    $detail_mission_upd, $environnement_upd );}

            /* Réinitialisation des variables de stockage */
            $site_mission_upd   = '';
            $periode_upd        = '';
            $objet_mission_upd  = '';
            $detail_mission_upd = '';
            $environnement_upd  = '';
        }

        // INSERTION des nouvelles Compétences individuelles
        $competence_indiv = prendre_chaine_entre( $ligne, 'COMPETENCES :',
            '/FINBLOK' );

        if ( !empty( $competence_indiv ) ) {
            $competence_indiv_upd = substr( $competence_indiv, 0, 40 );
        // Recherche si la compétence individuelle existe déjà
            $res                  =
                sql( "select `id_comp_indiv` from `competences_individuelles` where `consultant_id`='" . makeSafe( $data['id_consultant'], false ) .
                "'" . " and `Competences_specifiques`= '" . makeSafe( $competence_indiv_upd,
                    false ) . "' limit 1", $eo );

            if ( $row = db_fetch_assoc( $res ) ) {
                $data_comp_indiv = array_map( 'makeSafe', $row );
            }
        // Si la compétence spécifique n'a pas été trouvée alors on l'ajoute
            if ( empty( $data_comp_indiv ) ) {
                competences_individuelles_cv_insert( $data, $competence_indiv_upd );
            }
        }

        return true;
    }
}
/**
 * Called before deleting a record (and before performing child records check).
 *
 * @param $selectedID
 * The primary key value of the record to be deleted.
 *
 * @param $skipChecks
 * A flag passed by reference that determines whether child records check should be performed or not.
 * If you set $skipChecks to TRUE, no child records check will be made. If you set it to FALSE, the check will be performed.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * True to perform the delete operation or false to cancel it.
 */

    function consultant_before_delete( $selectedID, &$skipChecks, $memberInfo,
        &$args ) {

        return true;
    }

/**
 * Called after deleting a record.
 *
 * @param $selectedID
 * The primary key value of the record to be deleted.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * None.
 */

    function consultant_after_delete( $selectedID, $memberInfo, &$args )
    {
    }

/**
 * Called when a user requests to view the detail view (before displaying the detail view).
 *
 * @param $selectedID
 * The primary key value of the record selected. False if no record is selected (i.e. the detail view will be
 * displayed to enter a new record).
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $html
 * (passed by reference) the HTML code of the form ready to be displayed. This could be useful for manipulating
 * the code before displaying it using regular expressions, … etc.
 *
 * @param $args
 * An empty array that is passed by reference. It's currently not used but is reserved for future uses.
 *
 * @return
 * None.
 */

    function consultant_dv( $selectedID, $memberInfo, &$html, &$args )
    {
    }

/**
 * Called when a user requests to download table data as a CSV file (by clicking on the SAVE CSV button)
 *
 * @param $query
 * Contains the query that will be executed to return the data in the CSV file.
 *
 * @param $memberInfo
 * An array containing logged member's info.
 * @see https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks/memberInfo
 *
 * @param $args
 * An empty array. It's currently not used but is reserved for future uses.
 *
 * @return
 * A string containing the query to use for fetching the CSV data. If FALSE or empty is returned, the default query is used.
 */

    function consultant_csv( $query, $memberInfo, &$args )
    {

        return $query;
    }

/**
 * Called when displaying the table view to retrieve custom record actions
 *
 * @return
 * A 2D array describing custom record actions. The format of the array is:
 *   array(
 *      array(
 *         'title' => 'Title', // the title/label of the custom action as displayed to users
 *         'function' => 'js_function_name', // the name of a javascript function to be executed when user selects this action
 *         'class' => 'CSS class(es) to apply to the action title', // optional, refer to Bootstrap documentation for CSS classes
 *         'icon' => 'icon name' // optional, refer to Bootstrap glyphicons for supported names
 *      ), ...
 *   )
 */

    function consultant_batch_actions( &$args )
    {

        return [];
    }
