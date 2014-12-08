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
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/

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
						url    : "../php/processors/signInProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
							// wait for a time and then redirect
							setInterval(function () {location.href = '../index.php'}, 3000);
							//redirect here
						}
					});
			}
		})
});
