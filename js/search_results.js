/**
 * Created by zwg2 on 12/11/14.
 */
$(document).ready(function()
{
	$(".searchResults").validate(
		{

			rules: {

				selectOutbound: {
					required: true
				},
				selectInbound  : {
					required: true
				}
			},

			messages: {
				selectOutbound: {
					required: "Please select outbound trip."
				},
				selectInbound  : {
					required: "Please select return trip."
				}
			}
		});

	$(function() {
		if($('#hiddenRadio').is("Yes")) {

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

submitForms = function(){
	document.forms["selectOutbound"].submit();
	document.forms["selectInbound"].submit();
};



