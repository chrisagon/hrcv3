<?php
	$currDir = dirname(__FILE__);
	require("{$currDir}/incCommon.php");
	$GLOBALS['page_title'] = $Translation['view or rebuild fields'];
	include("{$currDir}/incHeader.php");

	/* application schema as created in AppGini */
	$schema = array(   
		'curriculum_vitae' => array(   
			'id_cv' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'du_consultant' => array('appgini' => 'INT unsigned '),
			'Titre_du_cv' => array('appgini' => 'TEXT not null '),
			'creer_par' => array('appgini' => 'VARCHAR(40) ')
		),
		'consultant' => array(   
			'id_consultant' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'Matricule' => array('appgini' => 'VARCHAR(40) not null '),
			'Prenom' => array('appgini' => 'VARCHAR(40) '),
			'Nom' => array('appgini' => 'VARCHAR(40) not null '),
			'email' => array('appgini' => 'VARCHAR(80) '),
			'adresse_postale' => array('appgini' => 'TEXT '),
			'saisie_par' => array('appgini' => 'VARCHAR(40) '),
			'coache_par' => array('appgini' => 'INT unsigned '),
			'emploi_fonctionnel' => array('appgini' => 'INT unsigned ')
		),
		'missions' => array(   
			'id_mission' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'id_consultant' => array('appgini' => 'INT unsigned '),
			'rattache_a_cv' => array('appgini' => 'INT unsigned '),
			'date_debut' => array('appgini' => 'DATE not null '),
			'date_fin' => array('appgini' => 'DATE '),
			'description_mission' => array('appgini' => 'TEXT not null '),
			'description_detaille' => array('appgini' => 'TEXT '),
			'client' => array('appgini' => 'INT unsigned not null ')
		),
		'competences_individuelles' => array(   
			'id_comp_indiv' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'rattache_a_mission' => array('appgini' => 'INT unsigned '),
			'competence_mis_en_oeuvre' => array('appgini' => 'INT unsigned not null '),
			'niveau' => array('appgini' => 'INT unsigned not null '),
			'consultant_id' => array('appgini' => 'INT unsigned not null '),
			'Documents_capitalises' => array('appgini' => 'VARCHAR(40) '),
			'commentaires' => array('appgini' => 'TEXT ')
		),
		'client' => array(   
			'id_client' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'nom_du_client' => array('appgini' => 'VARCHAR(40) not null '),
			'nom_du_contact' => array('appgini' => 'VARCHAR(40) '),
			'titre_du_contact' => array('appgini' => 'VARCHAR(40) '),
			'adresse_postale' => array('appgini' => 'TEXT '),
			'ville' => array('appgini' => 'VARCHAR(40) '),
			'code_postal' => array('appgini' => 'VARCHAR(5) '),
			'adresse_gm' => array('appgini' => 'TINYTEXT '),
			'pays' => array('appgini' => 'VARCHAR(40) '),
			'telephone_contact' => array('appgini' => 'VARCHAR(40) '),
			'email' => array('appgini' => 'VARCHAR(80) '),
			'commentaires' => array('appgini' => 'TEXT ')
		),
		'competences_ref' => array(   
			'id_competence' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre_competence' => array('appgini' => 'TEXT '),
			'description' => array('appgini' => 'TEXT '),
			'domaine_principal' => array('appgini' => 'INT unsigned '),
			'domaine' => array('appgini' => 'TEXT ')
		),
		'domaine' => array(   
			'id_domaine' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre_domaine' => array('appgini' => 'VARCHAR(40) '),
			'description' => array('appgini' => 'TEXT '),
			'rattache_a_filiere' => array('appgini' => 'INT unsigned ')
		),
		'filiere' => array(   
			'id_filiere' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre_filiere' => array('appgini' => 'VARCHAR(40) '),
			'description' => array('appgini' => 'TEXT ')
		),
		'niveaux_ref' => array(   
			'id_niveau' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre' => array('appgini' => 'VARCHAR(40) '),
			'niveau' => array('appgini' => 'VARCHAR(40) '),
			'description' => array('appgini' => 'TEXT ')
		),
		'carriere_consultant' => array(   
			'id_carriere_conslt' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'date_debut' => array('appgini' => 'DATE not null '),
			'date_fin' => array('appgini' => 'DATE '),
			'titre_emploi' => array('appgini' => 'VARCHAR(40) ')
		),
		'formation_suivi' => array(   
			'id' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre' => array('appgini' => 'VARCHAR(40) '),
			'niveau' => array('appgini' => 'VARCHAR(40) '),
			'date_deb' => array('appgini' => 'DATE '),
			'date_fin' => array('appgini' => 'DATE '),
			'competence_principale' => array('appgini' => 'INT unsigned '),
			'competence_secondaire' => array('appgini' => 'INT unsigned '),
			'evaluation' => array('appgini' => 'VARCHAR(40) '),
			'consultant_id' => array('appgini' => 'INT unsigned '),
			'organisme' => array('appgini' => 'VARCHAR(40) '),
			'commentaire' => array('appgini' => 'MEDIUMTEXT ')
		),
		'feedback' => array(   
			'id' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre' => array('appgini' => 'VARCHAR(40) '),
			'fonctionnalite' => array('appgini' => 'TEXT '),
			'description' => array('appgini' => 'TEXT '),
			'plus' => array('appgini' => 'TEXT '),
			'moins' => array('appgini' => 'TEXT ')
		),
		'emploi_fonctionnel' => array(   
			'id_ef' => array('appgini' => 'INT unsigned not null primary key auto_increment '),
			'titre_emploifct' => array('appgini' => 'VARCHAR(40) '),
			'grade' => array('appgini' => 'VARCHAR(40) '),
			'echelon' => array('appgini' => 'VARCHAR(40) '),
			'description' => array('appgini' => 'TEXT ')
		)
	);

	$table_captions = getTableList();

	/* function for preparing field definition for comparison */
	function prepare_def($def){
		$def = trim($def);
		$def = strtolower($def);

		/* ignore length for int data types */
		$def = preg_replace('/int\w*\([0-9]+\)/', 'int', $def);

		/* make sure there is always a space before mysql words */
		$def = preg_replace('/(\S)(unsigned|not null|binary|zerofill|auto_increment|default)/', '$1 $2', $def);

		/* treat 0.000.. same as 0 */
		$def = preg_replace('/([0-9])*\.0+/', '$1', $def);

		/* treat unsigned zerofill same as zerofill */
		$def = str_ireplace('unsigned zerofill', 'zerofill', $def);

		/* ignore zero-padding for date data types */
		$def = preg_replace("/date\s*default\s*'([0-9]{4})-0?([1-9])-0?([1-9])'/i", "date default '$1-$2-$3'", $def);

		return $def;
	}

	/* process requested fixes */
	$fix_table = (isset($_GET['t']) ? $_GET['t'] : false);
	$fix_field = (isset($_GET['f']) ? $_GET['f'] : false);

	if($fix_table && $fix_field && isset($schema[$fix_table][$fix_field])){
		$field_added = $field_updated = false;

		// field exists?
		$res = sql("show columns from `{$fix_table}` like '{$fix_field}'", $eo);
		if($row = db_fetch_assoc($res)){
			// modify field
			$qry = "alter table `{$fix_table}` modify `{$fix_field}` {$schema[$fix_table][$fix_field]['appgini']}";
			sql($qry, $eo);
			$field_updated = true;
		}else{
			// create field
			$qry = "alter table `{$fix_table}` add column `{$fix_field}` {$schema[$fix_table][$fix_field]['appgini']}";
			sql($qry, $eo);
			$field_added = true;
		}
	}

	foreach($table_captions as $tn => $tc){
		$eo['silentErrors'] = true;
		$res = sql("show columns from `{$tn}`", $eo);
		if($res){
			while($row = db_fetch_assoc($res)){
				if(!isset($schema[$tn][$row['Field']]['appgini'])) continue;
				$field_description = strtoupper(str_replace(' ', '', $row['Type']));
				$field_description = str_ireplace('unsigned', ' unsigned', $field_description);
				$field_description = str_ireplace('zerofill', ' zerofill', $field_description);
				$field_description = str_ireplace('binary', ' binary', $field_description);
				$field_description .= ($row['Null'] == 'NO' ? ' not null' : '');
				$field_description .= ($row['Key'] == 'PRI' ? ' primary key' : '');
				$field_description .= ($row['Key'] == 'UNI' ? ' unique' : '');
				$field_description .= ($row['Default'] != '' ? " default '" . makeSafe($row['Default']) . "'" : '');
				$field_description .= ($row['Extra'] == 'auto_increment' ? ' auto_increment' : '');

				$schema[$tn][$row['Field']]['db'] = '';
				if(isset($schema[$tn][$row['Field']])){
					$schema[$tn][$row['Field']]['db'] = $field_description;
				}
			}
		}
	}
