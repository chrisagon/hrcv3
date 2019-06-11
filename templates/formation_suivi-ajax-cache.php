<script>
	$j(function(){
		var tn = 'formation_suivi';

		/* data for selected record, or defaults if none is selected */
		var data = {
			competence_principale: '<?php echo $jdata['competence_principale']; ?>',
			competence_secondaire: { id: '<?php echo $rdata['competence_secondaire']; ?>', value: '<?php echo $rdata['competence_secondaire']; ?>', text: '<?php echo $jdata['competence_secondaire']; ?>' },
			consultant_id: { id: '<?php echo $rdata['consultant_id']; ?>', value: '<?php echo $rdata['consultant_id']; ?>', text: '<?php echo $jdata['consultant_id']; ?>' }
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for competence_secondaire */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'competence_secondaire' && d.id == data.competence_secondaire.id)
				return { results: [ data.competence_secondaire ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for competence_secondaire autofills */
		cache.addCheck(function(u, d){
			if(u != tn + '_autofill.php') return false;

			for(var rnd in d) if(rnd.match(/^rnd/)) break;

			if(d.mfk == 'competence_secondaire' && d.id == data.competence_secondaire.id){
				$j('#competence_principale' + d[rnd]).html(data.competence_principale);
				return true;
			}

			return false;
		});

		/* saved value for consultant_id */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'consultant_id' && d.id == data.consultant_id.id)
				return { results: [ data.consultant_id ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

