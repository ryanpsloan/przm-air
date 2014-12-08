$(document).ready(function()
{
	$("#changePass").validate(
		{

			rules: {
				oldPassword: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/
				},
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
				oldPassword: {
					required: "Please enter your old password"
				},
				password: {
					required: "Please enter your new password"

				},
				confPassword: {
					required: "Please re-enter your password for confirmation",
					equalTo: "New Passwords do not match"
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/processors/changePassProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
							setInterval(function () {location.href = '../index.php'}, 3000);
						}
					});
			}
		})
});
