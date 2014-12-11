$(document).ready(function()
{
	$("#flightSearch").validate(
		{

			rules: {

				roundTripOrOneWay : {
					required: true
				},
				origin            : {
					required: true,
					pattern: /[A-Za-z]{3}/
				},
				destination       : {
					required: true,
					pattern: /[A-Za-z]{3}/
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
				roundTripOrOneWay : {
					onSelect: "Please enter one-way or round trip."
				},
				origin 		: {
					required: "Please enter a origin city."
				},
				destination	: {
					required: "Please enter a destination city."
				},
				departDate  : {
					required: "Please enter a departure date."
				},


				///**fixme this needs to disappear if one-way was selected **/
				returnDate   : {
					reqired : "Please enter a return trip date."
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
						url    : "php/processors/flight_search_processor.php",
						success: function(ajaxOutput) {
							$("#searchOutputArea").html(ajaxOutput);
						}
					});
			}
		});


	//toggle or hide unhide
	//$("#oneWay").onclick();

});
