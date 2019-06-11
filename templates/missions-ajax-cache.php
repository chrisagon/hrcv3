<script>
	$j(function(){
		var tn = 'missions';

		/* data for selected record, or defaults if none is selected */
		var data = {
			id_consultant: { id: '<?php echo $rdata['id_consultant']; ?>', value: '<?php echo $rdata['id_consultant']; ?>', text: '<?php echo $jdata['id_consultant']; ?>' },
			rattache_a_cv: { id: '<?php echo $rdata['rattache_a_cv']; ?>', value: '<?php echo $rdata['rattache_a_cv']; ?>', text: '<?php echo $jdata['rattache_a_cv']; ?>' },
			client: { id: '<?php echo $rdata['client']; ?>', value: '<?php echo $rdata['client']; ?>', text: '<?php echo $jdata['client']; ?>' }
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

