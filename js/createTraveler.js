$(document).ready(function()
{
	$("#createTravelerForm").validate(
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
				first : {
					required: "Please enter traveler's first name"
				},
				middle:{
					required: "A middle name is not required"
				},
				last  : {
					required: "Please enter traveler's last name"
				},
				dob   : {
					required: "Enter traveler's date of birth."
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/processors/createTraveler.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});