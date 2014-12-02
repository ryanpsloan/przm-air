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
					required: true,
					dateISO: true,
					pattern: /^(\d{4})-(\d{2})-(\d{2})$/
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
					required: "Enter your date of birth.",
					dateISO: "yyyy-mm-dd format please"
				},
				email : {
					required: "Please enter a valid email"
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "editUserProfileProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});
