/**
 * Created by zwg2 on 12/3/14.
 */
$(document).ready(function()
{
	$("#flightSearch").validate(
		{

			rules: {

				/**fixme do i need to require and either/or for roundtrip/one way
				 * fixme what other rules needed for data*/
				origin            : {
					required: true
				},
				destination       : {
					required: true
				},
				departDate        : {
					required: true
				},
				returnDate        : {
					required: true
				},
				flexDatesBoolean  : {
					required: false
				},
				numberOfPassengers: {
					required: true
				},
				minLayover        : {
					required: true
				}
			},

			messages: {
				origin : {
					required: "Please enter a departure city."
				},
				destination: {
					required: "Please enter a destination city."
				},
				departDate  : {
					required: "Please enter a departure date."
				},


				/**fixme this needs to disappear if one-way was selected */
				returnDate   : {
					required: "Please enter a return trip date."
				},


				numberOfPassengers : {
					required: "Please enter the number of passengers flying together on this trip."
				},
				minLayover : {
					required: "Please enter the minimum time you want for any layover between connecting flights."
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "flight_search_processor.php",
						success: function(ajaxOutput) {
							$("#searchOutputArea").html(ajaxOutput);
						}
					});
			}
		})
});
