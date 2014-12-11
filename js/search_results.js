/**
 * Created by zwg2 on 12/11/14.
 */
$(document).ready(function()
{
	$("#searchResults").validate(
		{

			rules: {

				selectFlightA : {
					required: true
				},
				selectFlightB : {
					required: true
				}
			},

			messages: {
				selectFlightA : {
					onSelect: "Please select outbound trip."
				},
				selectFlightB 		: {
					required: "Please select return trip."
				}
			},




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
