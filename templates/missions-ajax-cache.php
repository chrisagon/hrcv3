<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function(){
		var tn = 'missions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			id_consultant: <?php echo json_encode(array('id' => $rdata['id_consultant'], 'value' => $rdata['id_consultant'], 'text' => $jdata['id_consultant'])); ?>,
			rattache_a_cv: <?php echo json_encode(array('id' => $rdata['rattache_a_cv'], 'value' => $rdata['rattache_a_cv'], 'text' => $jdata['rattache_a_cv'])); ?>,
			client: <?php echo json_encode(array('id' => $rdata['client'], 'value' => $rdata['client'], 'text' => $jdata['client'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for id_consultant */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'id_consultant' && d.id == data.id_consultant.id)
				return { results: [ data.id_consultant ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for rattache_a_cv */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'rattache_a_cv' && d.id == data.rattache_a_cv.id)
				return { results: [ data.rattache_a_cv ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for client */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'client' && d.id == data.client.id)
				return { results: [ data.client ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

