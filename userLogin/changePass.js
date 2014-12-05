$(document).ready(function()
{
	$("#changePass").validate(
		{

			rules: {
				password: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/

				},
				confPassword: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/,
					equalTo: '#password'

				}
			},

			messages: {
				password: {
					required: "Please enter your password"

				},
				confPassword: {
					required: "Please re-enter your password for confirmation",
					equalTo: "Please confirm your password"
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "changePassProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});
