<script>
	$j(function(){
		var tn = 'curriculum_vitae';

		/* data for selected record, or defaults if none is selected */
		var data = {
			du_consultant: { id: '<?php echo $rdata['du_consultant']; ?>', value: '<?php echo $rdata['du_consultant']; ?>', text: '<?php echo $jdata['du_consultant']; ?>' }
		};

		/* initialize or continue using AppGini.cache for the current table */
		AppGini.cache = AppGini.cache || {};
		AppGini.cache[tn] = AppGini.cache[tn] || AppGini.ajaxCache();
		var cache = AppGini.cache[tn];

		/* saved value for du_consultant */
		cache.addCheck(function(u, d){
			if(u != 'ajax_combo.php') return false;
			if(d.t == tn && d.f == 'du_consultant' && d.id == data.du_consultant.id)
				return { results: [ data.du_consultant ], more: false, elapsed: 0.01 };
			return false;
		});

		cache.start();
	});
</script>

