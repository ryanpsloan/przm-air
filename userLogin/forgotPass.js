$(document).ready(function()
{
	$("#forgotPass").validate(
		{

			rules: {
				email : {
					required: true,
					email   : true
				},
				password: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/

				},
				confPassword: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/

				}
			},

			messages: {
				email : {
					required: "Please enter your email"
				},
				password: {
					required: "Please enter your password",
					equalTo: '#confPassword'
				},
				confPassword: {
					required: "Please re-enter your password for confirmation",
					equalTo: '#password'
				}
			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "forgotPassProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});