<script>
	$j(function(){
		var tn = 'competences_ref';

		/* data for selected record, or defaults if none is selected */
		var data = {
			domaine_principal: { id: '<?php echo $rdata['domaine_principal']; ?>', value: '<?php echo $rdata['domaine_principal']; ?>', text: '<?php echo $jdata['domaine_principal']; ?>' }
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for domaine_principal */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'domaine_principal' && d.id == data.domaine_principal.id)
				return { results: [ data.domaine_principal ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

