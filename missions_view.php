<?php
// This script and data application were generated by AppGini 5.75
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir=dirname(__FILE__);
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	@include("$currDir/hooks/missions.php");
	include("$currDir/missions_dml.php");

	// mm: can the current member access this page?
	$perm=getTablePermissions('missions');
	if(!$perm[0]){
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout("window.location=\'index.php?signOut=1\'", 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = "missions";

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = array(   
		"`missions`.`id_mission`" => "id_mission",
		"IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') /* Consultant */" => "id_consultant",
		"IF(    CHAR_LENGTH(`consultant2`.`Prenom`) || CHAR_LENGTH(`consultant2`.`Nom`), CONCAT_WS('',   `consultant2`.`Prenom`, ', ', `consultant2`.`Nom`), '') /* Rattache au consultant */" => "rattache_a_cv",
		"if(`missions`.`date_debut`,date_format(`missions`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`missions`.`date_fin`,date_format(`missions`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`missions`.`description_mission`" => "description_mission",
		"`missions`.`description_detaille`" => "description_detaille",
		"IF(    CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `client1`.`nom_du_client`), '') /* Client */" => "client"
	);
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = array(   
		1 => '`missions`.`id_mission`',
		2 => 2,
		3 => 3,
		4 => '`missions`.`date_debut`',
		5 => '`missions`.`date_fin`',
		6 => 6,
		7 => 7,
		8 => '`client1`.`nom_du_client`'
	);

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = array(   
		"`missions`.`id_mission`" => "id_mission",
		"IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') /* Consultant */" => "id_consultant",
		"IF(    CHAR_LENGTH(`consultant2`.`Prenom`) || CHAR_LENGTH(`consultant2`.`Nom`), CONCAT_WS('',   `consultant2`.`Prenom`, ', ', `consultant2`.`Nom`), '') /* Rattache au consultant */" => "rattache_a_cv",
		"if(`missions`.`date_debut`,date_format(`missions`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`missions`.`date_fin`,date_format(`missions`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`missions`.`description_mission`" => "description_mission",
		"`missions`.`description_detaille`" => "description_detaille",
		"IF(    CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `client1`.`nom_du_client`), '') /* Client */" => "client"
	);
	// Fields that can be filtered
	$x->QueryFieldsFilters = array(   
		"`missions`.`id_mission`" => "Id mission",
		"IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') /* Consultant */" => "Consultant",
		"IF(    CHAR_LENGTH(`consultant2`.`Prenom`) || CHAR_LENGTH(`consultant2`.`Nom`), CONCAT_WS('',   `consultant2`.`Prenom`, ', ', `consultant2`.`Nom`), '') /* Rattache au consultant */" => "Rattache au consultant",
		"`missions`.`date_debut`" => "Date debut",
		"`missions`.`date_fin`" => "Date fin",
		"`missions`.`description_mission`" => "Description courte de la mission",
		"`missions`.`description_detaille`" => "Description detaille",
		"IF(    CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `client1`.`nom_du_client`), '') /* Client */" => "Client"
	);

	// Fields that can be quick searched
	$x->QueryFieldsQS = array(   
		"`missions`.`id_mission`" => "id_mission",
		"IF(    CHAR_LENGTH(`consultant1`.`Prenom`) || CHAR_LENGTH(`consultant1`.`Nom`), CONCAT_WS('',   `consultant1`.`Prenom`, ', ', `consultant1`.`Nom`), '') /* Consultant */" => "id_consultant",
		"IF(    CHAR_LENGTH(`consultant2`.`Prenom`) || CHAR_LENGTH(`consultant2`.`Nom`), CONCAT_WS('',   `consultant2`.`Prenom`, ', ', `consultant2`.`Nom`), '') /* Rattache au consultant */" => "rattache_a_cv",
		"if(`missions`.`date_debut`,date_format(`missions`.`date_debut`,'%d/%m/%Y'),'')" => "date_debut",
		"if(`missions`.`date_fin`,date_format(`missions`.`date_fin`,'%d/%m/%Y'),'')" => "date_fin",
		"`missions`.`description_mission`" => "description_mission",
		"`missions`.`description_detaille`" => "description_detaille",
		"IF(    CHAR_LENGTH(`client1`.`nom_du_client`), CONCAT_WS('',   `client1`.`nom_du_client`), '') /* Client */" => "client"
	);

	// Lookup fields that can be used as filterers
	$x->filterers = array(  'id_consultant' => 'Consultant', 'rattache_a_cv' => 'Rattache au consultant', 'client' => 'Client');

	$x->QueryFrom = "`missions` LEFT JOIN `consultant` as consultant1 ON `consultant1`.`id_consultant`=`missions`.`id_consultant` LEFT JOIN `curriculum_vitae` as curriculum_vitae1 ON `curriculum_vitae1`.`id_cv`=`missions`.`rattache_a_cv` LEFT JOIN `consultant` as consultant2 ON `consultant2`.`id_consultant`=`curriculum_vitae1`.`du_consultant` LEFT JOIN `client` as client1 ON `client1`.`id_client`=`missions`.`client` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm[2]==0 ? 1 : 0);
	$x->AllowDelete = $perm[4];
	$x->AllowMassDelete = false;
	$x->AllowInsert = $perm[1];
	$x->AllowUpdate = $perm[3];
	$x->SeparateDV = 1;
	$x->AllowDeleteOfParents = 1;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = 1;
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 0;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation["quick search"];
	$x->ScriptFileName = "missions_view.php";
	$x->RedirectAfterInsert = "missions_view.php?SelectedID=#ID#";
	$x->TableTitle = "Vos Missions";
	$x->TableIcon = "resources/table_icons/document_comment_above.png";
	$x->PrimaryKey = "`missions`.`id_mission`";
	$x->DefaultSortField = '`missions`.`date_debut`';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth   = array(  80, 150, 150, 150, 150, 150);
	$x->ColCaption = array("Consultant", "Date debut", "Date fin", "Description courte de la mission", "Description detaille", "Client");
	$x->ColFieldName = array('id_consultant', 'date_debut', 'date_fin', 'description_mission', 'description_detaille', 'client');
	$x->ColNumber  = array(2, 4, 5, 6, 7, 8);

	// template paths below are based on the app main directory
	$x->Template = 'templates/missions_templateTV.html';
	$x->SelectedTemplate = 'templates/missions_templateTVS.html';
	$x->TemplateDV = 'templates/missions_templateDV.html';
	$x->TemplateDVP = 'templates/missions_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HighlightColor = '#FFF0C2';

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, array('user', 'group'))){ $DisplayRecords = 'all'; }
	if($perm[2]==1 || ($perm[2]>1 && $DisplayRecords=='user' && !$_REQUEST['NoFilter_x'])){ // view owner only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `missions`.`id_mission`=membership_userrecords.pkValue and membership_userrecords.tableName='missions' and lcase(membership_userrecords.memberID)='".getLoggedMemberID()."'";
	}elseif($perm[2]==2 || ($perm[2]>2 && $DisplayRecords=='group' && !$_REQUEST['NoFilter_x'])){ // view group only
		$x->QueryFrom.=', membership_userrecords';
		$x->QueryWhere="where `missions`.`id_mission`=membership_userrecords.pkValue and membership_userrecords.tableName='missions' and membership_userrecords.groupID='".getLoggedGroupID()."'";
	}elseif($perm[2]==3){ // view all
		// no further action
	}elseif($perm[2]==0){ // view none
		$x->QueryFields = array("Not enough permissions" => "NEP");
		$x->QueryFrom = '`missions`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: missions_init
	$render=TRUE;
	if(function_exists('missions_init')){
		$args=array();
		$render=missions_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: missions_header
	$headerCode='';
	if(function_exists('missions_header')){
		$args=array();
		$headerCode=missions_header($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$headerCode){
		include_once("$currDir/header.php"); 
	}else{
		ob_start(); include_once("$currDir/header.php"); $dHeader=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%HEADER%%>', $dHeader, $headerCode);
	}

	echo $x->HTML;
	// hook: missions_footer
	$footerCode='';
	if(function_exists('missions_footer')){
		$args=array();
		$footerCode=missions_footer($x->ContentType, getMemberInfo(), $args);
	}  
	if(!$footerCode){
		include_once("$currDir/footer.php"); 
	}else{
		ob_start(); include_once("$currDir/footer.php"); $dFooter=ob_get_contents(); ob_end_clean();
		echo str_replace('<%%FOOTER%%>', $dFooter, $footerCode);
	}
?>