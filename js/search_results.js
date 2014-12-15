/**
 * Created by zwg2 on 12/11/14.
 */
$(document).ready(function()
{
	$("#searchResults").validate(
		{

			rules: {

				OutboundPath: {
					required: true
				},
				ReturnPath  : {
					required: true
				}
			},

			messages: {
				OutboundPath: {
					required: "Please select outbound trip."
				},
				ReturnPath  : {
					required: "Please select return trip."
				}
			}
		});

	$('input:radio[name="roundTripOrOneWay"]').change(function(){
		if($('#oneWay').is(":checked")) {

			$('#returnTabs').hide();
		}
		else {

			$('#returnTabs').show();
		}

	});


	$('#outboundSelection').dataTable();
	$('#returnSelection').dataTable();



			//submitHandler: function(form) {
			//	$(form).ajaxSubmit(
			//		{
			//			type   : "POST",
			//			url    : "../php/processors/selected_results_processor.php",
			//			success:
			//
			//
			//				function(ajaxOutput) {
			//				$("#searchOutputArea").html(ajaxOutput);
			//			}
			//		});
			//}
});
