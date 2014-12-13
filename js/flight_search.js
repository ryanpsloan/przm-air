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
					//date: true
				},
				returnDate        : {
					required: false
					//date: true
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
					required: "Please enter one-way or round trip."
				},
				origin 		: {
					required: "Please enter a three letter airport code."
				},
				destination	: {
					required: "Please enter a three letter airport code."
				},
				departDate  : {
					required: "Please enter a departure date."
				},
				returnDate   : {
					required : {
						depends: function(element){
							if($('#departDate').val().not('empty')){
								return "Please enter a return trip date."
							}
						}
					}
				},
				numberOfPassengers : {
					required: "Please enter the number of passengers flying together on this trip."
				},
				minLayover : {
					required: "Please enter the minimum time you want for any layover between connecting flights."
				}
			},

			submitHandler: function(form) {
				// some other code
				// maybe disabling submit button
				// then:
				$(form).submit();
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
