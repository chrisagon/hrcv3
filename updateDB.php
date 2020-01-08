<?php
	// check this file's MD5 to make sure it wasn't called before
	$prevMD5=@implode('', @file(dirname(__FILE__).'/setup.md5'));
	$thisMD5=md5(@implode('', @file("./updateDB.php")));
	if($thisMD5==$prevMD5) {
		$setupAlreadyRun=true;
	}else{
		// set up tables
		if(!isset($silent)) {
			$silent=true;
		}

		// set up tables
		setupTable('consultant', "create table if not exists `consultant` (   `id_consultant` INT unsigned not null auto_increment , primary key (`id_consultant`), `Matricule` VARCHAR(40) not null , `Prenom` VARCHAR(40) null , `Nom` VARCHAR(40) not null , `nom_complet` CHAR(40) null , `email` VARCHAR(80) null , `adresse_postale` TEXT null , `saisie_par` VARCHAR(40) null , `coache_par` INT unsigned null , `emploi_fonctionnel` INT unsigned null , `cv_hrc` VARCHAR(40) null ) CHARSET utf8", $silent);
		setupIndexes('consultant', array('coache_par','emploi_fonctionnel'));
		setupTable('missions', "create table if not exists `missions` (   `id_mission` INT unsigned not null auto_increment , primary key (`id_mission`), `id_consultant` INT unsigned null , `rattache_a_filiere` INT unsigned null , `site_mission` VARCHAR(64) null , `periode` VARCHAR(40) null , `date_debut` DATE null , `date_fin` DATE null , `description_mission` TEXT not null , `description_detaille` TEXT null , `client` INT unsigned null , `environnement` VARCHAR(254) null , `competences_utilisees` INT unsigned null ) CHARSET utf8", $silent);
		setupIndexes('missions', array('id_consultant','rattache_a_filiere','client','competences_utilisees'));
		setupTable('competences_individuelles', "create table if not exists `competences_individuelles` (   `id_comp_indiv` INT unsigned not null auto_increment , primary key (`id_comp_indiv`), `rattache_a_mission` INT unsigned null , `competence_mis_en_oeuvre` INT unsigned not null , `niveau` INT unsigned not null , `consultant_id` INT unsigned not null , `Documents_capitalises` VARCHAR(40) null , `commentaires` TEXT null ) CHARSET utf8", $silent);
		setupIndexes('competences_individuelles', array('rattache_a_mission','competence_mis_en_oeuvre','niveau','consultant_id'));
		setupTable('client', "create table if not exists `client` (   `id_client` INT unsigned not null auto_increment , primary key (`id_client`), `nom_du_client` VARCHAR(40) not null , `nom_du_contact` VARCHAR(40) null , `titre_du_contact` VARCHAR(40) null , `adresse_postale` TEXT null , `ville` VARCHAR(40) null , `code_postal` VARCHAR(5) null , `adresse_gm` TINYTEXT null , `pays` VARCHAR(40) null , `telephone_contact` VARCHAR(40) null , `email` VARCHAR(80) null , `commentaires` TEXT null ) CHARSET utf8", $silent);
		setupTable('competences_ref', "create table if not exists `competences_ref` (   `id_competence` INT unsigned not null auto_increment , primary key (`id_competence`), `titre_competence` TEXT null , `description` TEXT null , `domaine_principal` INT unsigned null , `domaine` TEXT null ) CHARSET utf8", $silent);
		setupIndexes('competences_ref', array('domaine_principal'));
		setupTable('domaine', "create table if not exists `domaine` (   `id_domaine` INT unsigned not null auto_increment , primary key (`id_domaine`), `titre_domaine` VARCHAR(40) null , `description` TEXT null , `rattache_a_filiere` INT unsigned null ) CHARSET utf8", $silent);
		setupIndexes('domaine', array('rattache_a_filiere'));
		setupTable('filiere', "create table if not exists `filiere` (   `id_filiere` INT unsigned not null auto_increment , primary key (`id_filiere`), `titre_filiere` VARCHAR(40) null , `description` TEXT null ) CHARSET utf8", $silent);
		setupTable('niveaux_ref', "create table if not exists `niveaux_ref` (   `id_niveau` INT unsigned not null auto_increment , primary key (`id_niveau`), `titre` VARCHAR(40) null , `niveau` VARCHAR(40) null , `description` TEXT null ) CHARSET utf8", $silent);
		setupTable('carriere_consultant', "create table if not exists `carriere_consultant` (   `id_carriere_conslt` INT unsigned not null auto_increment , primary key (`id_carriere_conslt`), `date_debut` DATE not null , `date_fin` DATE null , `titre_emploi` VARCHAR(40) null ) CHARSET utf8", $silent);
		setupTable('formation_suivi', "create table if not exists `formation_suivi` (   `id` INT unsigned not null auto_increment , primary key (`id`), `titre` VARCHAR(40) null , `niveau` VARCHAR(40) null , `date_deb` DATE null , `date_fin` DATE null , `competence_principale` INT unsigned null , `competence_secondaire` INT unsigned null , `evaluation` VARCHAR(40) null , `consultant_id` INT unsigned null , `organisme` VARCHAR(40) null , `commentaire` MEDIUMTEXT null ) CHARSET utf8", $silent);
		setupIndexes('formation_suivi', array('competence_secondaire','consultant_id'));
		setupTable('feedback', "create table if not exists `feedback` (   `id` INT unsigned not null auto_increment , primary key (`id`), `titre` VARCHAR(40) null , `fonctionnalite` TEXT null , `description` TEXT null , `plus` TEXT null , `moins` TEXT null ) CHARSET utf8", $silent);
		setupTable('emploi_fonctionnel', "create table if not exists `emploi_fonctionnel` (   `id_ef` INT unsigned not null auto_increment , primary key (`id_ef`), `titre_emploifct` VARCHAR(40) null , `grade` VARCHAR(40) null , `echelon` VARCHAR(40) null , `description` TEXT null ) CHARSET utf8", $silent);


		// save MD5
		if($fp=@fopen(dirname(__FILE__).'/setup.md5', 'w')) {
			fwrite($fp, $thisMD5);
			fclose($fp);
		}
	}


	function setupIndexes($tableName, $arrFields) {
		if(!is_array($arrFields)) {
			return false;
		}

		foreach($arrFields as $fieldName) {
			if(!$res=@db_query("SHOW COLUMNS FROM `$tableName` like '$fieldName'")) {
				continue;
			}
			if(!$row=@db_fetch_assoc($res)) {
				continue;
			}
			if($row['Key']=='') {
				@db_query("ALTER TABLE `$tableName` ADD INDEX `$fieldName` (`$fieldName`)");
			}
		}
	}


	function setupTable($tableName, $createSQL='', $silent=true, $arrAlter='') {
		global $Translation;
		ob_start();

		echo '<div style="padding: 5px; border-bottom:solid 1px silver; font-family: verdana, arial; font-size: 10px;">';

		// is there a table rename query?
		if(is_array($arrAlter)) {
			$matches=array();
			if(preg_match("/ALTER TABLE `(.*)` RENAME `$tableName`/", $arrAlter[0], $matches)) {
				$oldTableName=$matches[1];
			}
		}

		if($res=@db_query("select count(1) from `$tableName`")) { // table already exists
			if($row = @db_fetch_array($res)) {
				echo str_replace("<TableName>", $tableName, str_replace("<NumRecords>", $row[0],$Translation["table exists"]));
				if(is_array($arrAlter)) {
					echo '<br>';
					foreach($arrAlter as $alter) {
						if($alter!='') {
							echo "$alter ... ";
							if(!@db_query($alter)) {
								echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
								echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
							}else{
								echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
							}
						}
					}
				}else{
					echo $Translation["table uptodate"];
				}
			}else{
				echo str_replace("<TableName>", $tableName, $Translation["couldnt count"]);
			}
		}else{ // given tableName doesn't exist

			if($oldTableName!='') { // if we have a table rename query
				if($ro=@db_query("select count(1) from `$oldTableName`")) { // if old table exists, rename it.
					$renameQuery=array_shift($arrAlter); // get and remove rename query

					echo "$renameQuery ... ";
					if(!@db_query($renameQuery)) {
						echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
						echo '<div class="text-danger">' . $Translation['mysql said'] . ' ' . db_error(db_link()) . '</div>';
					}else{
						echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
					}

					if(is_array($arrAlter)) setupTable($tableName, $createSQL, false, $arrAlter); // execute Alter queries on renamed table ...
				}else{ // if old tableName doesn't exist (nor the new one since we're here), then just create the table.
					setupTable($tableName, $createSQL, false); // no Alter queries passed ...
				}
			}else{ // tableName doesn't exist and no rename, so just create the table
				echo str_replace("<TableName>", $tableName, $Translation["creating table"]);
				if(!@db_query($createSQL)) {
					echo '<span class="label label-danger">' . $Translation['failed'] . '</span>';
					echo '<div class="text-danger">' . $Translation['mysql said'] . db_error(db_link()) . '</div>';
				}else{
					echo '<span class="label label-success">' . $Translation['ok'] . '</span>';
				}
			}
		}

		echo "</div>";

		$out=ob_get_contents();
		ob_end_clean();
		if(!$silent) {
			echo $out;
		}
	}
?>