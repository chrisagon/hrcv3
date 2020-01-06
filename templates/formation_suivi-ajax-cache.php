<?php
	$rdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $rdata)));
	$jdata = array_map('to_utf8', array_map('nl2br', array_map('html_attr_tags_ok', $jdata)));
?>
<script>
	$j(function() {
		var tn = 'formation_suivi';

		/* data for selected record, or defaults if none is selected */
		var data = {
			competence_principale: <?php echo json_encode($jdata['competence_principale']); ?>,
			competence_secondaire: <?php echo json_encode(array('id' => $rdata['competence_secondaire'], 'value' => $rdata['competence_secondaire'], 'text' => $jdata['competence_secondaire'])); ?>,
			consultant_id: <?php echo json_encode(array('id' => $rdata['consultant_id'], 'value' => $rdata['consultant_id'], 'text' => $jdata['consultant_id'])); ?>
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for competence_secondaire */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'competence_secondaire' && d.id == data.competence_secondaire.id)
				return { results: [ data.competence_secondaire ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for competence_secondaire autofills */
		cache.addCheck(function(u, d) {
			if(u != tn + '_autofill.php') return false;

			for(var rnd in d) if(rnd.match(/^rnd/)) break;

			if(d.mfk == 'competence_secondaire' && d.id == data.competence_secondaire.id) {
				$j('#competence_principale' + d[rnd]).html(data.competence_principale);
				return true;
			}

			return false;
		});

		/* saved value for consultant_id */
		cache.addCheck(function(u, d) {
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'consultant_id' && d.id == data.consultant_id.id)
				return { results: [ data.consultant_id ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

