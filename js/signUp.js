$(document).ready(function()
{
	$("#signUpForm").validate(
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
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/

				},
				confPassword: {
					required: true,
					pattern: /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/,
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
					pattern: "The format of the password entered is not valid"

				},
				confPassword: {
					pattern: "The format of the password entered is not valid",
					equalTo: "Please enter a matching password"
				}

			},

			submitHandler: function(form) {
				$(form).ajaxSubmit(
					{
						type   : "POST",
						url    : "../php/processors/signUpProcessor.php",
						success: function(ajaxOutput) {
							$("#outputArea").html(ajaxOutput);
						}
					});
			}
		})
});