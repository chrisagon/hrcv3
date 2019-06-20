<?php

// Data functions (insert, update, delete, form) for table niveaux_ref

// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

function niveaux_ref_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('niveaux_ref');
	if(!$arrPerm[1]){
		return false;
	}

	$data['titre'] = makeSafe($_REQUEST['titre']);
		if($data['titre'] == empty_lookup_value){ $data['titre'] = ''; }
	$data['niveau'] = makeSafe($_REQUEST['niveau']);
		if($data['niveau'] == empty_lookup_value){ $data['niveau'] = ''; }
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }

	// hook: niveaux_ref_before_insert
	if(function_exists('niveaux_ref_before_insert')){
		$args=array();
		if(!niveaux_ref_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `niveaux_ref` set       `titre`=' . (($data['titre'] !== '' && $data['titre'] !== NULL) ? "'{$data['titre']}'" : 'NULL') . ', `niveau`=' . (($data['niveau'] !== '' && $data['niveau'] !== NULL) ? "'{$data['niveau']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"niveaux_ref_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: niveaux_ref_after_insert
	if(function_exists('niveaux_ref_after_insert')){
		$res = sql("select * from `niveaux_ref` where `id_niveau`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!niveaux_ref_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('niveaux_ref', $recID, getLoggedMemberID());

	return $recID;
}

function niveaux_ref_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('niveaux_ref');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='niveaux_ref' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='niveaux_ref' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: niveaux_ref_before_delete
	if(function_exists('niveaux_ref_before_delete')){
		$args=array();
		if(!niveaux_ref_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	// child table: competences_individuelles
	$res = sql("select `id_niveau` from `niveaux_ref` where `id_niveau`='$selected_id'", $eo);
	$id_niveau = db_fetch_row($res);
	$rires = sql("select count(1) from `competences_individuelles` where `niveau`='".addslashes($id_niveau[0])."'", $eo);
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
		$RetMsg = str_replace("<Delete>", "<input type=\"button\" class=\"button\" value=\"".$Translation['yes']."\" onClick=\"window.location='niveaux_ref_view.php?SelectedID=".urlencode($selected_id)."&delete_x=1&confirmed=1';\">", $RetMsg);
		$RetMsg = str_replace("<Cancel>", "<input type=\"button\" class=\"button\" value=\"".$Translation['no']."\" onClick=\"window.location='niveaux_ref_view.php?SelectedID=".urlencode($selected_id)."';\">", $RetMsg);
		return $RetMsg;
	}

	sql("delete from `niveaux_ref` where `id_niveau`='$selected_id'", $eo);

	// hook: niveaux_ref_after_delete
	if(function_exists('niveaux_ref_after_delete')){
		$args=array();
		niveaux_ref_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='niveaux_ref' and pkValue='$selected_id'", $eo);
}

function niveaux_ref_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('niveaux_ref');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='niveaux_ref' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='niveaux_ref' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['titre'] = makeSafe($_REQUEST['titre']);
		if($data['titre'] == empty_lookup_value){ $data['titre'] = ''; }
	$data['niveau'] = makeSafe($_REQUEST['niveau']);
		if($data['niveau'] == empty_lookup_value){ $data['niveau'] = ''; }
	$data['description'] = makeSafe($_REQUEST['description']);
		if($data['description'] == empty_lookup_value){ $data['description'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: niveaux_ref_before_update
	if(function_exists('niveaux_ref_before_update')){
		$args=array();
		if(!niveaux_ref_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `niveaux_ref` set       `titre`=' . (($data['titre'] !== '' && $data['titre'] !== NULL) ? "'{$data['titre']}'" : 'NULL') . ', `niveau`=' . (($data['niveau'] !== '' && $data['niveau'] !== NULL) ? "'{$data['niveau']}'" : 'NULL') . ', `description`=' . (($data['description'] !== '' && $data['description'] !== NULL) ? "'{$data['description']}'" : 'NULL') . " where `id_niveau`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="niveaux_ref_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: niveaux_ref_after_update
	if(function_exists('niveaux_ref_after_update')){
		$res = sql("SELECT * FROM `niveaux_ref` WHERE `id_niveau`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id_niveau'];
		$args = array();
		if(!niveaux_ref_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='niveaux_ref' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function niveaux_ref_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('niveaux_ref');
	if(!$arrPerm[1] && $selected_id==''){ return ''; }
	$AllowInsert = ($arrPerm[1] ? true : false);
	// print preview?
	$dvprint = false;
	if($selected_id && $_REQUEST['dvprint_x'] != ''){
		$dvprint = true;
	}


	// populate filterers, starting from children to grand-parents

	// unique random identifier
	$rnd1 = ($dvprint ? rand(1000000, 9999999) : '');

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='niveaux_ref' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='niveaux_ref' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `niveaux_ref` where `id_niveau`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'niveaux_ref_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
	}else{
	}

	ob_start();
	?>

	<script>
		// initial lookup values

		jQuery(function() {
			setTimeout(function(){
			}, 10); /* we need to slightly delay client-side execution of the above code to allow AppGini.ajaxCache to work */
		});
	</script>
	<?php

	$lookups = str_replace('__RAND__', $rnd1, ob_get_contents());
	ob_end_clean();


	// code for template based detail view forms

	// open the detail view template
	if($dvprint){
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/niveaux_ref_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/niveaux_ref_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Niveaux ref details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return niveaux_ref_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return niveaux_ref_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return niveaux_ref_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#titre').replaceWith('<div class=\"form-control-static\" id=\"titre\">' + (jQuery('#titre').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('#niveau').replaceWith('<div class=\"form-control-static\" id=\"niveau\">' + (jQuery('#niveau').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos

	/* lookup fields array: 'lookup field name' => array('parent table name', 'lookup field caption') */
	$lookup_fields = array();
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
	$templateCode = str_replace('<%%UPLOADFILE(id_niveau)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(titre)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(niveau)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(description)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id_niveau)%%>', safe_html($urow['id_niveau']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id_niveau)%%>', html_attr($row['id_niveau']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_niveau)%%>', urlencode($urow['id_niveau']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(titre)%%>', safe_html($urow['titre']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(titre)%%>', html_attr($row['titre']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(titre)%%>', urlencode($urow['titre']), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(niveau)%%>', safe_html($urow['niveau']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(niveau)%%>', html_attr($row['niveau']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(niveau)%%>', urlencode($urow['niveau']), $templateCode);
		if($AllowUpdate || $AllowInsert){
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5">' . html_attr($row['description']) . '</textarea>', $templateCode);
		}else{
			$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<div id="description" class="form-control-static">' . $row['description'] . '</div>', $templateCode);
		}
		$templateCode = str_replace('<%%VALUE(description)%%>', nl2br($row['description']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(description)%%>', urlencode($urow['description']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id_niveau)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_niveau)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(titre)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(titre)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(niveau)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(niveau)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%HTMLAREA(description)%%>', '<textarea name="description" id="description" rows="5"></textarea>', $templateCode);
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
	$rdata = $jdata = get_defaults('niveaux_ref');
	if($selected_id){
		$jdata = get_joined_record('niveaux_ref', $selected_id);
		if($jdata === false) $jdata = get_defaults('niveaux_ref');
		$rdata = $row;
	}
	$templateCode .= loadView('niveaux_ref-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: niveaux_ref_dv
	if(function_exists('niveaux_ref_dv')){
		$args=array();
		niveaux_ref_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>