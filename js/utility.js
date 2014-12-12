// function to assign range for datepicker
$(document).ready(function() {

	//$('#departDate').datepicker();
	//$('#returnDate').datepicker();

	$(function() {
		function enableEnd() {
			end.attr('disabled', !this.value.length).datepicker('option', 'minDate', this.value).datepicker('option',
				'maxDate', "+1y");
		}

		var end = $('#returnDate').datepicker();

		$('#departDate').datepicker({
			minDate : '0d',
			maxDate : '+1y',
			onSelect: enableEnd
		}).bind('input', enableEnd);

	});
});
