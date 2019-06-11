<?php $advanced_search_mode = intval($_REQUEST['advanced_search_mode']); ?>

	<input type="hidden" name="advanced_search_mode" value="<?php echo $advanced_search_mode; ?>" id="advanced_search_mode">
	<script>
		$j(function(){
			$j('.btn.search_mode').appendTo('.page-header h1');
			$j('.btn.search_mode').click(function(){
				var mode = parseInt($j('#advanced_search_mode').val());
				$j('#advanced_search_mode').val(1 - mode);
				if(typeof(beforeApplyFilters) == 'function') beforeApplyFilters();
				return true;
			});
		})
	</script>

<?php if($advanced_search_mode){ ?>
	<button class="btn btn-lg btn-success pull-right search_mode" type="submit" name="Filter_x" value="1">Passer en mode recherche simple</button>
	<?php include(dirname(__FILE__) . '/../defaultFilters.php'); ?>
	
<?php }else{ ?>

	<button class="btn btn-lg btn-default pull-right search_mode" type="submit" name="Filter_x" value="1">Passer en mode recherche avancée</button>
			
		<!-- load bootstrap datetime-picker -->
		<link rel="stylesheet" href="resources/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
		<script type="text/javascript" src="resources/moment/moment.min.js"></script>
		<script type="text/javascript" src="resources/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
		
			
			<div class="page-header"><h1>
				<a href="missions_view.php" style="text-decoration: none; color: inherit;">
					<img src="resources/table_icons/document_comment_above.png"> 					Missions en cours</a>
			</h1></div>

		
<div class="row vspacer-lg" style="border-bottom: dotted 2px #DDD;" >
	
	<div class="hidden-xs hidden-sm col-md-3 vspacer-lg text-right"><label for="">Date fin</label></div>
	<div class="hidden-md hidden-lg col-xs-12 vspacer-lg"><label for="">Date fin</label></div>
	
	
	<div class="col-xs-3 col-md-1 vspacer-lg text-center">Entre</div>
	
	<input type="hidden" name="FilterAnd[1]" value="and">
	<input type="hidden" name="FilterField[1]" value="5">   
	<input type="hidden" name="FilterOperator[1]" value="greater-than-or-equal-to">
	
	<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
		<input type="text"  class="form-control" id="from-date_5"  name="FilterValue[1]" value="<?php echo htmlspecialchars($FilterValue[1]); ?>" size="10">
	</div>

		<div class="col-xs-3 col-md-1 text-center vspacer-lg"> et </div>
	
	<input type="hidden" name="FilterAnd[2]" value="and">
	<input type="hidden" name="FilterField[2]" value="5">  
	<input type="hidden" name="FilterOperator[2]" value="less-than-or-equal-to">
	
	<div class="col-xs-9 col-md-3 col-lg-2 vspacer-md">
		<input type="text" class="form-control" id="to-date_5" name="FilterValue[2]" value="<?php echo htmlspecialchars($FilterValue[2]); ?>" size="10">
	</div>
	
	
	<div class="col-xs-3 col-xs-offset-9 col-md-offset-0 col-md-1">
		<button type="button" class="btn btn-default vspacer-md btn-block" title='Clear fields' onclick="clearFilters($j(this).parent());" ><span class="glyphicon glyphicon-trash text-danger"></button>
	</div>

	</div>

		
<script>
	$j(function(){
		//date
		$j("#from-date_5 , #to-date_5 ").datetimepicker({
			format: 'DD/MM/YYYY'   //config
		});

		$j("#from-date_5" ).on('dp.change' , function(e){
			date = moment(e.date).add(1, 'month');  
			$j("#to-date_5 ").val(date.format('DD/MM/YYYY')).data("DateTimePicker").minDate(e.date);
		});
	});
</script>

	
			<!-- ########################################################## -->
					<!-- filter actions -->
		<div class="row">
			<div class="col-md-3 col-md-offset-3 col-lg-offset-4 col-lg-2 vspacer-lg">
				<input type="hidden" name="apply_sorting" value="1">
				<button type="submit" id="applyFilters" onclick="beforeApplyFilters(event);return true;" class="btn btn-success btn-block btn-lg"><i class="glyphicon glyphicon-ok"></i> Appliquer le filtre</button>
			</div>
						<div class="col-md-3 col-lg-2 vspacer-lg">
				<button onclick="beforeCancelFilters();" type="submit" id="cancelFilters" class="btn btn-warning btn-block btn-lg"><i class="glyphicon glyphicon-remove"></i> Annuler</button>
			</div>
		</div>

		<script>
			$j(function(){
				//stop event if it is already bound
				$j(".numeric").off("keydown").on("keydown", function (e) {
					// Allow: backspace, delete, tab, escape, enter and .
					if ($j.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
						// Allow: Ctrl+A, Command+A
						(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
						// Allow: home, end, left, right, down, up
						(e.keyCode >= 35 && e.keyCode <= 40)) {
							// let it happen, don't do anything
							return;
					}
					// Ensure that it is a number and stop the keypress
					if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
						e.preventDefault();
					}
				});                
			});
			
			/* function to handle the action of the clear field button */
			function clearFilters(elm){
				var parentDiv = $j(elm).parent(".row ");
				//get all input nodes
				inputValueChildren = parentDiv.find("input[type!=radio][name^=FilterValue]");
				inputRadioClildren = parentDiv.find("input[type=radio][name^=FilterValue]");
				
				//default input nodes ( text, hidden )
				inputValueChildren.each(function( index ) {
					$j( this ).val('');
				});
				
				//radio buttons
				inputRadioClildren.each(function( index ) {
					$j( this ).removeAttr('checked');

					//checkbox case
					if ($j( this ).val()=='') $j(this).attr("checked", "checked").click();
				});
				
				//lookup and select dropdown
				parentDiv.find("div[id$=DropDown],div[id^=filter_]").select2("val", "");

				//for lookup
				parentDiv.find("input[id^=lookupoperator_]").val('equal-to');

			}
			
			function checkboxFilter(elm) {
				if (elm.value == "null") {
					$j("#" + elm.className).val("is-empty");
				} else {
					$j("#" + elm.className).val("equal-to");
				}
			}
			
			/* funtion to remove unsupplied fields */
			function beforeApplyFilters(event){
			
				//get all field submitted values
				$j(":input[type=text][name^=FilterValue],:input[type=hidden][name^=FilterValue],:input[type=radio][name^=FilterValue]:checked").each(function( index ) {
					  
					//if type=hidden  and options radio fields with the same name are checked, supply its value
					if ( $j( this ).attr('type')=='hidden' &&  $j(":input[type=radio][name='"+$j( this ).attr('name')+"']:checked").length >0 ){
						return;
					}
					  
					  //do not submit fields with empty values
					if ( !$j( this ).val()){
					  var fieldNum =  $j(this).attr('name').match(/(\d+)/)[0];
					  $j(":input[name='FilterField["+fieldNum+"]']").val('');
					 
					  };
				});

			}
			
			function beforeCancelFilters(){
				

				//other fields
				$j('form')[0].reset();

				//lookup case ( populate with initial data)
				$j(":input[class='populatedLookupData']").each(function(){
				  

					$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
					if ($j(this).val()== '<None>'){
						$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('is-empty');
					}else{
						$j(this).parent(".row ").find('input[id^="lookupoperator"]').val('equal-to');
					}
						
				})

				//options case ( populate with initial data)
				$j(":input[class='populatedOptionsData']").each(function(){
				   
					$j(":input[name='FilterValue["+$j(this).attr('name')+"]']").val($j(this).val());
				})


				//checkbox, radio options case
				$j(":input[class='checkboxData'],:input[class='optionsData'] ").each(function(){
					var filterNum = $j(this).val();
					var populatedValue = eval("filterValue_"+filterNum);                  
					var parentDiv = $j(this).parent(".row ");

					//check old value
					parentDiv.find("input[type=radio][value='"+populatedValue+"']").attr('checked', 'checked').click();
				
				})

				//remove unsuplied fields
				beforeApplyFilters();

				return true;
			}
		</script>
		
		<style>
			.form-control{ width: 100% !important; }
			.select2-container, .select2-container.vspacer-lg{ max-width: unset !important; width: 100%; margin-top: 0 !important; }
		</style>


	

<?php } ?>