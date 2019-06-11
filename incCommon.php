<?php

	#########################################################
	/*
	~~~~~~ LIST OF FUNCTIONS ~~~~~~
		getTableList() -- returns an associative array (tableName => tableData, tableData is array(tableCaption, tableDescription, tableIcon)) of tables accessible by current user
		get_table_groups() -- returns an associative array (table_group => tables_array)
		getLoggedMemberID() -- returns memberID of logged member. If no login, returns anonymous memberID
		getLoggedGroupID() -- returns groupID of logged member, or anonymous groupID
		logOutMember() -- destroys session and logs member out.
		logInMember() -- checks POST login. If not valid, redirects to index.php, else returns TRUE
		getTablePermissions($tn) -- returns an array of permissions allowed for logged member to given table (allowAccess, allowInsert, allowView, allowEdit, allowDelete) -- allowAccess is set to true if any access level is allowed
		get_sql_fields($tn) -- returns the SELECT part of the table view query
		get_sql_from($tn[, true]) -- returns the FROM part of the table view query, with full joins, optionally skipping permissions if true passed as 2nd param.
		get_joined_record($table, $id[, true]) -- returns assoc array of record values for given PK value of given table, with full joins, optionally skipping permissions if true passed as 3rd param.
		get_defaults($table) -- returns assoc array of table fields as array keys and default values (or empty), excluding automatic values as array values
		htmlUserBar() -- returns html code for displaying user login status to be used on top of pages.
		showNotifications($msg, $class) -- returns html code for displaying a notification. If no parameters provided, processes the GET request for possible notifications.
		parseMySQLDate(a, b) -- returns a if valid mysql date, or b if valid mysql date, or today if b is true, or empty if b is false.
		parseCode(code) -- calculates and returns special values to be inserted in automatic fields.
		addFilter(i, filterAnd, filterField, filterOperator, filterValue) -- enforce a filter over data
		clearFilters() -- clear all filters
		getMemberInfo() -- returns an array containing the currently signed-in member's info
		loadView($view, $data) -- passes $data to templates/{$view}.php and returns the output
		loadTable($table, $data) -- loads table template, passing $data to it
		filterDropdownBy($filterable, $filterers, $parentFilterers, $parentPKField, $parentCaption, $parentTable, &$filterableCombo) -- applies cascading drop-downs for a lookup field, returns js code to be inserted into the page
		br2nl($text) -- replaces all variations of HTML <br> tags with a new line character
		htmlspecialchars_decode($text) -- inverse of htmlspecialchars()
		entitiesToUTF8($text) -- convert unicode entities (e.g. &#1234;) to actual UTF8 characters, requires multibyte string PHP extension
		func_get_args_byref() -- returns an array of arguments passed to a function, by reference
		permissions_sql($table, $level) -- returns an array containing the FROM and WHERE additions for applying permissions to an SQL query
		error_message($msg[, $back_url]) -- returns html code for a styled error message .. pass explicit false in second param to suppress back button
		toMySQLDate($formattedDate, $sep = datalist_date_separator, $ord = datalist_date_format)
		reIndex(&$arr) -- returns a copy of the given array, with keys replaced by 1-based numeric indices, and values replaced by original keys
		get_embed($provider, $url[, $width, $height, $retrieve]) -- returns embed code for a given url (supported providers: youtube, googlemap)
		check_record_permission($table, $id, $perm = 'view') -- returns true if current user has the specified permission $perm ('view', 'edit' or 'delete') for the given recors, false otherwise
		NavMenus($options) -- returns the HTML code for the top navigation menus. $options is not implemented currently.
		StyleSheet() -- returns the HTML code for included style sheet files to be placed in the <head> section.
		getUploadDir($dir) -- if dir is empty, returns upload dir configured in defaultLang.php, else returns $dir.
		PrepareUploadedFile($FieldName, $MaxSize, $FileTypes='jpg|jpeg|gif|png', $NoRename=false, $dir="") -- validates and moves uploaded file for given $FieldName into the given $dir (or the default one if empty)
		get_home_links($homeLinks, $default_classes, $tgroup) -- process $homeLinks array and return custom links for homepage. Applies $default_classes to links if links have classes defined, and filters links by $tgroup (using '*' matches all table_group values)
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	*/

	#########################################################

	function getTableList($skip_authentication = false){
		$arrAccessTables = array();
		$arrTables = array(   
			'curriculum_vitae' => array('Votre Curriculum vitae', 'Votre fiche "consultant" est disponible dans Liste => Consultant. Vous pouvez cr&#233;er votre CV.<br>Il est possible d\'avoir plusieurs CV en fonction du focus du parcours professionnel. Il est possible d\'avoir un CV qui regroupe les missions MOE, et un autre les missions MOA.', 'resources/table_icons/curriculum_vitae.png', 'Votre CV'),
			'consultant' => array('Consultant', 'Fiche contact du consultant', 'resources/table_icons/administrator.png', 'Liste'),
			'missions' => array('Vos Missions', 'Mission rattach&#233; au cv : d&#233;crivez les &#233;lements cl&#233;s de votre mission. Vous pouvez ajouter en plus les comp&#233;tences acquises lors de chacune de vos missions.', 'resources/table_icons/document_comment_above.png', 'Votre CV'),
			'competences_individuelles' => array('Vos Competences ', 'Ajoutez vos Competences individuelles mis en oeuvre lors de la mission, ainsi que le niveau acquis.', 'resources/table_icons/brick.png', 'Votre CV'),
			'client' => array('Client', 'Fiche contact du client', 'resources/table_icons/account_balances.png', 'Liste'),
			'competences_ref' => array('R&#233;f&#233;rentiel des comp&#233;tences', 'R&#233;f&#233;rentiel g&#233;n&#233;ral des comp&#233;tences mise en oeuvre au sein de la soci&#233;t&#233;', 'table.gif', 'R&#233;ferentiel'),
			'domaine' => array('Domaine', 'Domaine regroupant des comp&#233;tences', 'table.gif', 'R&#233;ferentiel'),
			'filiere' => array('Filiere', 'Fill&#232;re (MOA, MOE, TMA, d&#233;claratif)', 'table.gif', 'R&#233;ferentiel'),
			'niveaux_ref' => array('Niveaux ref', '', 'table.gif', 'R&#233;ferentiel'),
			'carriere_consultant' => array('Votre Carriere', 'Les grandes &#233;tapes de votre vie de consultant.', 'resources/table_icons/chair.png', 'Votre CV'),
			'formation_suivi' => array('Formations suivis', 'Indiquez ici la liste des formations internes ou externes que vous avez suivis', 'resources/table_icons/books.png', 'Votre CV'),
			'feedback' => array('Feedback', 'Merci d\'utiliser cette fiche de remarque pour nous permettre d\'am&#233;liorer cette application', 'resources/table_icons/bulb.png', 'Votre CV'),
			'emploi_fonctionnel' => array('Emploi fonctionnel', '', 'table.gif', 'R&#233;ferentiel')
		);
		if($skip_authentication || getLoggedAdmin()) return $arrTables;

		if(is_array($arrTables)){
			foreach($arrTables as $tn => $tc){
				$arrPerm = getTablePermissions($tn);
				if($arrPerm[0]){
					$arrAccessTables[$tn] = $tc;
				}
			}
		}

		return $arrAccessTables;
	}

	#########################################################

	function get_table_groups($skip_authentication = false){
		$tables = getTableList($skip_authentication);
		$all_groups = array('Votre CV', 'Liste', 'R&#233;ferentiel');

		$groups = array();
		foreach($all_groups as $grp){
			foreach($tables as $tn => $td){
				if($td[3] && $td[3] == $grp) $groups[$grp][] = $tn;
				if(!$td[3]) $groups[0][] = $tn;
			}
		}

		return $groups;
	}

	#########################################################

	function getTablePermissions($tn){
		static $table_permissions = array();
		if(isset($table_permissions[$tn])) return $table_permissions[$tn];

		$groupID = getLoggedGroupID();
		$memberID = makeSafe(getLoggedMemberID());
		$res_group = sql("select tableName, allowInsert, allowView, allowEdit, allowDelete from membership_grouppermissions where groupID='{$groupID}'", $eo);
		$res_user = sql("select tableName, allowInsert, allowView, allowEdit, allowDelete from membership_userpermissions where lcase(memberID)='{$memberID}'", $eo);

		while($row = db_fetch_assoc($res_group)){
			$table_permissions[$row['tableName']] = array(
				1 => intval($row['allowInsert']),
				2 => intval($row['allowView']),
				3 => intval($row['allowEdit']),
				4 => intval($row['allowDelete']),
				'insert' => intval($row['allowInsert']),
				'view' => intval($row['allowView']),
				'edit' => intval($row['allowEdit']),
				'delete' => intval($row['allowDelete'])
			);
		}

		// user-specific permissions, if specified, overwrite his group permissions
		while($row = db_fetch_assoc($res_user)){
			$table_permissions[$row['tableName']] = array(
				1 => intval($row['allowInsert']),
				2 => intval($row['allowView']),
				3 => intval($row['allowEdit']),
				4 => intval($row['allowDelete']),
				'insert' => intval($row['allowInsert']),
				'view' => intval($row['allowView']),
				'edit' => intval($row['allowEdit']),
				'delete' => intval($row['allowDelete'])
			);
		}

		// if user has any type of access, set 'access' flag
		foreach($table_permissions as $t => $p){
			$table_permissions[$t]['access'] = $table_permissions[$t][0] = false;

			if($p['insert'] || $p['view'] || $p['edit'] || $p['delete']){
				$table_permissions[$t]['access'] = $table_permissions[$t][0] = true;
			}
		}

		return $table_permissions[$tn];
	}

	#########################################################

	function get_sql_fields($table_name){
		$sql_fields = array(   
			'curriculum_vitae' => "`curriculum_vitae`.`id_cv` as 'id_cv', IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') as 'du_consultant', `curriculum_vitae`.`Titre_du_cv` as 'Titre_du_cv', `curriculum_vitae`.`creer_par` as 'creer_par'",
			'consultant' => "`consultant`.`id_consultant` as 'id_consultant', `consultant`.`Matricule` as 'Matricule', `consultant`.`Prenom` as 'Prenom', `consultant`.`Nom` as 'Nom', `consultant`.`email` as 'email', `consultant`.`adresse_postale` as 'adresse_postale', `consultant`.`saisie_par` as 'saisie_par', IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') as 'coache_par', IF(    CHAR_LENGTH(`emploi_fonctionnel1`.`titre_emploifct`), CONCAT_WS('',   `emploi_fonctionnel1`.`titre_emploifct`), '') as 'emploi_fonctionnel'",
			'missions' => "`missions`.`id_mission` as 'id_mission', IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') as 'id_consultant', IF(    CHAR_LENGTH(`consultant2`.`Prenom`) || CHAR_LENGTH(`consultant2`.`Nom`), CONCAT_WS('',   `consultant2`.`Prenom`, ', ', `consultant2`.`Nom`), '') as 'rattache_a_cv', if(`missions`.`date_debut`,date_format(`missions`.`date_debut`,'%d/%m/%Y'),'') as 'date_debut', if(`missions`.`date_fin`,date_format(`missions`.`date_fin`,'%d/%m/%Y'),'') as 'date_fin', `missions`.`description_mission` as 'description_mission', `missions`.`description_detaille` as 'description_detaille', IF(    CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `client1`.`nom_du_client`), '') as 'client'",
			'competences_individuelles' => "`competences_individuelles`.`id_comp_indiv` as 'id_comp_indiv', IF(    CHAR_LENGTH(`missions1`.`description_mission`) || CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `missions1`.`description_mission`, ' chez ', `client1`.`nom_du_client`), '') as 'rattache_a_mission', IF(    CHAR_LENGTH(`competences_ref1`.`titre_competence`), CONCAT_WS('',   `competences_ref1`.`titre_competence`), '') as 'competence_mis_en_oeuvre', IF(    CHAR_LENGTH(`niveaux_ref1`.`titre`) || CHAR_LENGTH(`niveaux_ref1`.`niveau`), CONCAT_WS('',   `niveaux_ref1`.`titre`, ' : ', `niveaux_ref1`.`niveau`), '') as 'niveau', IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') as 'consultant_id', `competences_individuelles`.`Documents_capitalises` as 'Documents_capitalises', `competences_individuelles`.`commentaires` as 'commentaires'",
			'client' => "`client`.`id_client` as 'id_client', `client`.`nom_du_client` as 'nom_du_client', `client`.`nom_du_contact` as 'nom_du_contact', `client`.`titre_du_contact` as 'titre_du_contact', `client`.`adresse_postale` as 'adresse_postale', `client`.`ville` as 'ville', `client`.`code_postal` as 'code_postal', `client`.`adresse_gm` as 'adresse_gm', `client`.`pays` as 'pays', `client`.`telephone_contact` as 'telephone_contact', `client`.`email` as 'email', `client`.`commentaires` as 'commentaires'",
			'competences_ref' => "`competences_ref`.`id_competence` as 'id_competence', `competences_ref`.`titre_competence` as 'titre_competence', `competences_ref`.`description` as 'description', IF(    CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') as 'domaine_principal', `competences_ref`.`domaine` as 'domaine'",
			'domaine' => "`domaine`.`id_domaine` as 'id_domaine', `domaine`.`titre_domaine` as 'titre_domaine', `domaine`.`description` as 'description', IF(    CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `filiere1`.`titre_filiere`), '') as 'rattache_a_filiere'",
			'filiere' => "`filiere`.`id_filiere` as 'id_filiere', `filiere`.`titre_filiere` as 'titre_filiere', `filiere`.`description` as 'description'",
			'niveaux_ref' => "`niveaux_ref`.`id_niveau` as 'id_niveau', `niveaux_ref`.`titre` as 'titre', `niveaux_ref`.`niveau` as 'niveau', `niveaux_ref`.`description` as 'description'",
			'carriere_consultant' => "`carriere_consultant`.`id_carriere_conslt` as 'id_carriere_conslt', if(`carriere_consultant`.`date_debut`,date_format(`carriere_consultant`.`date_debut`,'%d/%m/%Y'),'') as 'date_debut', if(`carriere_consultant`.`date_fin`,date_format(`carriere_consultant`.`date_fin`,'%d/%m/%Y'),'') as 'date_fin', `carriere_consultant`.`titre_emploi` as 'titre_emploi'",
			'formation_suivi' => "`formation_suivi`.`id` as 'id', `formation_suivi`.`titre` as 'titre', `formation_suivi`.`niveau` as 'niveau', if(`formation_suivi`.`date_deb`,date_format(`formation_suivi`.`date_deb`,'%d/%m/%Y'),'') as 'date_deb', if(`formation_suivi`.`date_fin`,date_format(`formation_suivi`.`date_fin`,'%d/%m/%Y'),'') as 'date_fin', IF(    CHAR_LENGTH(`competences_ref1`.`titre_competence`) || CHAR_LENGTH(`domaine2`.`titre_domaine`) || CHAR_LENGTH(`filiere2`.`titre_filiere`), CONCAT_WS('',   `competences_ref1`.`titre_competence`, ' ', `domaine2`.`titre_domaine`, '>', `filiere2`.`titre_filiere`), '') as 'competence_principale', IF(    CHAR_LENGTH(`competences_ref1`.`titre_competence`) || CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `competences_ref1`.`titre_competence`, ' ', `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') as 'competence_secondaire', `formation_suivi`.`evaluation` as 'evaluation', IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ' ', `consultant1`.`Nom`), '') as 'consultant_id', `formation_suivi`.`organisme` as 'organisme', `formation_suivi`.`commentaire` as 'commentaire'",
			'feedback' => "`feedback`.`id` as 'id', `feedback`.`titre` as 'titre', `feedback`.`fonctionnalite` as 'fonctionnalite', `feedback`.`description` as 'description', `feedback`.`plus` as 'plus', `feedback`.`moins` as 'moins'",
			'emploi_fonctionnel' => "`emploi_fonctionnel`.`id_ef` as 'id_ef', `emploi_fonctionnel`.`titre_emploifct` as 'titre_emploifct', `emploi_fonctionnel`.`grade` as 'grade', `emploi_fonctionnel`.`echelon` as 'echelon', `emploi_fonctionnel`.`description` as 'description'"
		);

		if(isset($sql_fields[$table_name])){
			return $sql_fields[$table_name];
		}

		return false;
	}

	#########################################################

	function get_sql_from($table_name, $skip_permissions = false){
		$sql_from = array(   
			'curriculum_vitae' => "`curriculum_vitae` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`curriculum_vitae`.`du_consultant` ",
			'consultant' => "`consultant` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`consultant`.`coache_par` LEFT JOIN `emploi_fonctionnel` as emploi_fonctionnel1 ON `emploi_fonctionnel1`.`id_ef`=`consultant`.`emploi_fonctionnel` ",
			'missions' => "`missions` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`missions`.`id_consultant` LEFT JOIN `curriculum_vitae` as curriculum_vitae1 ON `curriculum_vitae1`.`id_cv`=`missions`.`rattache_a_cv` LEFT JOIN `consultant` as consultant2 ON `consultant2`.`id_consultant`=`curriculum_vitae1`.`du_consultant` LEFT JOIN `client` as client1 ON `client1`.`id_client`=`missions`.`client` ",
			'competences_individuelles' => "`competences_individuelles` LEFT JOIN `missions` as missions1 ON `missions1`.`id_mission`=`competences_individuelles`.`rattache_a_mission` LEFT JOIN `client` as client1 ON `client1`.`id_client`=`missions1`.`client` LEFT JOIN `competences_ref` as competences_ref1 ON `competences_ref1`.`id_competence`=`competences_individuelles`.`competence_mis_en_oeuvre` LEFT JOIN `niveaux_ref` as niveaux_ref1 ON `niveaux_ref1`.`id_niveau`=`competences_individuelles`.`niveau` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`competences_individuelles`.`consultant_id` ",
			'client' => "`client` ",
			'competences_ref' => "`competences_ref` LEFT JOIN `domaine` as domaine1 ON `domaine1`.`id_domaine`=`competences_ref`.`domaine_principal` LEFT JOIN `filiere` as filiere1 ON `filiere1`.`id_filiere`=`domaine1`.`rattache_a_filiere` ",
			'domaine' => "`domaine` LEFT JOIN `filiere` as filiere1 ON `filiere1`.`id_filiere`=`domaine`.`rattache_a_filiere` ",
			'filiere' => "`filiere` ",
			'niveaux_ref' => "`niveaux_ref` ",
			'carriere_consultant' => "`carriere_consultant` ",
			'formation_suivi' => "`formation_suivi` LEFT JOIN `competences_ref` as competences_ref1 ON `competences_ref1`.`id_competence`=`formation_suivi`.`competence_secondaire` LEFT JOIN `domaine` as domaine1 ON `domaine1`.`id_domaine`=`competences_ref1`.`domaine_principal` LEFT JOIN `filiere` as filiere1 ON `filiere1`.`id_filiere`=`domaine1`.`rattache_a_filiere` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`formation_suivi`.`consultant_id` LEFT JOIN `domaine` as domaine2 ON `domaine2`.`id_domaine`=`competences_ref1`.`domaine_principal` LEFT JOIN `filiere` as filiere2 ON `filiere2`.`id_filiere`=`domaine2`.`rattache_a_filiere` ",
			'feedback' => "`feedback` ",
			'emploi_fonctionnel' => "`emploi_fonctionnel` "
		);

		$pkey = array(   
			'curriculum_vitae' => 'id_cv',
			'consultant' => 'id_consultant',
			'missions' => 'id_mission',
			'competences_individuelles' => 'id_comp_indiv',
			'client' => 'id_client',
			'competences_ref' => 'id_competence',
			'domaine' => 'id_domaine',
			'filiere' => 'id_filiere',
			'niveaux_ref' => 'id_niveau',
			'carriere_consultant' => 'id_carriere_conslt',
			'formation_suivi' => 'id',
			'feedback' => 'id',
			'emploi_fonctionnel' => 'id_ef'
		);

		if(isset($sql_from[$table_name])){
			if($skip_permissions) return $sql_from[$table_name];

			// mm: build the query based on current member's permissions
			$perm = getTablePermissions($table_name);
			if($perm[2] == 1){ // view owner only
				$sql_from[$table_name] .= ", membership_userrecords WHERE `{$table_name}`.`{$pkey[$table_name]}`=membership_userrecords.pkValue and membership_userrecords.tableName='{$table_name}' and lcase(membership_userrecords.memberID)='" . getLoggedMemberID() . "'";
			}elseif($perm[2] == 2){ // view group only
				$sql_from[$table_name] .= ", membership_userrecords WHERE `{$table_name}`.`{$pkey[$table_name]}`=membership_userrecords.pkValue and membership_userrecords.tableName='{$table_name}' and membership_userrecords.groupID='" . getLoggedGroupID() . "'";
			}elseif($perm[2] == 3){ // view all
				$sql_from[$table_name] .= ' WHERE 1=1';
			}else{ // view none
				return false;
			}
			return $sql_from[$table_name];
		}

		return false;
	}

	#########################################################

	function get_joined_record($table, $id, $skip_permissions = false){
		$sql_fields = get_sql_fields($table);
		$sql_from = get_sql_from($table, $skip_permissions);

		if(!$sql_fields || !$sql_from) return false;

		$pk = getPKFieldName($table);
		if(!$pk) return false;

		$safe_id = makeSafe($id, false);
		$sql = "SELECT {$sql_fields} FROM {$sql_from} AND `{$table}`.`{$pk}`='{$safe_id}'";
		$eo['silentErrors'] = true;
		$res = sql($sql, $eo);
		if($row = db_fetch_assoc($res)) return $row;

		return false;
	}

	#########################################################

	function get_defaults($table){
		/* array of tables and their fields, with default values (or empty), excluding automatic values */
		$defaults = array(
			'curriculum_vitae' => array(
				'id_cv' => '',
				'du_consultant' => '',
				'Titre_du_cv' => '',
				'creer_par' => ''
			),
			'consultant' => array(
				'id_consultant' => '',
				'Matricule' => '',
				'Prenom' => '',
				'Nom' => '',
				'email' => '',
				'adresse_postale' => '',
				'saisie_par' => '',
				'coache_par' => '',
				'emploi_fonctionnel' => ''
			),
			'missions' => array(
				'id_mission' => '',
				'id_consultant' => '',
				'rattache_a_cv' => '',
				'date_debut' => '',
				'date_fin' => '',
				'description_mission' => '',
				'description_detaille' => '',
				'client' => ''
			),
			'competences_individuelles' => array(
				'id_comp_indiv' => '',
				'rattache_a_mission' => '',
				'competence_mis_en_oeuvre' => '',
				'niveau' => '',
				'consultant_id' => '',
				'Documents_capitalises' => '',
				'commentaires' => ''
			),
			'client' => array(
				'id_client' => '',
				'nom_du_client' => '',
				'nom_du_contact' => '',
				'titre_du_contact' => '',
				'adresse_postale' => '',
				'ville' => '',
				'code_postal' => '',
				'adresse_gm' => '',
				'pays' => '',
				'telephone_contact' => '',
				'email' => '',
				'commentaires' => ''
			),
			'competences_ref' => array(
				'id_competence' => '',
				'titre_competence' => '',
				'description' => '',
				'domaine_principal' => '',
				'domaine' => ''
			),
			'domaine' => array(
				'id_domaine' => '',
				'titre_domaine' => '',
				'description' => '',
				'rattache_a_filiere' => ''
			),
			'filiere' => array(
				'id_filiere' => '',
				'titre_filiere' => '',
				'description' => ''
			),
			'niveaux_ref' => array(
				'id_niveau' => '',
				'titre' => '',
				'niveau' => '',
				'description' => ''
			),
			'carriere_consultant' => array(
				'id_carriere_conslt' => '',
				'date_debut' => '',
				'date_fin' => '',
				'titre_emploi' => ''
			),
			'formation_suivi' => array(
				'id' => '',
				'titre' => '',
				'niveau' => '',
				'date_deb' => '',
				'date_fin' => '',
				'competence_principale' => '',
				'competence_secondaire' => '',
				'evaluation' => '',
				'consultant_id' => '',
				'organisme' => '',
				'commentaire' => ''
			),
			'feedback' => array(
				'id' => '',
				'titre' => '',
				'fonctionnalite' => '',
				'description' => '',
				'plus' => '',
				'moins' => ''
			),
			'emploi_fonctionnel' => array(
				'id_ef' => '',
				'titre_emploifct' => '',
				'grade' => '',
				'echelon' => '',
				'description' => ''
			)
		);

		return isset($defaults[$table]) ? $defaults[$table] : array();
	}

	#########################################################

	function getLoggedGroupID(){
		if($_SESSION['memberGroupID']!=''){
			return $_SESSION['memberGroupID'];
		}else{
			if(!setAnonymousAccess()) return false;
			return getLoggedGroupID();
		}
	}

	#########################################################

	function getLoggedMemberID(){
		if($_SESSION['memberID']!=''){
			return strtolower($_SESSION['memberID']);
		}else{
			if(!setAnonymousAccess()) return false;
			return getLoggedMemberID();
		}
	}

	#########################################################

	function setAnonymousAccess(){
		$adminConfig = config('adminConfig');
		$anon_group_safe = addslashes($adminConfig['anonymousGroup']);
		$anon_user_safe = strtolower(addslashes($adminConfig['anonymousMember']));

		$eo = array('silentErrors' => true);

		$res = sql("select groupID from membership_groups where name='{$anon_group_safe}'", $eo);
		if(!$res){ return false; }
		$row = db_fetch_array($res); $anonGroupID = $row[0];

		$_SESSION['memberGroupID'] = ($anonGroupID ? $anonGroupID : 0);

		$res = sql("select lcase(memberID) from membership_users where lcase(memberID)='{$anon_user_safe}' and groupID='{$anonGroupID}'", $eo);
		if(!$res){ return false; }
		$row = db_fetch_array($res); $anonMemberID = $row[0];

		$_SESSION['memberID'] = ($anonMemberID ? $anonMemberID : 0);

		return true;
	}

	#########################################################

	function logInMember(){
		$redir = 'index.php';
		if($_POST['signIn'] != ''){
			if($_POST['username'] != '' && $_POST['password'] != ''){
				$username = makeSafe(strtolower($_POST['username']));
				$password = md5($_POST['password']);

				if(sqlValue("select count(1) from membership_users where lcase(memberID)='$username' and passMD5='$password' and isApproved=1 and isBanned=0")==1){
					$_SESSION['memberID']=$username;
					$_SESSION['memberGroupID']=sqlValue("select groupID from membership_users where lcase(memberID)='$username'");
					if($_POST['rememberMe']==1){
						@setcookie('hrcv3_rememberMe', md5($username.$password), time()+86400*30);
					}else{
						@setcookie('hrcv3_rememberMe', '', time()-86400*30);
					}

					// hook: login_ok
					if(function_exists('login_ok')){
						$args=array();
						if(!$redir=login_ok(getMemberInfo(), $args)){
							$redir='index.php';
						}
					}

					redirect($redir);
					exit;
				}
			}

			// hook: login_failed
			if(function_exists('login_failed')){
				$args=array();
				login_failed(array(
					'username' => $_POST['username'],
					'password' => $_POST['password'],
					'IP' => $_SERVER['REMOTE_ADDR']
					), $args);
			}

			if(!headers_sent()) header('HTTP/1.0 403 Forbidden');
			redirect("index.php?loginFailed=1");
			exit;
		}elseif((!$_SESSION['memberID'] || $_SESSION['memberID']==$adminConfig['anonymousMember']) && $_COOKIE['hrcv3_rememberMe']!=''){
			$chk=makeSafe($_COOKIE['hrcv3_rememberMe']);
			if($username=sqlValue("select memberID from membership_users where convert(md5(concat(memberID, passMD5)), char)='$chk' and isBanned=0")){
				$_SESSION['memberID']=$username;
				$_SESSION['memberGroupID']=sqlValue("select groupID from membership_users where lcase(memberID)='$username'");
			}
		}
	}

	#########################################################

	function logOutMember(){
		logOutUser();
		redirect("index.php?signIn=1");
	}

	#########################################################

	function htmlUserBar(){
		global $adminConfig, $Translation;
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');

		ob_start();
		$home_page = (basename($_SERVER['PHP_SELF'])=='index.php' ? true : false);

		?>
		<nav class="navbar navbar-default navbar-fixed-top hidden-print" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<!-- application title is obtained from the name besides the yellow database icon in AppGini, use underscores for spaces -->
				<a class="navbar-brand" href="<?php echo PREPEND_PATH; ?>index.php"><i class="glyphicon glyphicon-home"></i> hrcv3</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?php if(!$home_page){ ?>
						<?php echo NavMenus(); ?>
					<?php } ?>
				</ul>

				<?php if(getLoggedAdmin()){ ?>
					<ul class="nav navbar-nav">
						<a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn hidden-xs" title="<?php echo html_attr($Translation['admin area']); ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['admin area']; ?></a>
						<a href="<?php echo PREPEND_PATH; ?>admin/pageHome.php" class="btn btn-danger navbar-btn visible-xs btn-lg" title="<?php echo html_attr($Translation['admin area']); ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['admin area']; ?></a>
					</ul>
				<?php } ?>

				<?php if(!$_GET['signIn'] && !$_GET['loginFailed']){ ?>
					<?php if(getLoggedMemberID() == $adminConfig['anonymousMember']){ ?>
						<p class="navbar-text navbar-right">&nbsp;</p>
						<a href="<?php echo PREPEND_PATH; ?>index.php?signIn=1" class="btn btn-success navbar-btn navbar-right"><?php echo $Translation['sign in']; ?></a>
						<p class="navbar-text navbar-right">
							<?php echo $Translation['not signed in']; ?>
						</p>
					<?php }else{ ?>
						<ul class="nav navbar-nav navbar-right hidden-xs" style="min-width: 330px;">
							<a class="btn navbar-btn btn-default" href="<?php echo PREPEND_PATH; ?>index.php?signOut=1"><i class="glyphicon glyphicon-log-out"></i> <?php echo $Translation['sign out']; ?></a>
							<p class="navbar-text">
								<?php echo $Translation['signed as']; ?> <strong><a href="<?php echo PREPEND_PATH; ?>membership_profile.php" class="navbar-link"><?php echo getLoggedMemberID(); ?></a></strong>
							</p>
						</ul>
						<ul class="nav navbar-nav visible-xs">
							<a class="btn navbar-btn btn-default btn-lg visible-xs" href="<?php echo PREPEND_PATH; ?>index.php?signOut=1"><i class="glyphicon glyphicon-log-out"></i> <?php echo $Translation['sign out']; ?></a>
							<p class="navbar-text text-center">
								<?php echo $Translation['signed as']; ?> <strong><a href="<?php echo PREPEND_PATH; ?>membership_profile.php" class="navbar-link"><?php echo getLoggedMemberID(); ?></a></strong>
							</p>
						</ul>
						<script>
							/* periodically check if user is still signed in */
							setInterval(function(){
								$j.ajax({
									url: '<?php echo PREPEND_PATH; ?>ajax_check_login.php',
									success: function(username){
										if(!username.length) window.location = '<?php echo PREPEND_PATH; ?>index.php?signIn=1';
									}
								});
							}, 60000);
						</script>
					<?php } ?>
				<?php } ?>
			</div>
		</nav>
		<?php

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	#########################################################

	function showNotifications($msg = '', $class = '', $fadeout = true){
		global $Translation;

		$notify_template_no_fadeout = '<div id="%%ID%%" class="alert alert-dismissable %%CLASS%%" style="display: none; padding-top: 6px; padding-bottom: 6px;">' .
					'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' .
					'%%MSG%%</div>' .
					'<script> jQuery(function(){ jQuery("#%%ID%%").show("slow"); }); </script>'."\n";
		$notify_template = '<div id="%%ID%%" class="alert %%CLASS%%" style="display: none; padding-top: 6px; padding-bottom: 6px;">%%MSG%%</div>' .
					'<script>' .
						'jQuery(function(){' .
							'jQuery("#%%ID%%").show("slow", function(){' .
								'setTimeout(function(){ jQuery("#%%ID%%").hide("slow"); }, 4000);' .
							'});' .
						'});' .
					'</script>'."\n";

		if(!$msg){ // if no msg, use url to detect message to display
			if($_REQUEST['record-added-ok'] != ''){
				$msg = $Translation['new record saved'];
				$class = 'alert-success';
			}elseif($_REQUEST['record-added-error'] != ''){
				$msg = $Translation['Couldn\'t save the new record'];
				$class = 'alert-danger';
				$fadeout = false;
			}elseif($_REQUEST['record-updated-ok'] != ''){
				$msg = $Translation['record updated'];
				$class = 'alert-success';
			}elseif($_REQUEST['record-updated-error'] != ''){
				$msg = $Translation['Couldn\'t save changes to the record'];
				$class = 'alert-danger';
				$fadeout = false;
			}elseif($_REQUEST['record-deleted-ok'] != ''){
				$msg = $Translation['The record has been deleted successfully'];
				$class = 'alert-success';
				$fadeout = false;
			}elseif($_REQUEST['record-deleted-error'] != ''){
				$msg = $Translation['Couldn\'t delete this record'];
				$class = 'alert-danger';
				$fadeout = false;
			}else{
				return '';
			}
		}
		$id = 'notification-' . rand();

		$out = ($fadeout ? $notify_template : $notify_template_no_fadeout);
		$out = str_replace('%%ID%%', $id, $out);
		$out = str_replace('%%MSG%%', $msg, $out);
		$out = str_replace('%%CLASS%%', $class, $out);

		return $out;
	}

	#########################################################

	function parseMySQLDate($date, $altDate){
		// is $date valid?
		if(preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", trim($date))){
			return trim($date);
		}

		if($date != '--' && preg_match("/^\d{4}-\d{1,2}-\d{1,2}$/", trim($altDate))){
			return trim($altDate);
		}

		if($date != '--' && $altDate && intval($altDate)==$altDate){
			return @date('Y-m-d', @time() + ($altDate >= 1 ? $altDate - 1 : $altDate) * 86400);
		}

		return '';
	}

	#########################################################

	function parseCode($code, $isInsert=true, $rawData=false){
		if($isInsert){
			$arrCodes=array(
				'<%%creatorusername%%>' => $_SESSION['memberID'],
				'<%%creatorgroupid%%>' => $_SESSION['memberGroupID'],
				'<%%creatorip%%>' => $_SERVER['REMOTE_ADDR'],
				'<%%creatorgroup%%>' => sqlValue("select name from membership_groups where groupID='{$_SESSION['memberGroupID']}'"),

				'<%%creationdate%%>' => ($rawData ? @date('Y-m-d') : @date('j/n/Y')),
				'<%%creationtime%%>' => ($rawData ? @date('H:i:s') : @date('h:i:s a')),
				'<%%creationdatetime%%>' => ($rawData ? @date('Y-m-d H:i:s') : @date('j/n/Y h:i:s a')),
				'<%%creationtimestamp%%>' => ($rawData ? @date('Y-m-d H:i:s') : @time())
			);
		}else{
			$arrCodes=array(
				'<%%editorusername%%>' => $_SESSION['memberID'],
				'<%%editorgroupid%%>' => $_SESSION['memberGroupID'],
				'<%%editorip%%>' => $_SERVER['REMOTE_ADDR'],
				'<%%editorgroup%%>' => sqlValue("select name from membership_groups where groupID='{$_SESSION['memberGroupID']}'"),

				'<%%editingdate%%>' => ($rawData ? @date('Y-m-d') : @date('j/n/Y')),
				'<%%editingtime%%>' => ($rawData ? @date('H:i:s') : @date('h:i:s a')),
				'<%%editingdatetime%%>' => ($rawData ? @date('Y-m-d H:i:s') : @date('j/n/Y h:i:s a')),
				'<%%editingtimestamp%%>' => ($rawData ? @date('Y-m-d H:i:s') : @time())
			);
		}

		$pc=str_ireplace(array_keys($arrCodes), array_values($arrCodes), $code);

		return $pc;
	}

	#########################################################

	function addFilter($index, $filterAnd, $filterField, $filterOperator, $filterValue){
		// validate input
		if($index < 1 || $index > 80 || !is_int($index)) return false;
		if($filterAnd != 'or')   $filterAnd = 'and';
		$filterField = intval($filterField);

		/* backward compatibility */
		if(in_array($filterOperator, $GLOBALS['filter_operators'])){
			$filterOperator = array_search($filterOperator, $GLOBALS['filter_operators']);
		}

		if(!in_array($filterOperator, array_keys($GLOBALS['filter_operators']))){
			$filterOperator = 'like';
		}

		if(!$filterField){
			$filterOperator = '';
			$filterValue = '';
		}

		$_REQUEST['FilterAnd'][$index] = $filterAnd;
		$_REQUEST['FilterField'][$index] = $filterField;
		$_REQUEST['FilterOperator'][$index] = $filterOperator;
		$_REQUEST['FilterValue'][$index] = $filterValue;

		return true;
	}

	#########################################################

	function clearFilters(){
		for($i=1; $i<=80; $i++){
			addFilter($i, '', 0, '', '');
		}
	}

	#########################################################

	function getMemberInfo($memberID = ''){
		static $member_info = array();

		if(!$memberID){
			$memberID = getLoggedMemberID();
		}

		// return cached results, if present
		if(isset($member_info[$memberID])) return $member_info[$memberID];

		$adminConfig = config('adminConfig');
		$mi = array();

		if($memberID){
			$res = sql("select * from membership_users where memberID='" . makeSafe($memberID) . "'", $eo);
			if($row = db_fetch_assoc($res)){
				$mi = array(
					'username' => $memberID,
					'groupID' => $row['groupID'],
					'group' => sqlValue("select name from membership_groups where groupID='{$row['groupID']}'"),
					'admin' => ($adminConfig['adminUsername'] == $memberID ? true : false),
					'email' => $row['email'],
					'custom' => array(
						$row['custom1'], 
						$row['custom2'], 
						$row['custom3'], 
						$row['custom4']
					),
					'banned' => ($row['isBanned'] ? true : false),
					'approved' => ($row['isApproved'] ? true : false),
					'signupDate' => @date('n/j/Y', @strtotime($row['signupDate'])),
					'comments' => $row['comments'],
					'IP' => $_SERVER['REMOTE_ADDR']
				);

				// cache results
				$member_info[$memberID] = $mi;
			}
		}

		return $mi;
	}

	#########################################################

	if(!function_exists('str_ireplace')){
		function str_ireplace($search, $replace, $subject){
			$ret=$subject;
			if(is_array($search)){
				for($i=0; $i<count($search); $i++){
					$ret=str_ireplace($search[$i], $replace[$i], $ret);
				}
			}else{
				$ret=preg_replace('/'.preg_quote($search, '/').'/i', $replace, $ret);
			}

			return $ret;
		} 
	} 

	#########################################################

	/**
	* Loads a given view from the templates folder, passing the given data to it
	* @param $view the name of a php file (without extension) to be loaded from the 'templates' folder
	* @param $the_data_to_pass_to_the_view (optional) associative array containing the data to pass to the view
	* @return the output of the parsed view as a string
	*/
	function loadView($view, $the_data_to_pass_to_the_view=false){
		global $Translation;

		$view = dirname(__FILE__)."/templates/$view.php";
		if(!is_file($view)) return false;

		if(is_array($the_data_to_pass_to_the_view)){
			foreach($the_data_to_pass_to_the_view as $k => $v)
				$$k = $v;
		}
		unset($the_data_to_pass_to_the_view, $k, $v);

		ob_start();
		@include($view);
		$out=ob_get_contents();
		ob_end_clean();

		return $out;
	}

	#########################################################

	/**
	* Loads a table template from the templates folder, passing the given data to it
	* @param $table_name the name of the table whose template is to be loaded from the 'templates' folder
	* @param $the_data_to_pass_to_the_table associative array containing the data to pass to the table template
	* @return the output of the parsed table template as a string
	*/
	function loadTable($table_name, $the_data_to_pass_to_the_table = array()){
		$dont_load_header = $the_data_to_pass_to_the_table['dont_load_header'];
		$dont_load_footer = $the_data_to_pass_to_the_table['dont_load_footer'];

		$header = $table = $footer = '';

		if(!$dont_load_header){
			// try to load tablename-header
			if(!($header = loadView("{$table_name}-header", $the_data_to_pass_to_the_table))){
				$header = loadView('table-common-header', $the_data_to_pass_to_the_table);
			}
		}

		$table = loadView($table_name, $the_data_to_pass_to_the_table);

		if(!$dont_load_footer){
			// try to load tablename-footer
			if(!($footer = loadView("{$table_name}-footer", $the_data_to_pass_to_the_table))){
				$footer = loadView('table-common-footer', $the_data_to_pass_to_the_table);
			}
		}

		return "{$header}{$table}{$footer}";
	}

	#########################################################

	function filterDropdownBy($filterable, $filterers, $parentFilterers, $parentPKField, $parentCaption, $parentTable, &$filterableCombo){
		$filterersArray = explode(',', $filterers);
		$parentFilterersArray = explode(',', $parentFilterers);
		$parentFiltererList = '`' . implode('`, `', $parentFilterersArray) . '`';
		$res=sql("SELECT `$parentPKField`, $parentCaption, $parentFiltererList FROM `$parentTable` ORDER BY 2", $eo);
		$filterableData = array();
		while($row=db_fetch_row($res)){
			$filterableData[$row[0]] = $row[1];
			$filtererIndex = 0;
			foreach($filterersArray as $filterer){
				$filterableDataByFilterer[$filterer][$row[$filtererIndex + 2]][$row[0]] = $row[1];
				$filtererIndex++;
			}
			$row[0] = addslashes($row[0]);
			$row[1] = addslashes($row[1]);
			$jsonFilterableData .= "\"{$row[0]}\":\"{$row[1]}\",";
		}
		$jsonFilterableData .= '}';
		$jsonFilterableData = '{'.str_replace(',}', '}', $jsonFilterableData);     
		$filterJS = "\nvar {$filterable}_data = $jsonFilterableData;";

		foreach($filterersArray as $filterer){
			if(is_array($filterableDataByFilterer[$filterer])) foreach($filterableDataByFilterer[$filterer] as $filtererItem => $filterableItem){
				$jsonFilterableDataByFilterer[$filterer] .= '"'.addslashes($filtererItem).'":{';
				foreach($filterableItem as $filterableItemID => $filterableItemData){
					$jsonFilterableDataByFilterer[$filterer] .= '"'.addslashes($filterableItemID).'":"'.addslashes($filterableItemData).'",';
				}
				$jsonFilterableDataByFilterer[$filterer] .= '},';
			}
			$jsonFilterableDataByFilterer[$filterer] .= '}';
			$jsonFilterableDataByFilterer[$filterer] = '{'.str_replace(',}', '}', $jsonFilterableDataByFilterer[$filterer]);

			$filterJS.="\n\n// code for filtering {$filterable} by {$filterer}\n";
			$filterJS.="\nvar {$filterable}_data_by_{$filterer} = {$jsonFilterableDataByFilterer[$filterer]}; ";
			$filterJS.="\nvar selected_{$filterable} = \$j('#{$filterable}').val();";
			$filterJS.="\nvar {$filterable}_change_by_{$filterer} = function(){";
			$filterJS.="\n\t$('{$filterable}').options.length=0;";
			$filterJS.="\n\t$('{$filterable}').options[0] = new Option();";
			$filterJS.="\n\tif(\$j('#{$filterer}').val()){";
			$filterJS.="\n\t\tfor({$filterable}_item in {$filterable}_data_by_{$filterer}[\$j('#{$filterer}').val()]){";
			$filterJS.="\n\t\t\t$('{$filterable}').options[$('{$filterable}').options.length] = new Option(";
			$filterJS.="\n\t\t\t\t{$filterable}_data_by_{$filterer}[\$j('#{$filterer}').val()][{$filterable}_item],";
			$filterJS.="\n\t\t\t\t{$filterable}_item,";
			$filterJS.="\n\t\t\t\t({$filterable}_item == selected_{$filterable} ? true : false),";
			$filterJS.="\n\t\t\t\t({$filterable}_item == selected_{$filterable} ? true : false)";
			$filterJS.="\n\t\t\t);";
			$filterJS.="\n\t\t}";
			$filterJS.="\n\t}else{";
			$filterJS.="\n\t\tfor({$filterable}_item in {$filterable}_data){";
			$filterJS.="\n\t\t\t$('{$filterable}').options[$('{$filterable}').options.length] = new Option(";
			$filterJS.="\n\t\t\t\t{$filterable}_data[{$filterable}_item],";
			$filterJS.="\n\t\t\t\t{$filterable}_item,";
			$filterJS.="\n\t\t\t\t({$filterable}_item == selected_{$filterable} ? true : false),";
			$filterJS.="\n\t\t\t\t({$filterable}_item == selected_{$filterable} ? true : false)";
			$filterJS.="\n\t\t\t);";
			$filterJS.="\n\t\t}";
			$filterJS.="\n\t\tif(selected_{$filterable} && selected_{$filterable} == \$j('#{$filterable}').val()){";
			$filterJS.="\n\t\t\tfor({$filterer}_item in {$filterable}_data_by_{$filterer}){";
			$filterJS.="\n\t\t\t\tfor({$filterable}_item in {$filterable}_data_by_{$filterer}[{$filterer}_item]){";
			$filterJS.="\n\t\t\t\t\tif({$filterable}_item == selected_{$filterable}){";
			$filterJS.="\n\t\t\t\t\t\t$('{$filterer}').value = {$filterer}_item;";
			$filterJS.="\n\t\t\t\t\t\tbreak;";
			$filterJS.="\n\t\t\t\t\t}";
			$filterJS.="\n\t\t\t\t}";
			$filterJS.="\n\t\t\t\tif({$filterable}_item == selected_{$filterable}) break;";
			$filterJS.="\n\t\t\t}";
			$filterJS.="\n\t\t}";
			$filterJS.="\n\t}";
			$filterJS.="\n\t$('{$filterable}').highlight();";
			$filterJS.="\n};";
			$filterJS.="\n$('{$filterer}').observe('change', function(){ window.setTimeout({$filterable}_change_by_{$filterer}, 25); });";
			$filterJS.="\n";
		}

		$filterableCombo = new Combo;
		$filterableCombo->ListType = 0;
		$filterableCombo->ListItem = array_slice(array_values($filterableData), 0, 10);
		$filterableCombo->ListData = array_slice(array_keys($filterableData), 0, 10);
		$filterableCombo->SelectName = $filterable;
		$filterableCombo->AllowNull = true;

		return $filterJS;
	}

	#########################################################
	function br2nl($text){
		return  preg_replace('/\<br(\s*)?\/?\>/i', "\n", $text);
	}

	#########################################################

	if(!function_exists('htmlspecialchars_decode')){
		function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT){
			return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
		}
	}

	#########################################################

	function entitiesToUTF8($input){
		return preg_replace_callback('/(&#[0-9]+;)/', '_toUTF8', $input);
	}

	function _toUTF8($m){
		if(function_exists('mb_convert_encoding')){
			return mb_convert_encoding($m[1], "UTF-8", "HTML-ENTITIES");
		}else{
			return $m[1];
		}
	}

	#########################################################

	function func_get_args_byref() {
		if(!function_exists('debug_backtrace')) return false;

		$trace = debug_backtrace();
		return $trace[1]['args'];
	}

	#########################################################

	function permissions_sql($table, $level = 'all'){
		if(!in_array($level, array('user', 'group'))){ $level = 'all'; }
		$perm = getTablePermissions($table);
		$from = '';
		$where = '';
		$pk = getPKFieldName($table);

		if($perm[2] == 1 || ($perm[2] > 1 && $level == 'user')){ // view owner only
			$from = 'membership_userrecords';
			$where = "(`$table`.`$pk`=membership_userrecords.pkValue and membership_userrecords.tableName='$table' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."')";
		}elseif($perm[2] == 2 || ($perm[2] > 2 && $level == 'group')){ // view group only
			$from = 'membership_userrecords';
			$where = "(`$table`.`$pk`=membership_userrecords.pkValue and membership_userrecords.tableName='$table' and membership_userrecords.groupID='".getLoggedGroupID()."')";
		}elseif($perm[2] == 3){ // view all
			// no further action
		}elseif($perm[2] == 0){ // view none
			return false;
		}

		return array('where' => $where, 'from' => $from, 0 => $where, 1 => $from);
	}

	#########################################################

	function error_message($msg, $back_url = '', $full_page = true){
		$curr_dir = dirname(__FILE__);
		global $Translation;

		ob_start();

		if($full_page) include_once($curr_dir . '/header.php');

		echo '<div class="panel panel-danger">';
			echo '<div class="panel-heading"><h3 class="panel-title">' . $Translation['error:'] . '</h3></div>';
			echo '<div class="panel-body"><p class="text-danger">' . $msg . '</p>';
			if($back_url !== false){ // explicitly passing false suppresses the back link completely
				echo '<div class="text-center">';
				if($back_url){
					echo '<a href="' . $back_url . '" class="btn btn-danger btn-lg vspacer-lg"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['< back'] . '</a>';
				}else{
					echo '<a href="#" class="btn btn-danger btn-lg vspacer-lg" onclick="history.go(-1); return false;"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['< back'] . '</a>';
				}
				echo '</div>';
			}
			echo '</div>';
		echo '</div>';

		if($full_page) include_once($curr_dir . '/footer.php');

		$out = ob_get_contents();
		ob_end_clean();

		return $out;
	}

	#########################################################

	function toMySQLDate($formattedDate, $sep = datalist_date_separator, $ord = datalist_date_format){
		// extract date elements
		$de=explode($sep, $formattedDate);
		$mySQLDate=intval($de[strpos($ord, 'Y')]).'-'.intval($de[strpos($ord, 'm')]).'-'.intval($de[strpos($ord, 'd')]);
		return $mySQLDate;
	}

	#########################################################

	function reIndex(&$arr){
		$i=1;
		foreach($arr as $n=>$v){
			$arr2[$i]=$n;
			$i++;
		}
		return $arr2;
	}

	#########################################################

	function get_embed($provider, $url, $max_width = '', $max_height = '', $retrieve = 'html'){
		global $Translation;
		if(!$url) return '';

		$providers = array(
			'youtube' => array('oembed' => 'http://www.youtube.com/oembed?'),
			'googlemap' => array('oembed' => '', 'regex' => '/^http.*\.google\..*maps/i')
		);

		if(!isset($providers[$provider])){
			return '<div class="text-danger">' . $Translation['invalid provider'] . '</div>';
		}

		if(isset($providers[$provider]['regex']) && !preg_match($providers[$provider]['regex'], $url)){
			return '<div class="text-danger">' . $Translation['invalid url'] . '</div>';
		}

		if($providers[$provider]['oembed']){
			$oembed = $providers[$provider]['oembed'] . 'url=' . urlencode($url) . "&maxwidth={$max_width}&maxheight={$max_height}&format=json";
			$data_json = request_cache($oembed);

			$data = json_decode($data_json, true);
			if($data === null){
				/* an error was returned rather than a json string */
				if($retrieve == 'html') return "<div class=\"text-danger\">{$data_json}\n<!-- {$oembed} --></div>";
				return '';
			}

			return (isset($data[$retrieve]) ? $data[$retrieve] : $data['html']);
		}

		/* special cases (where there is no oEmbed provider) */
		if($provider == 'googlemap') return get_embed_googlemap($url, $max_width, $max_height, $retrieve);

		return '<div class="text-danger">Invalid provider!</div>';
	}

	#########################################################

	function get_embed_googlemap($url, $max_width = '', $max_height = '', $retrieve = 'html'){
		global $Translation;
		$url_parts = parse_url($url);
		$coords_regex = '/-?\d+(\.\d+)?[,+]-?\d+(\.\d+)?(,\d{1,2}z)?/'; /* https://stackoverflow.com/questions/2660201 */

		if(preg_match($coords_regex, $url_parts['path'] . '?' . $url_parts['query'], $m)){
			list($lat, $long, $zoom) = explode(',', $m[0]);
			$zoom = intval($zoom);
			if(!$zoom) $zoom = 10; /* default zoom */
			if(!$max_height) $max_height = 360;
			if(!$max_width) $max_width = 480;

			$api_key = 'AIzaSyDCmPAecTO_tNH1X2M2H8c49s_DgycM-8M';
			$embed_url = "https://www.google.com/maps/embed/v1/view?key={$api_key}&center={$lat},{$long}&zoom={$zoom}&maptype=roadmap";
			$thumbnail_url = "https://maps.googleapis.com/maps/api/staticmap?key={$api_key}&center={$lat},{$long}&zoom={$zoom}&maptype=roadmap&size={$max_width}x{$max_height}";

			if($retrieve == 'html'){
				return "<iframe width=\"{$max_width}\" height=\"{$max_height}\" frameborder=\"0\" style=\"border:0\" src=\"{$embed_url}\"></iframe>";
			}else{
				return $thumbnail_url;
			}
		}else{
			return '<div class="text-danger">' . $Translation['cant retrieve coordinates from url'] . '</div>';
		}
	}

	#########################################################

	function request_cache($request, $force_fetch = false){
		$max_cache_lifetime = 7 * 86400; /* max cache lifetime in seconds before refreshing from source */

		/* membership_cache table exists? if not, create it */
		static $cache_table_exists = false;
		if(!$cache_table_exists && !$force_fetch){
			$te = sqlValue("show tables like 'membership_cache'");
			if(!$te){
				if(!sql("CREATE TABLE `membership_cache` (`request` VARCHAR(100) NOT NULL, `request_ts` INT, `response` TEXT NOT NULL, PRIMARY KEY (`request`))", $eo)){
					/* table can't be created, so force fetching request */
					return request_cache($request, true);
				}
			}
			$cache_table_exists = true;
		}

		/* retrieve response from cache if exists */
		if(!$force_fetch){
			$res = sql("select response, request_ts from membership_cache where request='" . md5($request) . "'", $eo);
			if(!$row = db_fetch_array($res)) return request_cache($request, true);

			$response = $row[0];
			$response_ts = $row[1];
			if($response_ts < time() - $max_cache_lifetime) return request_cache($request, true);
		}

		/* if no response in cache, issue a request */
		if(!$response || $force_fetch){
			$response = @file_get_contents($request);
			if($response === false){
				$error = error_get_last();
				$error_message = preg_replace('/.*: (.*)/', '$1', $error['message']);
				return $error_message;
			}elseif($cache_table_exists){
				/* store response in cache */
				$ts = time();
				sql("replace into membership_cache set request='" . md5($request) . "', request_ts='{$ts}', response='" . makeSafe($response, false) . "'", $eo);
			}
		}

		return $response;
	}

	#########################################################

	function check_record_permission($table, $id, $perm = 'view'){
		if($perm != 'edit' && $perm != 'delete') $perm = 'view';

		$perms = getTablePermissions($table);
		if(!$perms[$perm]) return false;

		$safe_id = makeSafe($id);
		$safe_table = makeSafe($table);

		if($perms[$perm] == 1){ // own records only
			$username = getLoggedMemberID();
			$owner = sqlValue("select memberID from membership_userrecords where tableName='{$safe_table}' and pkValue='{$safe_id}'");
			if($owner == $username) return true;
		}elseif($perms[$perm] == 2){ // group records
			$group_id = getLoggedGroupID();
			$owner_group_id = sqlValue("select groupID from membership_userrecords where tableName='{$safe_table}' and pkValue='{$safe_id}'");
			if($owner_group_id == $group_id) return true;
		}elseif($perms[$perm] == 3){ // all records
			return true;
		}

		return false;
	}

	#########################################################

	function NavMenus($options = array()){
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');
		global $Translation;
		$prepend_path = PREPEND_PATH;

		/* default options */
		if(empty($options)){
			$options = array(
				'tabs' => 7
			);
		}

		$table_group_name = array_keys(get_table_groups()); /* 0 => group1, 1 => group2 .. */
		/* if only one group named 'None', set to translation of 'select a table' */
		if((count($table_group_name) == 1 && $table_group_name[0] == 'None') || count($table_group_name) < 1) $table_group_name[0] = $Translation['select a table'];
		$table_group_index = array_flip($table_group_name); /* group1 => 0, group2 => 1 .. */
		$menu = array_fill(0, count($table_group_name), '');

		$t = time();
		$arrTables = getTableList();
		if(is_array($arrTables)){
			foreach($arrTables as $tn => $tc){
				/* ---- list of tables where hide link in nav menu is set ---- */
				$tChkHL = array_search($tn, array());

				/* ---- list of tables where filter first is set ---- */
				$tChkFF = array_search($tn, array());
				if($tChkFF !== false && $tChkFF !== null){
					$searchFirst = '&Filter_x=1';
				}else{
					$searchFirst = '';
				}

				/* when no groups defined, $table_group_index['None'] is NULL, so $menu_index is still set to 0 */
				$menu_index = intval($table_group_index[$tc[3]]);
				if(!$tChkHL && $tChkHL !== 0) $menu[$menu_index] .= "<li><a href=\"{$prepend_path}{$tn}_view.php?t={$t}{$searchFirst}\"><img src=\"{$prepend_path}" . ($tc[2] ? $tc[2] : 'blank.gif') . "\" height=\"32\"> {$tc[0]}</a></li>";
			}
		}

		// custom nav links, as defined in "hooks/links-navmenu.php" 
		global $navLinks;
		if(is_array($navLinks)){
			$memberInfo = getMemberInfo();
			$links_added = array();
			foreach($navLinks as $link){
				if(!isset($link['url']) || !isset($link['title'])) continue;
				if($memberInfo['admin'] || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])){
					$menu_index = intval($link['table_group']);
					if(!$links_added[$menu_index]) $menu[$menu_index] .= '<li class="divider"></li>';

					/* add prepend_path to custom links if they aren't absolute links */
					if(!preg_match('/^(http|\/\/)/i', $link['url'])) $link['url'] = $prepend_path . $link['url'];
					if(!preg_match('/^(http|\/\/)/i', $link['icon']) && $link['icon']) $link['icon'] = $prepend_path . $link['icon'];

					$menu[$menu_index] .= "<li><a href=\"{$link['url']}\"><img src=\"" . ($link['icon'] ? $link['icon'] : "{$prepend_path}blank.gif") . "\" height=\"32\"> {$link['title']}</a></li>";
					$links_added[$menu_index]++;
				}
			}
		}

		$menu_wrapper = '';
		for($i = 0; $i < count($menu); $i++){
			$menu_wrapper .= <<<EOT
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">{$table_group_name[$i]} <b class="caret"></b></a>
					<ul class="dropdown-menu" role="menu">{$menu[$i]}</ul>
				</li>
EOT;
		}

		return $menu_wrapper;
	}

	#########################################################

	function StyleSheet(){
		if(!defined('PREPEND_PATH')) define('PREPEND_PATH', '');
		$prepend_path = PREPEND_PATH;

		$css_links = <<<EOT

			<link rel="stylesheet" href="{$prepend_path}resources/initializr/css/bootstrap_hrc.css">
			<!--[if gt IE 8]><!-->
				<link rel="stylesheet" href="{$prepend_path}resources/initializr/css/bootstrap-theme.css">
			<!--<![endif]-->';
			<link rel="stylesheet" href="{$prepend_path}resources/lightbox/css/lightbox.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}resources/select2/select2.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}resources/timepicker/bootstrap-timepicker.min.css" media="screen">
			<link rel="stylesheet" href="{$prepend_path}dynamic.css.php">
EOT;

		return $css_links;
	}

	#########################################################

	function getUploadDir($dir){
		global $Translation;

		if($dir==""){
			$dir=$Translation['ImageFolder'];
		}

		if(substr($dir, -1)!="/"){
			$dir.="/";
		}

		return $dir;
	}

	#########################################################

	function PrepareUploadedFile($FieldName, $MaxSize, $FileTypes='jpg|jpeg|gif|png', $NoRename=false, $dir=""){
		global $Translation;
		$f = $_FILES[$FieldName];

		$dir = getUploadDir($dir);

		/* get php.ini upload_max_filesize in bytes */
		$php_upload_size_limit = trim(ini_get('upload_max_filesize'));
		$last = strtolower($php_upload_size_limit[strlen($php_upload_size_limit)-1]);
		switch($last){
			case 'g':
				$php_upload_size_limit *= 1024;
			case 'm':
				$php_upload_size_limit *= 1024;
			case 'k':
				$php_upload_size_limit *= 1024;
		}

		$MaxSize = min($MaxSize, $php_upload_size_limit);

		if($f['error'] != 4 && $f['name']!=''){
			if($f['size']>$MaxSize || $f['error']){
				echo error_message(str_replace('<MaxSize>', intval($MaxSize / 1024), $Translation['file too large']));
				exit;
			}
			if(!preg_match('/\.('.$FileTypes.')$/i', $f['name'], $ft)){
				echo error_message(str_replace('<FileTypes>', str_replace('|', ', ', $FileTypes), $Translation['invalid file type']));
				exit;
			}

			if($NoRename){
				$n  = str_replace(' ', '_', $f['name']);
			}else{
				$n  = microtime();
				$n  = str_replace(' ', '_', $n);
				$n  = str_replace('0.', '', $n);
				$n .= $ft[0];
			}

			if(!file_exists($dir)){
				@mkdir($dir, 0777);
			}

			if(!@move_uploaded_file($f['tmp_name'], $dir . $n)){
				echo error_message("Couldn't save the uploaded file. Try chmoding the upload folder '{$dir}' to 777.");
				exit;
			}else{
				@chmod($dir.$n, 0666);
				return $n;
			}
		}
		return "";
	}

	#########################################################

	function get_home_links($homeLinks, $default_classes, $tgroup = ''){
		if(!is_array($homeLinks) || !count($homeLinks)) return '';

		$memberInfo = getMemberInfo();

		ob_start();
		foreach($homeLinks as $link){
			if(!isset($link['url']) || !isset($link['title'])) continue;
			if($tgroup != $link['table_group'] && $tgroup != '*') continue;

			/* fall-back classes if none defined */
			if(!$link['grid_column_classes']) $link['grid_column_classes'] = $default_classes['grid_column'];
			if(!$link['panel_classes']) $link['panel_classes'] = $default_classes['panel'];
			if(!$link['link_classes']) $link['link_classes'] = $default_classes['link'];

			if($memberInfo['admin'] || @in_array($memberInfo['group'], $link['groups']) || @in_array('*', $link['groups'])){
				?>
				<div class="col-xs-12 <?php echo $link['grid_column_classes']; ?>">
					<div class="panel <?php echo $link['panel_classes']; ?>">
						<div class="panel-body">
							<a class="btn btn-block btn-lg <?php echo $link['link_classes']; ?>" title="<?php echo preg_replace("/&amp;(#[0-9]+|[a-z]+);/i", "&$1;", html_attr(strip_tags($link['description']))); ?>" href="<?php echo $link['url']; ?>"><?php echo ($link['icon'] ? '<img src="' . $link['icon'] . '">' : ''); ?><strong><?php echo $link['title']; ?></strong></a>
							<div class="panel-body-description"><?php echo $link['description']; ?></div>
						</div>
					</div>
				</div>
				<?php
			}
		}

		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	#########################################################
