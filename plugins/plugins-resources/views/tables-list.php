<?php if(!isset($this)) die("You can't call this file directly."); ?>

<div id="<?php echo $list_id; ?>" class="<?php echo $classes; ?> list-group">

	<?php
		for( $i= 0 ; $i < count ($axp->table) ; $i++ ){ ?>
			<a href="#" class="list-group-item" data-table-index="<?php echo $i; ?>">
				<?php if (!empty($axp->table[$i]->tableIcon)){ ?>
					<img
						src="../plugins-resources/table_icons/<?php echo $axp->table[$i]->tableIcon ;?>"
						alt="<?php echo $axp->table[$i]->tableIcon ; ?>">
				<?php } ?>
				<?php echo $axp->table[$i]->caption; ?>
			</a>
			<?php
		}
	?>

</div>

<style>
	#<?php echo $list_id; ?>{
		min-height: 150px;
		overflow-Y:scroll;
	}
</style>

<script>
	$j(function(){
		$j('#<?php echo $list_id; ?> .list-group-item').click(function(){
			$j("#<?php echo $list_id; ?> a").removeClass("active");
			$j(this).addClass("active");
			
			<?php echo $click_handler; ?>($j(this).data('table-index'));
			
			return false;
		});
		
		<?php if($select_first_table){ ?>
			$j('#<?php echo $list_id; ?> > a').first().click();
		<?php } ?>
	})
</script>










