/**
 * Created by zwg2 on 12/11/14.
 */
$(document).ready(function()
{
	$("#searchResults").validate(
		{

			rules: {

				OutboundPath 	: {
					required: true
				},
				ReturnPath 		: {
					required: true
				}
			},

			messages: {
				OutboundPath 	: {
					required: "Please select outbound trip."
				},
				ReturnPath 	: {
					required: "Please select return trip."
				}
			}

			//submitHandler: function(form) {
			//	$(form).ajaxSubmit(
			//		{
			//			type   : "POST",
			//			url    : "../php/processors/search_results_processor.php",
			//			success:
			//
			//
			//				function(ajaxOutput) {
			//				$("#searchOutputArea").html(ajaxOutput);
			//			}
			//		});
			//}


		});
});
