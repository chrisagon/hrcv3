<?php

	class AppGiniPlugin{
		protected $title, $name, $logo, $errors;
		public $progress_log;

		public function __construct($config = array()){
			error_reporting(E_ERROR | E_WARNING | E_PARSE);
			$this->errors = array();
			
			$this->title = 'AppGini Plugin';
			$this->name = 'plugin';
			$this->logo = '';
			
			if(isset($config['title'])) $this->title = $config['title'];
			if(isset($config['name'])) $this->name = $config['name'];
			if(isset($config['logo'])) $this->logo = $config['logo'];
			
			if(!class_exists('ProgressLog')) include_once(dirname(__FILE__).'/ProgressLog.php');
			$this->progress_log = new ProgressLog($this);
		}
		
		protected function error($method, $msg, $return = false){
			$this->errors[] = array(
				'method' => $method,
				'msg' => $msg
			);
			
			return $return;
		}
		
		public function last_error(){
			$last_error_index = count($this->errors);
			if(!$last_error_index) return '';
			
			return $this->errors[$last_error_index - 1]['msg'];
		}
		
		/**
		 * filter array of datas
		 * @param $inputArray: input data array
		 */
		public function filter_inputs(&$inputArray){
			foreach ($inputArray as $key => $value) {
				$inputArray[$key] = htmlspecialchars($value);
			}
		}
		
		/**
		 * Copy folder with and sub-folders from source to destinaton
		 * @param $msg: source folder path
		 * 	  $dst: destination folder path
		 */
		public function recurse_copy($src, $dst){
			$dir = opendir($src);
			@mkdir($dst);
			while (false !== ( $file = readdir($dir))) {
				if (( $file != '.' ) && ( $file != '..' )) {
					if (is_dir($src . '/' . $file)) {
						$this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
					} else {
						copy($src . '/' . $file, $dst . '/' . $file);
					}
				}
			}
			closedir($dir);
		}
		
		/**
		 * Display error messages
		 * @param $msg: error message
		 * 		  $back_url: pass explicit false to suppress back button
		 * @return  html code for a styled error message
		 */
		public function error_message($msg, $back_url = ''){
			ob_start();
			echo '<div class="panel panel-danger">';
			echo '<div class="panel-heading"><h3 class="panel-title">Error:</h3></div>';
			echo '<div class="panel-body"><p class="text-danger">' . $msg . '</p>';
			if ($back_url !== false) { // explicitly passing false suppresses the back link completely
				echo '<div class="text-center">';
				if ($back_url) {
					echo '<a href="' . $back_url . '" class="btn btn-danger btn-lg vspacer-lg"><i class="glyphicon glyphicon-chevron-left"></i> < Back </a>';
				} else {
					echo '<a href="#" class="btn btn-danger btn-lg vspacer-lg" onclick="history.go(-1); return false;"><i class="glyphicon glyphicon-chevron-left"></i> < Back </a>';
				}
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
			$out = ob_get_contents();
			ob_end_clean();

			return $out;
		}

		/**
		 * Get XML file object from hashed project file name 
		 * @param $fileHash: md5 hashed project name
		 * 		  $projectFile: project file name ( empty var passed by reference )
		 * @return  XML project file object 
		 */
		public function get_xml_file($fileHash, &$projectFile){
			try {

				$projects = scandir("../projects");
				$projects = array_diff($projects, array('.', '..'));
				$userProject = $fileHash;
				$projectFile = null;

				foreach ($projects as $project) {
					if ($userProject == md5($project)) {
						$projectFile = $project;
						break;
					}
				}
				if (!$projectFile)
					throw new RuntimeException('Project file not found.');

				// validate simpleXML extension enabled
				if (!function_exists(simpleXML_load_file)) {
					throw new RuntimeException('Please, enable simplexml extention in your php.ini configuration file.');
				}


				// validate that the file is not corrupted
				@$xmlFile = simpleXML_load_file("../projects/$projectFile", 'SimpleXMLElement', LIBXML_NOCDATA);
				if (!$xmlFile) {
					throw new RuntimeException('Invalid axp file.');
				}

				return $xmlFile;
			} catch (RuntimeException $e) {
				echo "<br>" . error_message($e->getMessage());
				exit;
			}
		}
		
		/**
		 * Check if the current logged-in user is an adminstrator
		 * @return  boolean
		 */
		public function is_admin(){
			$mi = getMemberInfo();
			if( ! ($mi['admin'] && ((is_string($mi['group']) && $mi['group'] =='Admins') || ( is_array($mi['group']) && array_search("Admins" , $mi['group']))))){
				return false;
			}
			return true;
		}
		
		/**
		 * Update node in axp file table 
		 * @param nodeData : target node data array having:
		 *           projectName: table axp project name
		 *           tableIndex: table index inside axp file
		 *           fieldIndex: field index inside table if exists, null otherwise
		 *           pluginName:  plugin to be updated
		 *           nodeName:  plugin node to be updated if exists, null otherwise
		 *           data: data to update the node with
		 * @return  boolean
		 */
		public function update_project_plugin_node($nodeData){
			if(!preg_match('/^[a-z0-9-_]+\.axp$/i', $nodeData['projectName'] )){
				return $this->error('update_project_plugin_node', 'Invalid project file name');
			}

			$axp_file = dirname(__FILE__) . "/../projects/" . $nodeData['projectName'];
			@$xmlFile = simpleXML_load_file($axp_file, 'SimpleXMLElement', LIBXML_NOCDATA);


			if ( !isset($xmlFile) ) {
				return $this->error('update_project_plugin_node', 'Could not load project file as XML');
			}
			

			$targetNode = &$xmlFile;
			if ( isset ($nodeData['tableIndex']) &&  $nodeData['tableIndex'] >= 0 ){
					$targetNode =& $xmlFile->table[$nodeData['tableIndex']];
					if ( isset ($nodeData['fieldIndex'])  &&  $nodeData['fieldIndex'] >= 0 ){
						$targetNode =& $targetNode->field[$nodeData['fieldIndex']];   
					}
			}

			$targetNode =& $this->check_or_create_plugin_node( $targetNode , $nodeData ) ;
		   
			if ($targetNode){

				$targetNode[0] = $nodeData['data'];
				$xmlFile->asXML($axp_file);    
				return true;
			}    

			return $this->error('update_project_plugin_node', 'No targetNode');
		}
		
		/**
		 * Detect and return the theme style links to insert into the plugin header
		 * @return string '<link rel="stylesheet" ...'
		 */
		public function get_theme_css_links(){
			$host_app_header = @file_get_contents(dirname(__FILE__) . '/../../header.php');
			if(!$host_app_header){
				/* try to guess the theme and assume no 3D effect */
				return '<link rel="stylesheet" href="../../resources/initializr/css/bootstrap.css">';
			}
			
			$regex = '/<link\s+rel="stylesheet".*?resources\/initializr\/css\/(.*?)\.css"/i';
			$mat = array();
			if(!preg_match_all($regex, $host_app_header, $mat)){
				/* error or no matches */
				return '';
			}
			
			$links = '';
			foreach($mat[1] as $m){
				if($m == 'bootstrap-theme'){
					$links .= "<!--[if gt IE 8]><!-->\n";
					$links .= '<link rel="stylesheet" href="../../resources/initializr/css/bootstrap-theme.css">' . "\n";
					$links .= '<!--<![endif]-->' . "\n";
				}else{
					$links .= '<link rel="stylesheet" href="../../resources/initializr/css/' . $m . '.css">' . "\n";
				}
			}
			
			return $links;
		}

		/**
		 * get max. file size from php.ini configuration
		 */
		public function parse_size($size) {
			$unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
			$size = preg_replace('/[^0-9\.]/', '', $size); 		// Remove the non-numeric characters from the size.
			if ($unit) {
				// Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
				return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
			}else {
				return round($size);
			}
		}
		
		/**
		 * Loads a given view, passing the given data to it
		 * @param $view the path of a php file to be loaded
		 * @param $the_data_to_pass_to_the_view (optional) associative array containing the data to pass to the view
		 * @return the output of the parsed view as a string
		 */
		public function view($view, $the_data_to_pass_to_the_view = false){
			if(!is_file($view)) return $this->error('view', "'{$view}' is not a file");

			if(is_array($the_data_to_pass_to_the_view)){
				foreach($the_data_to_pass_to_the_view as $k => $v)
					$$k = $v;
			}
			unset($the_data_to_pass_to_the_view, $k, $v);

			ob_start();
			@include($view);
			$out = ob_get_contents();
			ob_end_clean();

			return $out;
		}

		/**
		 * Loads the page for uploading a new project or opening an existing one
		 * @param $content optional associative array that could contain:
		 *                 'pre_upload': cleint-side code to include above the upload box
		 *                 'post_upload': cleint-side code to include below the upload box
		 * @return the page code
		 */
		public function get_project($content = array()){
			$resources_dir = dirname(__FILE__);
			
			$currentProjects = scandir("{$resources_dir}/../projects");
			
			$content['currentProjects'] = array_diff($currentProjects, array('.', '..'));
			$content['projectsNum'] = count($content['currentProjects']);
			
			return $this->view("{$resources_dir}/views/load-project.php", $content);
		}

		public function process_ajax_upload(){
			$maxFileSize = ($this->parse_size(ini_get('post_max_size')) < $this->parse_size(ini_get('upload_max_filesize')) ? ini_get('post_max_size') : ini_get('upload_max_filesize'));
			
			try {

				//if file exceeded the filesize, no file will be sent
				if(!isset($_FILES['uploadedFile'])) {	
							throw new RuntimeException("No file sent, you must upload a (.axp) file not greater than $maxFileSize");
				}
				
				$file = pathinfo($_FILES['uploadedFile']['name']);
				$ext = $file['extension']; // get the extension of the file	
				$filename = $file['filename'];
					
				// Undefined | Multiple Files | $_FILES Corruption Attack
				// If this request falls under any of them, treat it invalid.
				
				// Check $_FILES['uploadedFile']['error'] value.
				switch ($_FILES['uploadedFile']['error']) {
					case UPLOAD_ERR_OK:
						break;
					case UPLOAD_ERR_NO_FILE:
						throw new RuntimeException('No file sent.');
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						throw new RuntimeException('You must upload a (.axp) file not greater than $maxFileSize"');
					default:
						throw new RuntimeException('Unknown errors.');
				}
			
				//Check extention
				if ( strtolower($ext) != "axp") {
					throw new RuntimeException('You must upload a (.axp) file');
				}
				
				// $_FILES['uploadedFile']['name'] validation
				if( !preg_match('/^[a-z0-9-_]+\.axp$/i', $_FILES['uploadedFile']['name'] )) {
					throw new RuntimeException('File was not uploaded. The file can only contain "a-z", "0-9", "_" and "-".');
				}
				
				//check existing projects' names 
				$currentProjects = scandir ( "../projects"  );
				
				natsort($currentProjects);
				$currentProjects = array_reverse ( $currentProjects );
				
				$renameFlag = false;

				foreach ( $currentProjects as $projName ){
					if ( preg_match('/^'.$filename.'(-[0-9]+)?\.axp$/i', $projName )) {
						
						$matches = array();
						if ( !strcmp ( $_FILES['uploadedFile']['name'] , $projName) ){
							$newName = $filename."-"."1.axp";
							$renameFlag = true;
						}else{
						
							//increment number at the end of the name ( sorted desc, first one is the largest number)
							preg_match('/(-[0-9]+)\.axp$/i', $projName, $matches);
							$number = preg_replace("/[^0-9]/", '', $matches[0]);
							$newName = $filename."-".(((int)$number )+1).".axp";
							$renameFlag = true;
							break;
						}
						
					}else{
						//found name without number at the previous loop, and name with number not found at this loop
						if ($renameFlag){
							break;
						}
					}
				}
					
				if (!move_uploaded_file( $_FILES['uploadedFile']['tmp_name'], sprintf('../projects/%s',($renameFlag?$newName:$_FILES['uploadedFile']['name']))
				)) {
					throw new RuntimeException('Failed to move uploaded file.');
				}else{
			
					//file uploaded successfully							
					echo json_encode(array(
						"response-type" =>"success",
						"isRenamed" =>$renameFlag,
						"fileName" => $renameFlag?$newName:$_FILES['uploadedFile']['name'],
						"md5FileName"=>md5($renameFlag?$newName:$_FILES['uploadedFile']['name'])

					));
				}	
				
			} catch (RuntimeException $e){
				header('Content-Type: application/json');
				header($_SERVER['SERVER_PROTOCOL'] . 'error; Content-Type: application/json', true, 400);
				echo json_encode(array(
						"error" => $e->getMessage()
					));
			}
		}
		
		/**
		 * Injects provided code to a hook file
		 * @param $hook_file_path the full path of the hook file
		 * @param $hook_function name of the hook function to inject code into
		 * @param $location 'top' injects code directly after function declaration line
		 *                  'bottom' injects code directly before the last return statement in the
		 *                           function or before the ending curly bracket if no return statement
		 *                           found before it.
		 *                  >>>> 'bottom' is not yet supported -- only 'top' is supported now.
		 * @return true on success, false on failure
		 */
		public function add_to_hook($hook_file_path, $hook_function, $code, $location = 'top'){
			/* Check if hook file exists and is writable */
			$hook_code = @file_get_contents($hook_file_path);
			if(!$hook_code) return $this->error('add_to_hook', 'Unable to access hook file');
			
			/* Find hook function */
			preg_match('/function\s+' . $hook_function . '\s*\(/i', $hook_code, $matches, PREG_OFFSET_CAPTURE);
			if(count($matches) != 1) return $this->error('add_to_hook', 'Could not determine correct function location');
			
				/* start position of hook function */
				$hf_position = $matches[0][1];
				
				/* position of next function, or EOF position if this is the last function in the file */
				$nf_position = strlen($hook_code);
				preg_match('/function\s+[a-z0-9_]+\s*\(/i', $hook_code, $matches, PREG_OFFSET_CAPTURE, $hf_position + 10);
				if(count($matches)) $nf_position = $matches[0][1];
				
				/* hook function code */
				$old_function_code = substr($hook_code, $hf_position, $nf_position - $hf_position);
			
			/* Checks $code is not already in there */
				if(strpos($old_function_code, $code) !== false) return $this->error('add_to_hook', 'Code already exists');
			
			/* determine insertion point based on $location */
				/*********** location support not yet implemented ************/
			
			/* insert $code and save */
				$code_comment = "/* Inserted by {$this->title} on " . date('Y-m-d h:i:s') . " */";
				$new_function_code = preg_replace(
					'/(function\s+' . $hook_function . '\s*\(.*\)\s*\\' . chr(123) . ')/i',
					"\$1\n\t\t{$code_comment}\n\t\t{$code}\n",
					$old_function_code, 
					1
				);
				if(!$new_function_code) return $this->error('add_to_hook', 'Error while injecting code');
				if($new_function_code == $old_function_code) return $this->error('add_to_hook', 'Nothing changed');
				
				$hook_code = str_replace($old_function_code, $new_function_code, $hook_code);
				if(!@file_put_contents($hook_file_path, $hook_code)) return $this->error('add_to_hook', 'Could not save changes');
				
			return true;
		}
		
		/**
		 * Get client-side code for displaying clickable table list of open project
		 * @param $config associative array containing elements having the following keys:
		 *                'axp': Project XML object, as returned from AppGiniPlugin->get_xml_file()
		 *                'click_handler': js function to invoke when a table is clicked. The index of the table clicked is passed to that function.
		 *                'list_id' optional id attribute of returned list, defaults to 'tables-list'
		 *                'select_first_table' optional flag to select the first table in the list, defaults to true.
		 *                'classes' optional css classes (use space delimiter between class names) to apply to the table list, defaults to empty string.
		 * @return client-side code to be placed in page
		 */
		public function show_tables($config){
			$resources_dir = dirname(__FILE__);
			
			if(!isset($config['axp'])) return $this->error_message('Missing "axp" item in config array passed to show_tables()');
			if(!isset($config['click_handler'])) return $this->error_message('Missing "click_handler" item in config array passed to show_tables()');
			
			if(!isset($config['list_id'])) $config['list_id'] = 'tables-list';
			if(!isset($config['select_first_table'])) $config['select_first_table'] = true;
			if(!isset($config['classes'])) $config['classes'] = '';
			
			return $this->view("{$resources_dir}/views/tables-list.php", $config);
		}
		
		/**
		 * Check existance/create node in xml project structure
		 * @param $targetNode: parent xml node,which will be mofified to match the target node
		 *        $nodeData: target plugin node data to be checked/created
		 * @return the target node
		 */
		private function &check_or_create_plugin_node( &$targetNode , $nodeData){
			if (! isset($targetNode->plugins)){
				   $targetNode->addChild('plugins');  
			}
			$targetNode = &$targetNode->plugins; 
			
			if ( isset ($nodeData['pluginName'])){
				$pluginName = $nodeData['pluginName'];
				if (! isset($targetNode->$pluginName)){
					$targetNode->addChild($pluginName);   
				}
				$targetNode = &$targetNode->$pluginName;
			}

			if ( isset ($nodeData['nodeName'])){
				$node_name = $nodeData['nodeName'];
				if (! isset($targetNode->$node_name)){
				   $targetNode->addChild($node_name);   
				}
				$targetNode = &$targetNode->$node_name;
			}


			return $targetNode;
		}
	}
