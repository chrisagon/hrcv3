<?php

// Data functions (insert, update, delete, form) for table competences_ref

// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

function competences_ref_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('competences_ref');
	if(!$arrPerm[1]){
		return false;
	}

	$data['titre_competence'] = br2nl(makeSafe($_REQUEST['titre_competence']));
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }
	$data['domaine_principal'] = makeSafe($_REQUEST['domaine_principal']);
		if($data['domaine_principal'] == empty_lookup_value){ $data['domaine_principal'] = ''; }
	if(is_array($_REQUEST['domaine'])){
		$MultipleSeparator=', ';
		foreach($_REQUEST['domaine'] as $k => $v)
			$data['domaine'] .= makeSafe($v) . $MultipleSeparator;
		$data['domaine'] = substr($data['domaine'], 0, -1 * strlen($MultipleSeparator));
	}else{
		$data['domaine']='';
	}

	// hook: competences_ref_before_insert
	if(function_exists('competences_ref_before_insert')){
		$args=array();
		if(!competences_ref_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `competences_ref` set       `titre_competence`=' . (($data['titre_competence'] !== '' && $data['titre_competence'] !== NULL) ? "'{$data['titre_competence']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', `domaine_principal`=' . (($data['domaine_principal'] !== '' && $data['domaine_principal'] !== NULL) ? "'{$data['domaine_principal']}'" : 'NULL') . ', `domaine`=' . (($data['domaine'] !== '' && $data['domaine'] !== NULL) ? "'{$data['domaine']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"competences_ref_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: competences_ref_after_insert
	if(function_exists('competences_ref_after_insert')){
		$res = sql("select * from `competences_ref` where `id_competence`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!competences_ref_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('competences_ref', $recID, getLoggedMemberID());

	return $recID;
}

function competences_ref_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('competences_ref');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='competences_ref' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='competences_ref' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: competences_ref_before_delete
	if(function_exists('competences_ref_before_delete')){
		$args=array();
		if(!competences_ref_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: competences_individuelles
	$res = sql("select `id_competence` from `competences_ref` where `id_competence`='$selected_id'", $eo);
	$id_competence = db_fetch_row($res);
	$rires = sql("select count(1) from `competences_individuelles` where `competence_mis_en_oeuvre`='".addslashes($id_competence[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "competences_individuelles", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "competences_individuelles", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='competences_ref_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='competences_ref_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	// child table: formation_suivi
	$res = sql("select `id_competence` from `competences_ref` where `id_competence`='$selected_id'", $eo);
	$id_competence = db_fetch_row($res);
	$rires = sql("select count(1) from `formation_suivi` where `competence_secondaire`='".addslashes($id_competence[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "formation_suivi", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "formation_suivi", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='competences_ref_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='competences_ref_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `competences_ref` where `id_competence`='$selected_id'", $eo);

	// hook: competences_ref_after_delete
	if(function_exists('competences_ref_after_delete')){
		$args=array();
		competences_ref_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='competences_ref' and pkValue='$selected_id'", $eo);
}

function competences_ref_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('competences_ref');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='competences_ref' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='competences_ref' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['titre_competence'] = br2nl(makeSafe($_REQUEST['titre_competence']));
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }
	$data['domaine_principal'] = makeSafe($_REQUEST['domaine_principal']);
		if($data['domaine_principal'] == empty_lookup_value){ $data['domaine_principal'] = ''; }
	if(is_array($_REQUEST['domaine'])){
		$MultipleSeparator = ', ';
		foreach($_REQUEST['domaine'] as $k => $v)
			$data['domaine'] .= makeSafe($v) . $MultipleSeparator;
		$data['domaine']=substr($data['domaine'], 0, -1 * strlen($MultipleSeparator));
	}else{
		$data['domaine']='';
	}
	$data['selectedID']=makeSafe($selected_id);

	// hook: competences_ref_before_update
	if(function_exists('competences_ref_before_update')){
		$args=array();
		if(!competences_ref_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `competences_ref` set       `titre_competence`=' . (($data['titre_competence'] !== '' && $data['titre_competence'] !== NULL) ? "'{$data['titre_competence']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', `domaine_principal`=' . (($data['domaine_principal'] !== '' && $data['domaine_principal'] !== NULL) ? "'{$data['domaine_principal']}'" : 'NULL') . ', `domaine`=' . (($data['domaine'] !== '' && $data['domaine'] !== NULL) ? "'{$data['domaine']}'" : 'NULL') . " where `id_competence`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="competences_ref_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: competences_ref_after_update
	if(function_exists('competences_ref_after_update')){
		$res = sql("SELECT * FROM `competences_ref` WHERE `id_competence`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id_competence'];
		$args = array();
		if(!competences_ref_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='competences_ref' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function competences_ref_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('competences_ref');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_domaine_principal = thisOr(undo_magic_quotes($_REQUEST['filterer_domaine_principal']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: domaine_principal
	$combo_domaine_principal = new DataCombo;
	// combobox: domaine
	$combo_domaine = new Combo;
	$combo_domaine->ListType = 3;
	$combo_domaine->MultipleSeparator = ', ';
	$combo_domaine->ListBoxHeight = 10;
	$combo_domaine->RadiosPerLine = 1;
	if(is_file(dirname(__FILE__).'/hooks/competences_ref.domaine.csv')){
		$domaine_data = addslashes(implode('', @file(dirname(__FILE__).'/hooks/competences_ref.domaine.csv')));
		$combo_domaine->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions($domaine_data)));
		$combo_domaine->ListData = $combo_domaine->ListItem;
	}else{
		$combo_domaine->ListItem = explode('||', entitiesToUTF8(convertLegacyOptions("Applicatif;;Outils & langages;;Base de donn&#233;es;;OS;;AMOA;;Bascule et d&#233;ploiement;;Accompagnement au changement;;Recette;;Assistance aux sp&#233;cifications;;Reprise de donn&#233;es;;R&#233;daction de cahier des charges;;Construction de la phase amont;;Paie;;Processus RH;;PUBLIC;;")));
		$combo_domaine->ListData = $combo_domaine->ListItem;
	}
	$combo_domaine->SelectName = 'domaine';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='competences_ref' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='competences_ref' and pkValue='".makeSafe($selected_id)."'");
		if($arrPerm[2]==1 && getLoggedMemberID()!=$ownerMemberID){
			return "";
		}
		if($arrPerm[2]==2 && getLoggedGroupID()!=$ownerGroupID){
			return "";
		}

		// can edit?
		if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){
			$AllowUpdate=1;
		}else{
			$AllowUpdate=0;
		}

		$res = sql("select * from `competences_ref` where `id_competence`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'competences_ref_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_domaine_principal->SelectedData = $row['domaine_principal'];
		$combo_domaine->SelectedData = $row['domaine'];
	}else{
		$combo_domaine_principal->SelectedData = $filterer_domaine_principal;
	}
	$combo_domaine_principal->HTML = '<span id="domaine_principal-container' . $rnd1 . '"></span><input type="hidden" name="domaine_principal" id="domaine_principal' . $rnd1 . '" value="' . html_attr($combo_domaine_principal->SelectedData) . '">';
	$combo_domaine_principal->MatchText = '<span id="domaine_principal-container-readonly' . $rnd1 . '"></span><input type="hidden" name="domaine_principal" id="domaine_principal' . $rnd1 . '" value="' . html_attr($combo_domaine_principal->SelectedData) . '">';
	$combo_domaine->Render();

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_domaine_principal__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['domaine_principal'] : $filterer_domaine_principal); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(domaine_principal_reload__RAND__) == 'function') domaine_principal_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function domaine_principal_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#domaine_principal-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_domaine_principal__RAND__.value, t: 'competences_ref', f: 'domaine_principal' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="domaine_principal"]').val(resp.results[0].id);
							$j('[id=domaine_principal-container-readonly__RAND__]').html('<span id="domaine_principal-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=domaine_view_parent]').hide(); }else{ $j('.btn[id=domaine_view_parent]').show(); }


							if(typeof(domaine_principal_update_autofills__RAND__) == 'function') domaine_principal_update_autofills__RAND__();
						}
					});
				},
				width: '100%',
				formatNoMatches: function(term){ /* */ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 5,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ /* */ return { s: term, p: page, t: 'competences_ref', f: 'domaine_principal' }; },
					results: function(resp, page){ /* */ return resp; }
				},
				escapeMarkup: function(str){ /* */ return str; }
			}).on('change', function(e){
				AppGini.current_domaine_principal__RAND__.value = e.added.id;
				AppGini.current_domaine_principal__RAND__.text = e.added.text;
				$j('[name="domaine_principal"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=domaine_view_parent]').hide(); }else{ $j('.btn[id=domaine_view_parent]').show(); }


				if(typeof(domaine_principal_update_autofills__RAND__) == 'function') domaine_principal_update_autofills__RAND__();
			});

			if(!$j("#domaine_principal-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_domaine_principal__RAND__.value, t: 'competences_ref', f: 'domaine_principal' },
					success: function(resp){
						$j('[name="domaine_principal"]').val(resp.results[0].id);
						$j('[id=domaine_principal-container-readonly__RAND__]').html('<span id="domaine_principal-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=domaine_view_parent]').hide(); }else{ $j('.btn[id=domaine_view_parent]').show(); }

						if(typeof(domaine_principal_update_autofills__RAND__) == 'function') domaine_principal_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_domaine_principal__RAND__.value, t: 'competences_ref', f: 'domaine_principal' },
				success: function(resp){
					$j('[id=domaine_principal-container__RAND__], [id=domaine_principal-container-readonly__RAND__]').html('<span id="domaine_principal-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=domaine_view_parent]').hide(); }else{ $j('.btn[id=domaine_view_parent]').show(); }

					if(typeof(domaine_principal_update_autofills__RAND__) == 'function') domaine_principal_update_autofills__RAND__();
				}
			});
		<?php } ?>

		}
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/competences_ref_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/competences_ref_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Competence details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return competences_ref_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return competences_ref_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'AppGini.closeParentModal(); return false;';
	}else{
		$backAction = '$j(\'form\').eq(0).attr(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode = str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$j(\'form\').eq(0).prop(\'novalidate\', true); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return competences_ref_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode = str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#titre_competence').replaceWith('<div class=\"form-control-static\" id=\"titre_competence\">' + (jQuery('#titre_competence').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#domaine_principal').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#domaine_principal_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('#domaine').replaceWith('<div class=\"form-control-static\" id=\"domaine\">' + (jQuery('#domaine').val() || '') + '</div>'); jQuery('#domaine-multi-selection-help').hide();\n";
		$jsReadOnly .= "\tjQuery('#s2id_domaine').remove();\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(domaine_principal)%%>', $combo_domaine_principal->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(domaine_principal)%%>', $combo_domaine_principal->MatchText, $templateCode);
	$templateCode = str_replace('<%%URLCOMBOTEXT(domaine_principal)%%>', urlencode($combo_domaine_principal->MatchText), $templateCode);
	$templateCode = str_replace('<%%COMBO(domaine)%%>', $combo_domaine->HTML, $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(domaine)%%>', $combo_domaine->SelectedData, $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'domaine_principal' => array('domaine', 'Domaine principal'));
	foreach($lookup_fields as $luf => $ptfc){
		$pt_perm = getTablePermissions($ptfc[0]);

		// process foreign key links
		if($pt_perm['view'] || $pt_perm['edit']){
			$templateCode = str_replace("<%%PLINK({$luf})%%>", '<button type="button" class="btn btn-default view_parent hspacer-md" id="' . $ptfc[0] . '_view_parent" title="' . html_attr($Translation['View'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-eye-open"></i></button>', $templateCode);
		}

		// if user has insert permission to parent table of a lookup field, put an add new button
		if($pt_perm['insert'] && !$_REQUEST['Embedded']){
			$templateCode = str_replace("<%%ADDNEW({$ptfc[0]})%%>", '<button type="button" class="btn btn-success add_new_parent hspacer-md" id="' . $ptfc[0] . '_add_new" title="' . html_attr($Translation['Add New'] . ' ' . $ptfc[1]) . '"><i class="glyphicon glyphicon-plus-sign"></i></button>', $templateCode);
		}
	}

	// process images
	$templateCode = str_replace('<%%UPLOADFILE(id_competence)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(titre_competence)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(description)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(domaine_principal)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(domaine)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id_competence)%%>', safe_html($urow['id_competence']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id_competence)%%>', html_attr($row['id_competence']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_competence)%%>', urlencode($urow['id_competence']), $templateCode);
		if($dvprint || (!$AllowUpdate && !$AllowInsert)){
			$templateCode = str_replace('<%%VALUE(titre_competence)%%>', safe_html($urow['titre_competence']), $templateCode);
		}else{
			$templateCode = str_replace('<%%VALUE(titre_competence)%%>', html_attr($row['titre_competence']), $templateCode);
		}
		$templateCode = str_replace('<%%URLVALUE(titre_competence)%%>', urlencode($urow['titre_competence']), $templateCode);
		if($AllowUpdate || $AllowInsert){
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5">' . html_attr($row['description']) . '</textarea>', $templateCode);
		}else{
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<div id="description" class="form-control-static">' . $row['description'] . '</div>', $templateCode);
		}
		$templateCode = str_replace('<%%VALUE(description)%%>', nl2br($row['description']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode($urow['description']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(domaine_principal)%%>', safe_html($urow['domaine_principal']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(domaine_principal)%%>', html_attr($row['domaine_principal']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(domaine_principal)%%>', urlencode($urow['domaine_principal']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(domaine)%%>', safe_html($urow['domaine']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(domaine)%%>', html_attr($row['domaine']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(domaine)%%>', urlencode($urow['domaine']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id_competence)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_competence)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(titre_competence)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(titre_competence)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5"></textarea>', $templateCode);
		$templateCode = str_replace('<%%VALUE(domaine_principal)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(domaine_principal)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(domaine)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(domaine)%%>', urlencode(''), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode = str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode = str_replace('<%%', '<!-- ', $templateCode);
	$templateCode = str_replace('%%>', ' -->', $templateCode);

	// hide links to inaccessible tables
	if($_REQUEST['dvprint_x'] == ''){
		$templateCode .= "\n\n<script>\$j(function(){\n";
		$arrTables = getTableList();
		foreach($arrTables as $name => $caption){
			$templateCode .= "\t\$j('#{$name}_link').removeClass('hidden');\n";
			$templateCode .= "\t\$j('#xs_{$name}_link').removeClass('hidden');\n";
		}

		$templateCode .= $jsReadOnly;
		$templateCode .= $jsEditable;

		if(!$selected_id){
		}

		$templateCode.="\n});</script>\n";
	}

	// ajaxed auto-fill fields
	$templateCode .= '<script>';
	$templateCode .= '$j(function() {';


	$templateCode.="});";
	$templateCode.="</script>";
	$templateCode .= $lookups;

	// handle enforced parent values for read-only lookup fields

	// don't include blank images in lightbox gallery
	$templateCode = preg_replace('/blank.gif" data-lightbox=".*?"/', 'blank.gif"', $templateCode);

	// don't display empty email links
	$templateCode=preg_replace('/<a .*?href="mailto:".*?<\/a>/', '', $templateCode);

	/* default field values */
	$rdata = $jdata = get_defaults('competences_ref');
	if($selected_id){
		$jdata = get_joined_record('competences_ref', $selected_id);
		if($jdata === false) $jdata = get_defaults('competences_ref');
		$rdata = $row;
	}
	$templateCode .= loadView('competences_ref-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: competences_ref_dv
	if(function_exists('competences_ref_dv')){
		$args=array();
		competences_ref_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>