?>

<?php if($field_added || $field_updated){ ?>
	<div class="alert alert-info alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i class="glyphicon glyphicon-info-sign"></i>
		<?php 
			$originalValues =  array ('<ACTION>','<FIELD>' , '<TABLE>' , '<QUERY>' );
			$action = ($field_added ? 'create' : 'update');
			$replaceValues = array ( $action , $fix_field , $fix_table , $qry );
			echo  str_replace ( $originalValues , $replaceValues , $Translation['create or update table']  );
		?>
	</div>
<?php } ?>

<div class="page-header"><h1>
	<?php echo $Translation['view or rebuild fields'] ; ?>
	<button type="button" class="btn btn-default" id="show_deviations_only"><i class="glyphicon glyphicon-eye-close"></i> <?php echo $Translation['show deviations only'] ; ?></button>
	<button type="button" class="btn btn-default hidden" id="show_all_fields"><i class="glyphicon glyphicon-eye-open"></i> <?php echo $Translation['show all fields'] ; ?></button>
</h1></div>

<p class="lead"><?php echo $Translation['compare tables page'] ; ?></p>

<div class="alert summary"></div>
<table class="table table-responsive table-hover table-striped">
	<thead><tr>
		<th></th>
		<th><?php echo $Translation['field'] ; ?></th>
		<th><?php echo $Translation['AppGini definition'] ; ?></th>
		<th><?php echo $Translation['database definition'] ; ?></th>
		<th></th>
	</tr></thead>

	<tbody>
	<?php foreach($schema as $tn => $fields){ ?>
		<tr class="text-info"><td colspan="5"><h4 data-placement="left" data-toggle="tooltip" title="<?php echo str_replace ( "<TABLENAME>" , $tn , $Translation['table name title']) ; ?>"><i class="glyphicon glyphicon-th-list"></i> <?php echo $table_captions[$tn]; ?></h4></td></tr>
		<?php foreach($fields as $fn => $fd){ ?>
			<?php $diff = ((prepare_def($fd['appgini']) == prepare_def($fd['db'])) ? false : true); ?>
			<?php $no_db = ($fd['db'] ? false : true); ?>
			<tr class="<?php echo ($diff ? 'warning' : 'field_ok'); ?>">
				<td><i class="glyphicon glyphicon-<?php echo ($diff ? 'remove text-danger' : 'ok text-success'); ?>"></i></td>
				<td><?php echo $fn; ?></td>
				<td class="<?php echo ($diff ? 'bold text-success' : ''); ?>"><?php echo $fd['appgini']; ?></td>
				<td class="<?php echo ($diff ? 'bold text-danger' : ''); ?>"><?php echo thisOr($fd['db'], $Translation['does not exist']); ?></td>
				<td>
					<?php if($diff && $no_db){ ?>
						<a href="pageRebuildFields.php?t=<?php echo $tn; ?>&f=<?php echo $fn; ?>" class="btn btn-success btn-xs btn_create" data-toggle="tooltip" data-placement="top" title="<?php echo $Translation['create field'] ; ?>"><i class="glyphicon glyphicon-plus"></i> <?php echo $Translation['create it'] ; ?></a>
					<?php }elseif($diff){ ?>
						<a href="pageRebuildFields.php?t=<?php echo $tn; ?>&f=<?php echo $fn; ?>" class="btn btn-warning btn-xs btn_update" data-toggle="tooltip" title="<?php echo $Translation['fix field'] ; ?>"><i class="glyphicon glyphicon-cog"></i> <?php echo $Translation['fix it'] ; ?></a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
	<?php } ?>
	</tbody>
</table>
<div class="alert summary"></div>

<style>
	.bold{ font-weight: bold; }
	[data-toggle="tooltip"]{ display: block !important; }
</style>

<script>
	jQuery(function(){
		jQuery('[data-toggle="tooltip"]').tooltip();

		jQuery('#show_deviations_only').click(function(){
			jQuery(this).addClass('hidden');
			jQuery('#show_all_fields').removeClass('hidden');
			jQuery('.field_ok').hide();
		});

		jQuery('#show_all_fields').click(function(){
			jQuery(this).addClass('hidden');
			jQuery('#show_deviations_only').removeClass('hidden');
			jQuery('.field_ok').show();
		});

		jQuery('.btn_update').click(function(){
			return confirm("<?php echo $Translation['field update warning'] ; ?>");
		});

		var count_updates = jQuery('.btn_update').length;
		var count_creates = jQuery('.btn_create').length;
		if(!count_creates && !count_updates){
			jQuery('.summary').addClass('alert-success').html("<?php echo $Translation['no deviations found'] ; ?>");
		}else{
			var fieldsCount = "<?php echo $Translation['error fields']; ?>";
			fieldsCount = fieldsCount.replace(/<CREATENUM>/, count_creates ).replace(/<UPDATENUM>/, count_updates);


			jQuery('.summary')
				.addClass('alert-warning')
				.html(
					fieldsCount
				);
		}
	});
</script>

<?php
	include("{$currDir}/incFooter.php");
?>
