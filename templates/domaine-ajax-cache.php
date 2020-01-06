<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'domaine';

		/* data for selected record, or defaults if none is selected */
		var data = {
			rattache_a_filiere: <?php echo json_encode(array('id' => $rdata['rattache_a_filiere'], 'value' => $rdata['rattache_a_filiere'], 'text' => $jdata['rattache_a_filiere'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for rattache_a_filiere */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'rattache_a_filiere' && d.id == data.rattache_a_filiere.id)
				return { results: [ data.rattache_a_filiere ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

