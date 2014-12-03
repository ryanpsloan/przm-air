$(document).ready(function()
{
	$("#signInForm").validate(
		{

			rules: {
				email : {
					required: true,
					email   : true
				},
				password: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/

				}
			},

			messages: {
				email : {
					required: "Please enter your email"
				},
				password: {
					required: "Please enter your password"
				}


			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "signInProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});
