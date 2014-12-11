$(document).ready(function()
{
	$("#selectTravelersForm").validate(
		{

			rules: {
				tFirst : {
					required: true
				},
				tMiddle: {
					required: false
				},
				tLast  : {
					required: true
				},
				tDOB   : {
					required: true
				}

			},

			messages: {
				tFirst : {
					required: "Please enter traveler's first name"
				},
				tMiddle:{
					required: "A middle name is not required"
				},
				tLast  : {
					required: "Please enter traveler's last name"
				},
				tDOB   : {
					required: "Enter traveler's date of birth."
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/processors/createTraveler.php",
						success: function(ajaxOutput) {
							$("#travelerContainer").html(ajaxOutput);
						}
					});
			}
		})
});