<script>
	$j(function(){
		var tn = 'consultant';

		/* data for selected record, or defaults if none is selected */
		var data = {
			coache_par: { id: '<?php echo $rdata['coache_par']; ?>', value: '<?php echo $rdata['coache_par']; ?>', text: '<?php echo $jdata['coache_par']; ?>' },
			emploi_fonctionnel: { id: '<?php echo $rdata['emploi_fonctionnel']; ?>', value: '<?php echo $rdata['emploi_fonctionnel']; ?>', text: '<?php echo $jdata['emploi_fonctionnel']; ?>' }
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for coache_par */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'coache_par' && d.id == data.coache_par.id)
				return { results: [ data.coache_par ], more: false, elapsed: 0.01 };
			return false;
		});

		/* saved value for emploi_fonctionnel */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'emploi_fonctionnel' && d.id == data.emploi_fonctionnel.id)
				return { results: [ data.emploi_fonctionnel ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

