<?php
// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/emploi_fonctionnel.php");
	include("$currDir/emploi_fonctionnel_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('emploi_fonctionnel');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "emploi_fonctionnel";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`emploi_fonctionnel`.`id_ef`" => "id_ef",
		"`emploi_fonctionnel`.`titre_emploifct`" => "titre_emploifct",
		"`emploi_fonctionnel`.`grade`" => "grade",
		"`emploi_fonctionnel`.`echelon`" => "echelon",
		"`emploi_fonctionnel`.`description`" => "description"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`emploi_fonctionnel`.`id_ef`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`emploi_fonctionnel`.`id_ef`" => "id_ef",
		"`emploi_fonctionnel`.`titre_emploifct`" => "titre_emploifct",
		"`emploi_fonctionnel`.`grade`" => "grade",
		"`emploi_fonctionnel`.`echelon`" => "echelon",
		"`emploi_fonctionnel`.`description`" => "description"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`emploi_fonctionnel`.`id_ef`" => "Id ef",
		"`emploi_fonctionnel`.`titre_emploifct`" => "Titre de l\'emploi fonctionnel",
		"`emploi_fonctionnel`.`grade`" => "Grade",
		"`emploi_fonctionnel`.`echelon`" => "Echelon",
		"`emploi_fonctionnel`.`description`" => "Description"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`emploi_fonctionnel`.`id_ef`" => "id_ef",
		"`emploi_fonctionnel`.`titre_emploifct`" => "titre_emploifct",
		"`emploi_fonctionnel`.`grade`" => "grade",
		"`emploi_fonctionnel`.`echelon`" => "echelon",
		"`emploi_fonctionnel`.`description`" => "description"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array();

	$x->QueryFrom = "`emploi_fonctionnel` ";
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
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "emploi_fonctionnel_view.php";
	$x->RedirectAfterInsert = "emploi_fonctionnel_view.php?SelectedID=#ID#";
	$x->TableTitle = "Emploi fonctionnel";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`emploi_fonctionnel`.`id_ef`";

	$x->ColWidth   = array(  150, 150, 150, 150);
	$x->ColCaption = array("Titre de l'emploi fonctionnel", "Grade", "Echelon", "Description");
	$x->ColFieldName = array('titre_emploifct', 'grade', 'echelon', 'description');
	$x->ColNumber  = array(2, 3, 4, 5);

	// template paths below are based on the app main directory
	$x->Template = 'templates/emploi_fonctionnel_templateTV.html';
	$x->SelectedTemplate = 'templates/emploi_fonctionnel_templateTVS.html';
	$x->TemplateDV = 'templates/emploi_fonctionnel_templateDV.html';
	$x->TemplateDVP = 'templates/emploi_fonctionnel_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `emploi_fonctionnel`.`id_ef`=membership_userrecords.pkValue and membership_userrecords.tableName='emploi_fonctionnel' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `emploi_fonctionnel`.`id_ef`=membership_userrecords.pkValue and membership_userrecords.tableName='emploi_fonctionnel' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`emploi_fonctionnel`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: emploi_fonctionnel_init
	$render=TRUE;
	if(function_exists('emploi_fonctionnel_init')){
		$args=array();
		$render=emploi_fonctionnel_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: emploi_fonctionnel_header
	$headerCode='';
	if(function_exists('emploi_fonctionnel_header')){
		$args=array();
		$headerCode=emploi_fonctionnel_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: emploi_fonctionnel_footer
	$footerCode='';
	if(function_exists('emploi_fonctionnel_footer')){
		$args=array();
		$footerCode=emploi_fonctionnel_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>