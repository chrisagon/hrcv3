<?php
	include(dirname(__FILE__) . "/header.php");
	
	echo $spm->get_project(array(
		'pre_upload' => file_get_contents(dirname(__FILE__) . "/video-link.html")
	));
?>

</body>
</html>