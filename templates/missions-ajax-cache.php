<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'missions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			id_consultant: <?php echo json_encode(array('id' => $rdata['id_consultant'], 'value' => $rdata['id_consultant'], 'text' => $jdata['id_consultant'])); ?>,
			rattache_a_filiere: <?php echo json_encode(array('id' => $rdata['rattache_a_filiere'], 'value' => $rdata['rattache_a_filiere'], 'text' => $jdata['rattache_a_filiere'])); ?>,
			client: <?php echo json_encode(array('id' => $rdata['client'], 'value' => $rdata['client'], 'text' => $jdata['client'])); ?>,
			competences_utilisees: <?php echo json_encode(array('id' => $rdata['competences_utilisees'], 'value' => $rdata['competences_utilisees'], 'text' => $jdata['competences_utilisees'])); ?>,
			tags: <?php echo json_encode(array('id' => $rdata['tags'], 'value' => $rdata['tags'], 'text' => $jdata['tags'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for id_consultant */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'id_consultant' && d.id == data.id_consultant.id)
				return { results: [ data.id_consultant ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for rattache_a_filiere */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'rattache_a_filiere' && d.id == data.rattache_a_filiere.id)
				return { results: [ data.rattache_a_filiere ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for client */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'client' && d.id == data.client.id)
				return { results: [ data.client ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for competences_utilisees */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'competences_utilisees' && d.id == data.competences_utilisees.id)
				return { results: [ data.competences_utilisees ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for tags */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'tags' && d.id == data.tags.id)
				return { results: [ data.tags ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

