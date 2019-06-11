<script>
	$j(function(){
		var tn = 'competences_individuelles';

		/* data for selected record, or defaults if none is selected */
		var data = {
			rattache_a_mission: { id: '<?php echo $rdata['rattache_a_mission']; ?>', value: '<?php echo $rdata['rattache_a_mission']; ?>', text: '<?php echo $jdata['rattache_a_mission']; ?>' },
			competence_mis_en_oeuvre: { id: '<?php echo $rdata['competence_mis_en_oeuvre']; ?>', value: '<?php echo $rdata['competence_mis_en_oeuvre']; ?>', text: '<?php echo $jdata['competence_mis_en_oeuvre']; ?>' },
			niveau: { id: '<?php echo $rdata['niveau']; ?>', value: '<?php echo $rdata['niveau']; ?>', text: '<?php echo $jdata['niveau']; ?>' },
			consultant_id: { id: '<?php echo $rdata['consultant_id']; ?>', value: '<?php echo $rdata['consultant_id']; ?>', text: '<?php echo $jdata['consultant_id']; ?>' }
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for rattache_a_mission */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'rattache_a_mission' && d.id == data.rattache_a_mission.id)
				return { results: [ data.rattache_a_mission ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for competence_mis_en_oeuvre */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'competence_mis_en_oeuvre' && d.id == data.competence_mis_en_oeuvre.id)
				return { results: [ data.competence_mis_en_oeuvre ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for niveau */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'niveau' && d.id == data.niveau.id)
				return { results: [ data.niveau ], more: false, elapsed: 0.01 };
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

