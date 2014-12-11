// This function is just used to display error messages on the page.
// Assumes there's an element with an ID of "payment-errors".
function reportError(msg) {
	// Show the error in the form:
	$('#payment-errors').text(msg).addClass('alert alert-error');
	// re-enable the submit button:
	$('#submitBtn').prop('disabled', false);
	return false;
}

// validate the form using jQuery
$(document).ready(function() {
	// Watch for a form submission:
	$("#paymentForm").submit(function(event) {

		// Flag variable:
		var error = false;

		// disable the submit button to prevent repeated clicks:
		$('#submitBtn').attr("disabled", "disabled");

		// Get the values:
		var ccNum = $('.cardNumber').val(), cvcNum = $('.cardCvc').val(), expMonth = $('.cardExpiryMonth').val(), expYear = $('.cardExpiryYear').val();

		// Validate the number:
		if (!Stripe.validateCardNumber(ccNum)) {
			error = true;
			reportError('The credit card number appears to be invalid.');
		}

		// Validate the CVC:
		if (!Stripe.validateCVC(cvcNum)) {
			error = true;
			reportError('The CVC number appears to be invalid.');
		}

		// Validate the expiration:
		if (!Stripe.validateExpiry(expMonth, expYear)) {
			error = true;
			reportError('The expiration date appears to be invalid.');
		}

		// Validate other form elements
		// setup the form validation
//	$("#paymentForm").validate({
		// debug option in jQuery's validator
//		debug   : true,
		// rules dictate what is (in)valid
//		rules   : {
//			firstName: {
//				required: true
//			},

//			lastName: {
//				required: true
//			},

//			addressLine1: {
//				required: true
//			},

//			addressCity: {
//				required: true
//			},

//			addressState: {
//				required: true
//			},

//			addressZip: {
//				required: true
//			}

//		},

		// messages are what are displayed to the user
//		messages: {
//			firstName      : "Please enter fist name.",
//			lastName       : "Please enter last name.",
//			addressLine1   : "Please enter address.",
//			addressCity    : "Please enter city.",
//			addressState   : "Please enter state.",
//			addressZip     : "Please enter zip code."
//		}
//	}
		// Check for errors:
		if (!error) {

			// Get the Stripe token:
			Stripe.createToken({
				number: ccNum,
				cvc: cvcNum,
				exp_month: expMonth,
				exp_year: expYear
			}, stripeResponseHandler);

		}

		// Prevent the form from submitting:
		return false;



	});
});

// Function handles the Stripe response:
function stripeResponseHandler(status, response) {

	// Check for an error:
	if (response.error) {

		reportError(response.error.message);

	} else { // No errors, submit the form:

		var f = $("#payment-form");

		// Token contains id, last4, and card type:
		var token = response['id'];

		// Insert the token into the form so it gets submitted to the server
		f.append("<input type='hidden' name='stripeToken' value='" + token + "' />");

		// Submit the form:
		f.get(0).submit();

	}

} // End of stripeResponseHandler() function.

