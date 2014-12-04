$(document).ready(function()
{
	$("#signUp").validate(
		{

			rules: {
				first : {
					required: true,
					pattern: /^[a-zA-Z]+$/
				},
				middle: {
					required: false,
					pattern: /^[a-zA-Z]+$/
				},
				last  : {
					required: true,
					pattern: /^[a-zA-Z]+$/
				},
				dob   : {
					required: true
				},
				email : {
					required: true,
					email   : true
				},
				password: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/,
					equalTo: "#confPassword"
				},
				confPassword: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,12}$/,
					equalTo: "#password"
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
				},
				password: {
					required: "Please enter a password of a minimum of 5 characters using at least one capital, one letter, and one digit, no special characters, maximum 12"

				},
				confPassword: {
					required: "Please enter a password of a minimum of 5 characters using at least one capital, one letter, and one digit, no special characters, maximum 12"
				}

			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "signUpProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});