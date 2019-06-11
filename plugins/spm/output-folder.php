<?php
	include(dirname(__FILE__)."/header.php");

	// validate project name
	if (!isset($_GET['axp']) || !preg_match('/^[a-f0-9]{32}$/i', $_GET['axp'])){
		 echo "<br>".$spm->error_message('Project file not found.');
		exit;
	}
	$projectFile = '';
	$xmlFile = $spm->get_xml_file( $_GET['axp'] , $projectFile);
//-----------------------------------------------------------------------------------------
?>

<link rel="stylesheet" href="../plugins-resources/css/animate-bootstrap-icons.css">

<style>
	.transparent{
		background-color: Transparent;
        background-repeat:no-repeat;
        border: none;
        cursor:default;
        overflow: hidden;
	}
	.popover{
		background-color: rgba(191, 70, 70, 0.33);
	}
	.popover.right .arrow:after {
		  border-right-color: rgba(191, 70, 70, 0.33);
	}
</style>


<div class="page-header row">
	<h1><img src="spm-logo-lg.png" style="height: 1em;"> Search Page Maker for AppGini</h1>
	<h1><a href="./index.php">Projects</a> > <a href="./project.php?axp=<?php echo $_GET['axp'] ?>"><?php echo substr( $projectFile , 0 , strrpos( $projectFile , ".")); ?></a> > Select output folder
	</h1>

</div>
<form method="POST" action="generate.php?axp=<?php echo $_GET['axp']; ?>">
	<h4>Full path to your target application folder</h4>
	<div class="form-group">
		<input name="path" type="text" class="form-control col-md-11 col-xs-9" value="<?php echo dirname(dirname(__DIR__ . "../")); ?>">
	</div>
	<button data-toggle="popover" title="" data-content="" class="transparent btn">
		<i class="glyphicon glyphicon-ok text-success" id="mark"></i>
	</button>
	<br>
	<h5><i>For example: /var/www/my-app</i></h5>
	<h4>How would you like to install the generated search page(s) into the target application?</h4>
	<div class="radio">
		<label>
			<input type="radio" name="write_to_hooks" id="write_to_hooks-yes" value="yes" checked style="margin-top: 0;">
			Automatically install the search page(s) into hooks.
		</label>
	</div>
	<div class="radio">
		<label>
			<input type="radio" name="write_to_hooks" id="write_to_hooks-no" value="no" style="margin-top: 0;">
			Show me the hooks code for manually installing the search page(s).
		</label>
	</div>
	<center>
			<button type="submit" id="start"   class="btn btn-success btn-lg"><span class="glyphicon glyphicon-play" ></span>   Start</button>
	</center>
</form>



<script>
	$j(function(){
		var last_path = '';
		setInterval(function(){
			var new_path = $j('input[name=path]').val();
			if(new_path == last_path) return;
			
			last_path = new_path;
			$j("#mark").attr('class' , 'glyphicon glyphicon-refresh gly-spin');
			$j(".transparent").popover('hide').off();
			$j("#start").hide();
		
			$j.ajax({
				type: "POST",
				url: "project-ajax.php",
				data: {
					actionName: "validatePath",
					path: new_path
				},
				success: function(response){
						if (response == "ok"){
							$j("#mark").attr('class', 'glyphicon glyphicon-ok text-success');
							$j("#start").show();
						}else{
							$j("#mark").attr('class', 'glyphicon glyphicon-remove text-danger');
							$j(".transparent").attr("data-content", response);
							$j(".transparent").popover()
								.on('mouseenter', function(){
									$j(".transparent").popover('show');
								})
								.on('mouseleave', function(){
									$j(".transparent").popover('hide');
								});
						}
				},
			});
			
		}, 500);
	});
</script>