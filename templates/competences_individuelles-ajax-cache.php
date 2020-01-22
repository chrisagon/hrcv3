<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'competences_individuelles';

		/* data for selected record, or defaults if none is selected */
		var data = {
			competence_mis_en_oeuvre: <?php echo json_encode(array('id' => $rdata['competence_mis_en_oeuvre'], 'value' => $rdata['competence_mis_en_oeuvre'], 'text' => $jdata['competence_mis_en_oeuvre'])); ?>,
			niveau: <?php echo json_encode(array('id' => $rdata['niveau'], 'value' => $rdata['niveau'], 'text' => $jdata['niveau'])); ?>,
			consultant_id: <?php echo json_encode(array('id' => $rdata['consultant_id'], 'value' => $rdata['consultant_id'], 'text' => $jdata['consultant_id'])); ?>,
			tags: <?php echo json_encode(array('id' => $rdata['tags'], 'value' => $rdata['tags'], 'text' => $jdata['tags'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for competence_mis_en_oeuvre */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'competence_mis_en_oeuvre' && d.id == data.competence_mis_en_oeuvre.id)
				return { results: [ data.competence_mis_en_oeuvre ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for niveau */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'niveau' && d.id == data.niveau.id)
				return { results: [ data.niveau ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for consultant_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'consultant_id' && d.id == data.consultant_id.id)
				return { results: [ data.consultant_id ], more: false, elapsed: 0.01 };
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

