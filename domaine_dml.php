<?php

// Data functions (insert, update, delete, form) for table domaine

// This script and data application were generated by AppGini 5.62
// Download AppGini for free from https://bigprof.com/appgini/download/

function domaine_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('domaine');
	if(!$arrPerm[1]){
		return false;
	}

	$data['titre_domaine'] = makeSafe($_REQUEST['titre_domaine']);
		if($data['titre_domaine'] == empty_lookup_value){ $data['titre_domaine'] = ''; }
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }
	$data['rattache_a_filiere'] = makeSafe($_REQUEST['rattache_a_filiere']);
		if($data['rattache_a_filiere'] == empty_lookup_value){ $data['rattache_a_filiere'] = ''; }

	// hook: domaine_before_insert
	if(function_exists('domaine_before_insert')){
		$args=array();
		if(!domaine_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `domaine` set       `titre_domaine`=' . (($data['titre_domaine'] !== '' && $data['titre_domaine'] !== NULL) ? "'{$data['titre_domaine']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', `rattache_a_filiere`=' . (($data['rattache_a_filiere'] !== '' && $data['rattache_a_filiere'] !== NULL) ? "'{$data['rattache_a_filiere']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"domaine_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: domaine_after_insert
	if(function_exists('domaine_after_insert')){
		$res = sql("select * from `domaine` where `id_domaine`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!domaine_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	sql("insert ignore into membership_userrecords set tableName='domaine', pkValue='" . makeSafe($recID, false) . "', memberID='" . makeSafe(getLoggedMemberID(), false) . "', dateAdded='" . time() . "', dateUpdated='" . time() . "', groupID='" . getLoggedGroupID() . "'", $eo);

	return $recID;
}

function domaine_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('domaine');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='domaine' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='domaine' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: domaine_before_delete
	if(function_exists('domaine_before_delete')){
		$args=array();
		if(!domaine_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: competences_ref
	$res = sql("select `id_domaine` from `domaine` where `id_domaine`='$selected_id'", $eo);
	$id_domaine = db_fetch_row($res);
	$rires = sql("select count(1) from `competences_ref` where `domaine_principal`='".addslashes($id_domaine[0])."'", $eo);
	$rirow = db_fetch_row($rires);
	if($rirow[0] && !$AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["couldn't delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "competences_ref", $RetMsg);
		return $RetMsg;
	}elseif($rirow[0] && $AllowDeleteOfParents && !$skipChecks){
		$RetMsg = $Translation["confirm delete"];
		$RetMsg = str_replace("<RelatedRecords>", $rirow[0], $RetMsg);
		$RetMsg = str_replace("<TableName>", "competences_ref", $RetMsg);
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='domaine_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='domaine_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `domaine` where `id_domaine`='$selected_id'", $eo);

	// hook: domaine_after_delete
	if(function_exists('domaine_after_delete')){
		$args=array();
		domaine_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='domaine' and pkValue='$selected_id'", $eo);
}

function domaine_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('domaine');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='domaine' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='domaine' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['titre_domaine'] = makeSafe($_REQUEST['titre_domaine']);
		if($data['titre_domaine'] == empty_lookup_value){ $data['titre_domaine'] = ''; }
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }
	$data['rattache_a_filiere'] = makeSafe($_REQUEST['rattache_a_filiere']);
		if($data['rattache_a_filiere'] == empty_lookup_value){ $data['rattache_a_filiere'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: domaine_before_update
	if(function_exists('domaine_before_update')){
		$args=array();
		if(!domaine_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `domaine` set       `titre_domaine`=' . (($data['titre_domaine'] !== '' && $data['titre_domaine'] !== NULL) ? "'{$data['titre_domaine']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . ', `rattache_a_filiere`=' . (($data['rattache_a_filiere'] !== '' && $data['rattache_a_filiere'] !== NULL) ? "'{$data['rattache_a_filiere']}'" : 'NULL') . " where `id_domaine`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="domaine_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: domaine_after_update
	if(function_exists('domaine_after_update')){
		$res = sql("SELECT * FROM `domaine` WHERE `id_domaine`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id_domaine'];
		$args = array();
		if(!domaine_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='domaine' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function domaine_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('domaine');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}

	$filterer_rattache_a_filiere = thisOr(undo_magic_quotes($_REQUEST['filterer_rattache_a_filiere']), '');

	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');
	// combobox: rattache_a_filiere
	$combo_rattache_a_filiere = new DataCombo;

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='domaine' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='domaine' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `domaine` where `id_domaine`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'domaine_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_rattache_a_filiere->SelectedData = $row['rattache_a_filiere'];
	}else{
		$combo_rattache_a_filiere->SelectedData = $filterer_rattache_a_filiere;
	}
	$combo_rattache_a_filiere->HTML = '<span id="rattache_a_filiere-container' . $rnd1 . '"></span><input type="hidden" name="rattache_a_filiere" id="rattache_a_filiere' . $rnd1 . '" value="' . html_attr($combo_rattache_a_filiere->SelectedData) . '">';
	$combo_rattache_a_filiere->MatchText = '<span id="rattache_a_filiere-container-readonly' . $rnd1 . '"></span><input type="hidden" name="rattache_a_filiere" id="rattache_a_filiere' . $rnd1 . '" value="' . html_attr($combo_rattache_a_filiere->SelectedData) . '">';

	ob_start();
	?>

	<script>
		// initial lookup values
		AppGini.current_rattache_a_filiere__RAND__ = { text: "", value: "<?php echo addslashes($selected_id ? $urow['rattache_a_filiere'] : $filterer_rattache_a_filiere); ?>"};

		jQuery(function() {
			setTimeout(function(){
				if(typeof(rattache_a_filiere_reload__RAND__) == 'function') rattache_a_filiere_reload__RAND__();
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
		function rattache_a_filiere_reload__RAND__(){
		<?php if(($AllowUpdate || $AllowInsert) && !$dvprint){ ?>

			$j("#rattache_a_filiere-container__RAND__").select2({
				/* initial default value */
				initSelection: function(e, c){
					$j.ajax({
						url: 'ajax_combo.php',
						dataType: 'json',
						data: { id: AppGini.current_rattache_a_filiere__RAND__.value, t: 'domaine', f: 'rattache_a_filiere' },
						success: function(resp){
							c({
								id: resp.results[0].id,
								text: resp.results[0].text
							});
							$j('[name="rattache_a_filiere"]').val(resp.results[0].id);
							$j('[id=rattache_a_filiere-container-readonly__RAND__]').html('<span id="rattache_a_filiere-match-text">' + resp.results[0].text + '</span>');
							if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=filiere_view_parent]').hide(); }else{ $j('.btn[id=filiere_view_parent]').show(); }


							if(typeof(rattache_a_filiere_update_autofills__RAND__) == 'function') rattache_a_filiere_update_autofills__RAND__();
						}
					});
				},
				width: ($j('fieldset .col-xs-11').width() - select2_max_width_decrement()) + 'px',
				formatNoMatches: function(term){ return '<?php echo addslashes($Translation['No matches found!']); ?>'; },
				minimumResultsForSearch: 10,
				loadMorePadding: 200,
				ajax: {
					url: 'ajax_combo.php',
					dataType: 'json',
					cache: true,
					data: function(term, page){ return { s: term, p: page, t: 'domaine', f: 'rattache_a_filiere' }; },
					results: function(resp, page){ return resp; }
				},
				escapeMarkup: function(str){ return str; }
			}).on('change', function(e){
				AppGini.current_rattache_a_filiere__RAND__.value = e.added.id;
				AppGini.current_rattache_a_filiere__RAND__.text = e.added.text;
				$j('[name="rattache_a_filiere"]').val(e.added.id);
				if(e.added.id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=filiere_view_parent]').hide(); }else{ $j('.btn[id=filiere_view_parent]').show(); }


				if(typeof(rattache_a_filiere_update_autofills__RAND__) == 'function') rattache_a_filiere_update_autofills__RAND__();
			});

			if(!$j("#rattache_a_filiere-container__RAND__").length){
				$j.ajax({
					url: 'ajax_combo.php',
					dataType: 'json',
					data: { id: AppGini.current_rattache_a_filiere__RAND__.value, t: 'domaine', f: 'rattache_a_filiere' },
					success: function(resp){
						$j('[name="rattache_a_filiere"]').val(resp.results[0].id);
						$j('[id=rattache_a_filiere-container-readonly__RAND__]').html('<span id="rattache_a_filiere-match-text">' + resp.results[0].text + '</span>');
						if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=filiere_view_parent]').hide(); }else{ $j('.btn[id=filiere_view_parent]').show(); }

						if(typeof(rattache_a_filiere_update_autofills__RAND__) == 'function') rattache_a_filiere_update_autofills__RAND__();
					}
				});
			}

		<?php }else{ ?>

			$j.ajax({
				url: 'ajax_combo.php',
				dataType: 'json',
				data: { id: AppGini.current_rattache_a_filiere__RAND__.value, t: 'domaine', f: 'rattache_a_filiere' },
				success: function(resp){
					$j('[id=rattache_a_filiere-container__RAND__], [id=rattache_a_filiere-container-readonly__RAND__]').html('<span id="rattache_a_filiere-match-text">' + resp.results[0].text + '</span>');
					if(resp.results[0].id == '<?php echo empty_lookup_value; ?>'){ $j('.btn[id=filiere_view_parent]').hide(); }else{ $j('.btn[id=filiere_view_parent]').show(); }

					if(typeof(rattache_a_filiere_update_autofills__RAND__) == 'function') rattache_a_filiere_update_autofills__RAND__();
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/domaine_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/domaine_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Domaine details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return domaine_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return domaine_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%INSERT_BUTTON%%>', '', $templateCode);
	}

	// 'Back' button action
	if($_REQUEST['Embedded']){
		$backAction = 'window.parent.jQuery(\'.modal\').modal(\'hide\'); return false;';
	}else{
		$backAction = '$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;';
	}

	if($selected_id){
		if(!$_REQUEST['Embedded']) $templateCode=str_replace('<%%DVPRINT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="dvprint" name="dvprint_x" value="1" onclick="$$(\'form\')[0].writeAttribute(\'novalidate\', \'novalidate\'); document.myform.reset(); return true;" title="' . html_attr($Translation['Print Preview']) . '"><i class="glyphicon glyphicon-print"></i> ' . $Translation['Print Preview'] . '</button>', $templateCode);
		if($AllowUpdate){
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return domaine_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		}
		if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '<button type="submit" class="btn btn-danger" id="delete" name="delete_x" value="1" onclick="return confirm(\'' . $Translation['are you sure?'] . '\');" title="' . html_attr($Translation['Delete']) . '"><i class="glyphicon glyphicon-trash"></i> ' . $Translation['Delete'] . '</button>', $templateCode);
		}else{
			$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		}
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>', $templateCode);
	}else{
		$templateCode=str_replace('<%%UPDATE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DELETE_BUTTON%%>', '', $templateCode);
		$templateCode=str_replace('<%%DESELECT_BUTTON%%>', ($ShowCancel ? '<button type="submit" class="btn btn-default" id="deselect" name="deselect_x" value="1" onclick="' . $backAction . '" title="' . html_attr($Translation['Back']) . '"><i class="glyphicon glyphicon-chevron-left"></i> ' . $Translation['Back'] . '</button>' : ''), $templateCode);
	}

	// set records to read only if user can't insert new records and can't edit current record
	if(($selected_id && !$AllowUpdate && !$AllowInsert) || (!$selected_id && !$AllowInsert)){
		$jsReadOnly .= "\tjQuery('#titre_domaine').replaceWith('<div class=\"form-control-static\" id=\"titre_domaine\">' + (jQuery('#titre_domaine').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#rattache_a_filiere').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#rattache_a_filiere_caption').prop('disabled', true).css({ color: '#555', backgroundColor: 'white' });\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode=str_replace('<%%COMBO(rattache_a_filiere)%%>', $combo_rattache_a_filiere->HTML, $templateCode);
	$templateCode=str_replace('<%%COMBOTEXT(rattache_a_filiere)%%>', $combo_rattache_a_filiere->MatchText, $templateCode);
	$templateCode=str_replace('<%%URLCOMBOTEXT(rattache_a_filiere)%%>', urlencode($combo_rattache_a_filiere->MatchText), $templateCode);

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array(  'rattache_a_filiere' => array('filiere', 'Rattache a la Fili&#232;re'));
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
	$templateCode=str_replace('<%%UPLOADFILE(id_domaine)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(titre_domaine)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(description)%%>', '', $templateCode);
	$templateCode=str_replace('<%%UPLOADFILE(rattache_a_filiere)%%>', '', $templateCode);

	// process values
	if($selected_id){
		$templateCode=str_replace('<%%VALUE(id_domaine)%%>', html_attr($row['id_domaine']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(id_domaine)%%>', urlencode($urow['id_domaine']), $templateCode);
		$templateCode=str_replace('<%%VALUE(titre_domaine)%%>', html_attr($row['titre_domaine']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(titre_domaine)%%>', urlencode($urow['titre_domaine']), $templateCode);
		if($AllowUpdate || $AllowInsert){
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5">' . html_attr($row['description']) . '</textarea>', $templateCode);
		}else{
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<div id="description" class="form-control-static">' . $row['description'] . '</div>', $templateCode);
		}
		$templateCode=str_replace('<%%VALUE(description)%%>', nl2br($row['description']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(description)%%>', urlencode($urow['description']), $templateCode);
		$templateCode=str_replace('<%%VALUE(rattache_a_filiere)%%>', html_attr($row['rattache_a_filiere']), $templateCode);
		$templateCode=str_replace('<%%URLVALUE(rattache_a_filiere)%%>', urlencode($urow['rattache_a_filiere']), $templateCode);
	}else{
		$templateCode=str_replace('<%%VALUE(id_domaine)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(id_domaine)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%VALUE(titre_domaine)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(titre_domaine)%%>', urlencode(''), $templateCode);
		$templateCode=str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5"></textarea>', $templateCode);
		$templateCode=str_replace('<%%VALUE(rattache_a_filiere)%%>', '', $templateCode);
		$templateCode=str_replace('<%%URLVALUE(rattache_a_filiere)%%>', urlencode(''), $templateCode);
	}

	// process translations
	foreach($Translation as $symbol=>$trans){
		$templateCode=str_replace("<%%TRANSLATION($symbol)%%>", $trans, $templateCode);
	}

	// clear scrap
	$templateCode=str_replace('<%%', '<!-- ', $templateCode);
	$templateCode=str_replace('%%>', ' -->', $templateCode);

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
	$rdata = $jdata = get_defaults('domaine');
	if($selected_id){
		$jdata = get_joined_record('domaine', $selected_id);
		$rdata = $row;
	}
	$cache_data = array(
		'rdata' => array_map('nl2br', array_map('addslashes', $rdata)),
		'jdata' => array_map('nl2br', array_map('addslashes', $jdata)),
	);
	$templateCode .= loadView('domaine-ajax-cache', $cache_data);

	// hook: domaine_dv
	if(function_exists('domaine_dv')){
		$args=array();
		domaine_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>