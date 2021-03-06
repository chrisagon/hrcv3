<?php
// This script and data application were generated by AppGini 5.81
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/carriere_consultant.php");
	include("$currDir/carriere_consultant_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('carriere_consultant');
	if(!$perm[0]) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "carriere_consultant";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(
		"`carriere_consultant`.`id_carriere_conslt`" => "id_carriere_conslt",
		"if(`carriere_consultant`.`date_debut`,date_format(`carriere_consultant`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`carriere_consultant`.`date_fin`,date_format(`carriere_consultant`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`carriere_consultant`.`titre_emploi`" => "titre_emploi",
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(
		1 => '`carriere_consultant`.`id_carriere_conslt`',
		2 => '`carriere_consultant`.`date_debut`',
		3 => '`carriere_consultant`.`date_fin`',
		4 => 4,
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(
		"`carriere_consultant`.`id_carriere_conslt`" => "id_carriere_conslt",
		"if(`carriere_consultant`.`date_debut`,date_format(`carriere_consultant`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`carriere_consultant`.`date_fin`,date_format(`carriere_consultant`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`carriere_consultant`.`titre_emploi`" => "titre_emploi",
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(
		"`carriere_consultant`.`id_carriere_conslt`" => "Id carriere conslt",
		"`carriere_consultant`.`date_debut`" => "Date debut",
		"`carriere_consultant`.`date_fin`" => "Date fin",
		"`carriere_consultant`.`titre_emploi`" => "Titre emploi",
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(
		"`carriere_consultant`.`id_carriere_conslt`" => "id_carriere_conslt",
		"if(`carriere_consultant`.`date_debut`,date_format(`carriere_consultant`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`carriere_consultant`.`date_fin`,date_format(`carriere_consultant`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`carriere_consultant`.`titre_emploi`" => "titre_emploi",
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`carriere_consultant` ";
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
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "carriere_consultant_view.php";
	$x->RedirectAfterInsert = "carriere_consultant_view.php?SelectedID=#ID#";
	$x->TableTitle = "Votre Carriere";
	$x->TableIcon = "resources/table_icons/chair.png";
	$x->PrimaryKey = "`carriere_consultant`.`id_carriere_conslt`";
	$x->DefaultSortField = '`carriere_consultant`.`date_debut`';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth   = array(  150, 150, 150);
	$x->ColCaption = array("Date debut", "Date fin", "Titre emploi");
	$x->ColFieldName = array('date_debut', 'date_fin', 'titre_emploi');
	$x->ColNumber  = array(2, 3, 4);

	// template paths below are based on the app main directory
	$x->Template = 'templates/carriere_consultant_templateTV.html';
	$x->SelectedTemplate = 'templates/carriere_consultant_templateTVS.html';
	$x->TemplateDV = 'templates/carriere_consultant_templateDV.html';
	$x->TemplateDVP = 'templates/carriere_consultant_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';
	$x->HasCalculatedFields = false;

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))) { $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])) { // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `carriere_consultant`.`id_carriere_conslt`=membership_userrecords.pkValue and membership_userrecords.tableName='carriere_consultant' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `carriere_consultant`.`id_carriere_conslt`=membership_userrecords.pkValue and membership_userrecords.tableName='carriere_consultant' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3) { // view all
		// no further action
	}elseif($perm[2]==0) { // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`carriere_consultant`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: carriere_consultant_init
	$render=TRUE;
	if(function_exists('carriere_consultant_init')) {
		$args=array();
		$render=carriere_consultant_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: carriere_consultant_header
	$headerCode='';
	if(function_exists('carriere_consultant_header')) {
		$args=array();
		$headerCode=carriere_consultant_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode) {
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: carriere_consultant_footer
	$footerCode='';
	if(function_exists('carriere_consultant_footer')) {
		$args=array();
		$footerCode=carriere_consultant_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode) {
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>