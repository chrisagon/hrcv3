<?php

// Data functions (insert, update, delete, form) for table carriere_consultant

// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

function carriere_consultant_insert(){
	global $Translation;

	// mm: can member insert record?
	$arrPerm=getTablePermissions('carriere_consultant');
	if(!$arrPerm[1]){
		return false;
	}

	$data['date_debut'] = intval($_REQUEST['date_debutYear']) . '-' . intval($_REQUEST['date_debutMonth']) . '-' . intval($_REQUEST['date_debutDay']);
	$data['date_debut'] = parseMySQLDate($data['date_debut'], '');
	$data['date_fin'] = intval($_REQUEST['date_finYear']) . '-' . intval($_REQUEST['date_finMonth']) . '-' . intval($_REQUEST['date_finDay']);
	$data['date_fin'] = parseMySQLDate($data['date_fin'], '');
	$data['titre_emploi'] = makeSafe($_REQUEST['titre_emploi']);
		if($data['titre_emploi'] == empty_lookup_value){ $data['titre_emploi'] = ''; }
	if($data['date_debut']== ''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">" . $Translation['error:'] . " 'Date debut': " . $Translation['field not null'] . '<br><br>';
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}

	// hook: carriere_consultant_before_insert
	if(function_exists('carriere_consultant_before_insert')){
		$args=array();
		if(!carriere_consultant_before_insert($data, getMemberInfo(), $args)){ return false; }
	}

	$o = array('silentErrors' => true);
	sql('insert into `carriere_consultant` set       `date_debut`=' . (($data['date_debut'] !== '' && $data['date_debut'] !== NULL) ? "'{$data['date_debut']}'" : 'NULL') . ', `date_fin`=' . (($data['date_fin'] !== '' && $data['date_fin'] !== NULL) ? "'{$data['date_fin']}'" : 'NULL') . ', `titre_emploi`=' . (($data['titre_emploi'] !== '' && $data['titre_emploi'] !== NULL) ? "'{$data['titre_emploi']}'" : 'NULL'), $o);
	if($o['error']!=''){
		echo $o['error'];
		echo "<a href=\"carriere_consultant_view.php?addNew_x=1\">{$Translation['< back']}</a>";
		exit;
	}

	$recID = db_insert_id(db_link());

	// hook: carriere_consultant_after_insert
	if(function_exists('carriere_consultant_after_insert')){
		$res = sql("select * from `carriere_consultant` where `id_carriere_conslt`='" . makeSafe($recID, false) . "' limit 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = makeSafe($recID, false);
		$args=array();
		if(!carriere_consultant_after_insert($data, getMemberInfo(), $args)){ return $recID; }
	}

	// mm: save ownership data
	set_record_owner('carriere_consultant', $recID, getLoggedMemberID());

	return $recID;
}

function carriere_consultant_delete($selected_id, $AllowDeleteOfParents=false, $skipChecks=false){
	// insure referential integrity ...
	global $Translation;
	$selected_id=makeSafe($selected_id);

	// mm: can member delete record?
	$arrPerm=getTablePermissions('carriere_consultant');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='carriere_consultant' and pkValue='$selected_id'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='carriere_consultant' and pkValue='$selected_id'");
	if(($arrPerm[4]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[4]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[4]==3){ // allow delete?
		// delete allowed, so continue ...
	}else{
		return $Translation['You don\'t have enough permissions to delete this record'];
	}

	// hook: carriere_consultant_before_delete
	if(function_exists('carriere_consultant_before_delete')){
		$args=array();
		if(!carriere_consultant_before_delete($selected_id, $skipChecks, getMemberInfo(), $args))
			return $Translation['Couldn\'t delete this record'];
	}

	sql("delete from `carriere_consultant` where `id_carriere_conslt`='$selected_id'", $eo);

	// hook: carriere_consultant_after_delete
	if(function_exists('carriere_consultant_after_delete')){
		$args=array();
		carriere_consultant_after_delete($selected_id, getMemberInfo(), $args);
	}

	// mm: delete ownership data
	sql("delete from membership_userrecords where tableName='carriere_consultant' and pkValue='$selected_id'", $eo);
}

function carriere_consultant_update($selected_id){
	global $Translation;

	// mm: can member edit record?
	$arrPerm=getTablePermissions('carriere_consultant');
	$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='carriere_consultant' and pkValue='".makeSafe($selected_id)."'");
	$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='carriere_consultant' and pkValue='".makeSafe($selected_id)."'");
	if(($arrPerm[3]==1 && $ownerMemberID==getLoggedMemberID()) || ($arrPerm[3]==2 && $ownerGroupID==getLoggedGroupID()) || $arrPerm[3]==3){ // allow update?
		// update allowed, so continue ...
	}else{
		return false;
	}

	$data['date_debut'] = intval($_REQUEST['date_debutYear']) . '-' . intval($_REQUEST['date_debutMonth']) . '-' . intval($_REQUEST['date_debutDay']);
	$data['date_debut'] = parseMySQLDate($data['date_debut'], '');
	if($data['date_debut']==''){
		echo StyleSheet() . "\n\n<div class=\"alert alert-danger\">{$Translation['error:']} 'Date debut': {$Translation['field not null']}<br><br>";
		echo '<a href="" onclick="history.go(-1); return false;">'.$Translation['< back'].'</a></div>';
		exit;
	}
	$data['date_fin'] = intval($_REQUEST['date_finYear']) . '-' . intval($_REQUEST['date_finMonth']) . '-' . intval($_REQUEST['date_finDay']);
	$data['date_fin'] = parseMySQLDate($data['date_fin'], '');
	$data['titre_emploi'] = makeSafe($_REQUEST['titre_emploi']);
		if($data['titre_emploi'] == empty_lookup_value){ $data['titre_emploi'] = ''; }
	$data['selectedID']=makeSafe($selected_id);

	// hook: carriere_consultant_before_update
	if(function_exists('carriere_consultant_before_update')){
		$args=array();
		if(!carriere_consultant_before_update($data, getMemberInfo(), $args)){ return false; }
	}

	$o=array('silentErrors' => true);
	sql('update `carriere_consultant` set       `date_debut`=' . (($data['date_debut'] !== '' && $data['date_debut'] !== NULL) ? "'{$data['date_debut']}'" : 'NULL') . ', `date_fin`=' . (($data['date_fin'] !== '' && $data['date_fin'] !== NULL) ? "'{$data['date_fin']}'" : 'NULL') . ', `titre_emploi`=' . (($data['titre_emploi'] !== '' && $data['titre_emploi'] !== NULL) ? "'{$data['titre_emploi']}'" : 'NULL') . " where `id_carriere_conslt`='".makeSafe($selected_id)."'", $o);
	if($o['error']!=''){
		echo $o['error'];
		echo '<a href="carriere_consultant_view.php?SelectedID='.urlencode($selected_id)."\">{$Translation['< back']}</a>";
		exit;
	}


	// hook: carriere_consultant_after_update
	if(function_exists('carriere_consultant_after_update')){
		$res = sql("SELECT * FROM `carriere_consultant` WHERE `id_carriere_conslt`='{$data['selectedID']}' LIMIT 1", $eo);
		if($row = db_fetch_assoc($res)){
			$data = array_map('makeSafe', $row);
		}
		$data['selectedID'] = $data['id_carriere_conslt'];
		$args = array();
		if(!carriere_consultant_after_update($data, getMemberInfo(), $args)){ return; }
	}

	// mm: update ownership data
	sql("update membership_userrecords set dateUpdated='".time()."' where tableName='carriere_consultant' and pkValue='".makeSafe($selected_id)."'", $eo);

}

function carriere_consultant_form($selected_id = '', $AllowUpdate = 1, $AllowInsert = 1, $AllowDelete = 1, $ShowCancel = 0, $TemplateDV = '', $TemplateDVP = ''){
	// function to return an editable form for a table records
	// and fill it with data of record whose ID is $selected_id. If $selected_id
	// is empty, an empty form is shown, with only an 'Add New'
	// button displayed.

	global $Translation;

	// mm: get table permissions
	$arrPerm=getTablePermissions('carriere_consultant');
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
	// combobox: date_debut
	$combo_date_debut = new DateCombo;
	$combo_date_debut->DateFormat = "dmy";
	$combo_date_debut->MinYear = 1900;
	$combo_date_debut->MaxYear = 2100;
	$combo_date_debut->DefaultDate = parseMySQLDate('', '');
	$combo_date_debut->MonthNames = $Translation['month names'];
	$combo_date_debut->NamePrefix = 'date_debut';
	// combobox: date_fin
	$combo_date_fin = new DateCombo;
	$combo_date_fin->DateFormat = "dmy";
	$combo_date_fin->MinYear = 1900;
	$combo_date_fin->MaxYear = 2100;
	$combo_date_fin->DefaultDate = parseMySQLDate('', '');
	$combo_date_fin->MonthNames = $Translation['month names'];
	$combo_date_fin->NamePrefix = 'date_fin';

	if($selected_id){
		// mm: check member permissions
		if(!$arrPerm[2]){
			return "";
		}
		// mm: who is the owner?
		$ownerGroupID=sqlValue("select groupID from membership_userrecords where tableName='carriere_consultant' and pkValue='".makeSafe($selected_id)."'");
		$ownerMemberID=sqlValue("select lcase(memberID) from membership_userrecords where tableName='carriere_consultant' and pkValue='".makeSafe($selected_id)."'");
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

		$res = sql("select * from `carriere_consultant` where `id_carriere_conslt`='".makeSafe($selected_id)."'", $eo);
		if(!($row = db_fetch_array($res))){
			return error_message($Translation['No records found'], 'carriere_consultant_view.php', false);
		}
		$urow = $row; /* unsanitized data */
		$hc = new CI_Input();
		$row = $hc->xss_clean($row); /* sanitize data */
		$combo_date_debut->DefaultDate = $row['date_debut'];
		$combo_date_fin->DefaultDate = $row['date_fin'];
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
		$template_file = is_file("./{$TemplateDVP}") ? "./{$TemplateDVP}" : './templates/carriere_consultant_templateDVP.html';
		$templateCode = @file_get_contents($template_file);
	}else{
		$template_file = is_file("./{$TemplateDV}") ? "./{$TemplateDV}" : './templates/carriere_consultant_templateDV.html';
		$templateCode = @file_get_contents($template_file);
	}

	// process form title
	$templateCode = str_replace('<%%DETAIL_VIEW_TITLE%%>', 'Carriere consultant details', $templateCode);
	$templateCode = str_replace('<%%RND1%%>', $rnd1, $templateCode);
	$templateCode = str_replace('<%%EMBEDDED%%>', ($_REQUEST['Embedded'] ? 'Embedded=1' : ''), $templateCode);
	// process buttons
	if($AllowInsert){
		if(!$selected_id) $templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-success" id="insert" name="insert_x" value="1" onclick="return carriere_consultant_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save New'] . '</button>', $templateCode);
		$templateCode = str_replace('<%%INSERT_BUTTON%%>', '<button type="submit" class="btn btn-default" id="insert" name="insert_x" value="1" onclick="return carriere_consultant_validateData();"><i class="glyphicon glyphicon-plus-sign"></i> ' . $Translation['Save As Copy'] . '</button>', $templateCode);
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
			$templateCode = str_replace('<%%UPDATE_BUTTON%%>', '<button type="submit" class="btn btn-success btn-lg" id="update" name="update_x" value="1" onclick="return carriere_consultant_validateData();" title="' . html_attr($Translation['Save Changes']) . '"><i class="glyphicon glyphicon-ok"></i> ' . $Translation['Save Changes'] . '</button>', $templateCode);
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
		$jsReadOnly .= "\tjQuery('#date_debut').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#date_debutDay, #date_debutMonth, #date_debutYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#date_fin').prop('readonly', true);\n";
		$jsReadOnly .= "\tjQuery('#date_finDay, #date_finMonth, #date_finYear').prop('disabled', true).css({ color: '#555', backgroundColor: '#fff' });\n";
		$jsReadOnly .= "\tjQuery('#titre_emploi').replaceWith('<div class=\"form-control-static\" id=\"titre_emploi\">' + (jQuery('#titre_emploi').val() || '') + '</div>');\n";
		$jsReadOnly .= "\tjQuery('.select2-container').hide();\n";

		$noUploads = true;
	}elseif($AllowInsert){
		$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', true);"; // temporarily disable form change handler
			$jsEditable .= "\tjQuery('form').eq(0).data('already_changed', false);"; // re-enable form change handler
	}

	// process combos
	$templateCode = str_replace('<%%COMBO(date_debut)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_date_debut->GetHTML(true) . '</div>' : $combo_date_debut->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(date_debut)%%>', $combo_date_debut->GetHTML(true), $templateCode);
	$templateCode = str_replace('<%%COMBO(date_fin)%%>', ($selected_id && !$arrPerm[3] ? '<div class="form-control-static">' . $combo_date_fin->GetHTML(true) . '</div>' : $combo_date_fin->GetHTML()), $templateCode);
	$templateCode = str_replace('<%%COMBOTEXT(date_fin)%%>', $combo_date_fin->GetHTML(true), $templateCode);

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
	$templateCode = str_replace('<%%UPLOADFILE(id_carriere_conslt)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(date_debut)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(date_fin)%%>', '', $templateCode);
	$templateCode = str_replace('<%%UPLOADFILE(titre_emploi)%%>', '', $templateCode);

	// process values
	if($selected_id){
		if( $dvprint) $templateCode = str_replace('<%%VALUE(id_carriere_conslt)%%>', safe_html($urow['id_carriere_conslt']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(id_carriere_conslt)%%>', html_attr($row['id_carriere_conslt']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_carriere_conslt)%%>', urlencode($urow['id_carriere_conslt']), $templateCode);
		$templateCode = str_replace('<%%VALUE(date_debut)%%>', @date('d/m/Y', @strtotime(html_attr($row['date_debut']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(date_debut)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['date_debut'])))), $templateCode);
		$templateCode = str_replace('<%%VALUE(date_fin)%%>', @date('d/m/Y', @strtotime(html_attr($row['date_fin']))), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(date_fin)%%>', urlencode(@date('d/m/Y', @strtotime(html_attr($urow['date_fin'])))), $templateCode);
		if( $dvprint) $templateCode = str_replace('<%%VALUE(titre_emploi)%%>', safe_html($urow['titre_emploi']), $templateCode);
		if(!$dvprint) $templateCode = str_replace('<%%VALUE(titre_emploi)%%>', html_attr($row['titre_emploi']), $templateCode);
		$templateCode = str_replace('<%%URLVALUE(titre_emploi)%%>', urlencode($urow['titre_emploi']), $templateCode);
	}else{
		$templateCode = str_replace('<%%VALUE(id_carriere_conslt)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(id_carriere_conslt)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(date_debut)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(date_debut)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(date_fin)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(date_fin)%%>', urlencode(''), $templateCode);
		$templateCode = str_replace('<%%VALUE(titre_emploi)%%>', '', $templateCode);
		$templateCode = str_replace('<%%URLVALUE(titre_emploi)%%>', urlencode(''), $templateCode);
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
	$rdata = $jdata = get_defaults('carriere_consultant');
	if($selected_id){
		$jdata = get_joined_record('carriere_consultant', $selected_id);
		if($jdata === false) $jdata = get_defaults('carriere_consultant');
		$rdata = $row;
	}
	$templateCode .= loadView('carriere_consultant-ajax-cache', array('rdata' => $rdata, 'jdata' => $jdata));

	// hook: carriere_consultant_dv
	if(function_exists('carriere_consultant_dv')){
		$args=array();
		carriere_consultant_dv(($selected_id ? $selected_id : FALSE), getMemberInfo(), $templateCode, $args);
	}

	return $templateCode;
}
?>