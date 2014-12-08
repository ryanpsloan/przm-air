$(document).ready(function()
{
	$("#editProfile").validate(
		{

			rules: {
				first : {
					required: true
				},
				middle: {
					required: false
				},
				last  : {
					required: true
				},
				dob   : {
					required: true
				},
				email : {
					required: true,
					email   : true
				}
			},

			messages: {
				first : {
					required: "Please enter your first name"
				},
				middle:{
					required: "A middle name is not required"
				},
				last  : {
					required: "Please enter your last name"
				},
				dob   : {
					required: "Enter your date of birth."
				},
				email : {
					required: "Please enter a valid email"
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/processors/editUserProfileProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});
