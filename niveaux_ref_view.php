<?php
// This script and data application were generated by AppGini 5.62
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/niveaux_ref.php");
	include("$currDir/niveaux_ref_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('niveaux_ref');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "niveaux_ref";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`niveaux_ref`.`id_niveau`" => "id_niveau",
		"`niveaux_ref`.`titre`" => "titre",
		"`niveaux_ref`.`niveau`" => "niveau",
		"`niveaux_ref`.`description`" => "description"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`niveaux_ref`.`id_niveau`',
		2 => 2,
		3 => 3,
		4 => 4
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`niveaux_ref`.`id_niveau`" => "id_niveau",
		"`niveaux_ref`.`titre`" => "titre",
		"`niveaux_ref`.`niveau`" => "niveau",
		"`niveaux_ref`.`description`" => "description"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`niveaux_ref`.`titre`" => "Titre",
		"`niveaux_ref`.`niveau`" => "Niveau",
		"`niveaux_ref`.`description`" => "Description"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`niveaux_ref`.`titre`" => "titre",
		"`niveaux_ref`.`niveau`" => "niveau",
		"`niveaux_ref`.`description`" => "description"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`niveaux_ref` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = false;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 0;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "niveaux_ref_view.php";
	$x->RedirectAfterInsert = "niveaux_ref_view.php?SelectedID=#ID#";
	$x->TableTitle = "Niveaux ref";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`niveaux_ref`.`id_niveau`";

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("Titre", "Niveau", "Description");
	$x->ColFieldName = array('titre', 'niveau', 'description');
	$x->ColNumber  = array(2, 3, 4);

	// template paths below are based on the app main directory
	$x->Template = 'templates/niveaux_ref_templateTV.html';
	$x->SelectedTemplate = 'templates/niveaux_ref_templateTVS.html';
	$x->TemplateDV = 'templates/niveaux_ref_templateDV.html';
	$x->TemplateDVP = 'templates/niveaux_ref_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->ShowRecordSlots = 0;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `niveaux_ref`.`id_niveau`=membership_userrecords.pkValue and membership_userrecords.tableName='niveaux_ref' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `niveaux_ref`.`id_niveau`=membership_userrecords.pkValue and membership_userrecords.tableName='niveaux_ref' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`niveaux_ref`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: niveaux_ref_init
	$render=TRUE;
	if(function_exists('niveaux_ref_init')){
		$args=array();
		$render=niveaux_ref_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: niveaux_ref_header
	$headerCode='';
	if(function_exists('niveaux_ref_header')){
		$args=array();
		$headerCode=niveaux_ref_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: niveaux_ref_footer
	$footerCode='';
	if(function_exists('niveaux_ref_footer')){
		$args=array();
		$footerCode=niveaux_ref_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>