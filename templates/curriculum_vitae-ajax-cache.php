<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'curriculum_vitae';

		/* data for selected record, or defaults if none is selected */
		var data = {
			du_consultant: <?php echo json_encode(array('id' => $rdata['du_consultant'], 'value' => $rdata['du_consultant'], 'text' => $jdata['du_consultant'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for du_consultant */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'du_consultant' && d.id == data.du_consultant.id)
				return { results: [ data.du_consultant ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

