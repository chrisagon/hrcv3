<?php
// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/competences_ref.php");
	include("$currDir/competences_ref_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('competences_ref');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "competences_ref";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`competences_ref`.`id_competence`" => "id_competence",
		"`competences_ref`.`titre_competence`" => "titre_competence",
		"`competences_ref`.`description`" => "description",
		"IF(    CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') /* Domaine principal */" => "domaine_principal",
		"`competences_ref`.`domaine`" => "domaine"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`competences_ref`.`id_competence`',
		2 => 2,
		3 => 3,
		4 => 4,
		5 => 5
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`competences_ref`.`id_competence`" => "id_competence",
		"`competences_ref`.`titre_competence`" => "titre_competence",
		"`competences_ref`.`description`" => "description",
		"IF(    CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') /* Domaine principal */" => "domaine_principal",
		"`competences_ref`.`domaine`" => "domaine"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`competences_ref`.`id_competence`" => "Id competence",
		"`competences_ref`.`titre_competence`" => "Titre competence",
		"`competences_ref`.`description`" => "Description",
		"IF(    CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') /* Domaine principal */" => "Domaine principal",
		"`competences_ref`.`domaine`" => "Domaines annexes"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`competences_ref`.`id_competence`" => "id_competence",
		"`competences_ref`.`titre_competence`" => "titre_competence",
		"`competences_ref`.`description`" => "description",
		"IF(    CHAR_LENGTH(`domaine1`.`titre_domaine`) || CHAR_LENGTH(`filiere1`.`titre_filiere`), CONCAT_WS('',   `domaine1`.`titre_domaine`, '>', `filiere1`.`titre_filiere`), '') /* Domaine principal */" => "domaine_principal",
		"`competences_ref`.`domaine`" => "domaine"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'domaine_principal' => 'Domaine principal');

	$x->QueryFrom = "`competences_ref` LEFT JOIN `domaine` as domaine1 ON `domaine1`.`id_domaine`=`competences_ref`.`domaine_principal` LEFT JOIN `filiere` as filiere1 ON `filiere1`.`id_filiere`=`domaine1`.`rattache_a_filiere` ";
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
	$x->ScriptFileName = "competences_ref_view.php";
	$x->RedirectAfterInsert = "competences_ref_view.php?SelectedID=#ID#";
	$x->TableTitle = "R&#233;f&#233;rentiel des comp&#233;tences";
	$x->TableIcon = "table.gif";
	$x->PrimaryKey = "`competences_ref`.`id_competence`";

	$x->ColWidth   = array(  150, 150, 150, 150);
	$x->ColCaption = array("Titre competence", "Description", "Domaine principal", "Domaines annexes");
	$x->ColFieldName = array('titre_competence', 'description', 'domaine_principal', 'domaine');
	$x->ColNumber  = array(2, 3, 4, 5);

	// template paths below are based on the app main directory
	$x->Template = 'templates/competences_ref_templateTV.html';
	$x->SelectedTemplate = 'templates/competences_ref_templateTVS.html';
	$x->TemplateDV = 'templates/competences_ref_templateDV.html';
	$x->TemplateDVP = 'templates/competences_ref_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `competences_ref`.`id_competence`=membership_userrecords.pkValue and membership_userrecords.tableName='competences_ref' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `competences_ref`.`id_competence`=membership_userrecords.pkValue and membership_userrecords.tableName='competences_ref' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`competences_ref`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: competences_ref_init
	$render=TRUE;
	if(function_exists('competences_ref_init')){
		$args=array();
		$render=competences_ref_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: competences_ref_header
	$headerCode='';
	if(function_exists('competences_ref_header')){
		$args=array();
		$headerCode=competences_ref_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: competences_ref_footer
	$footerCode='';
	if(function_exists('competences_ref_footer')){
		$args=array();
		$footerCode=competences_ref_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>