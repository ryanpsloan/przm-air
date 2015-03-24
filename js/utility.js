$(document).ready(function() {

	$(window).load(function(){
		$(':input','#flightSearchForm  ').not('.form-control,:button, :submit, :reset, :hidden').val('')
	});

	$(function() {
		function enableEnd() {
			end.attr('disabled', !this.value.length).datepicker('option', 'minDate', this.value).datepicker('option',
				'maxDate', new Date(2015, 2 - 1, 22));
		}

		var end = $('#returnDate').datepicker();

		$('#departDate').datepicker({
			minDate : new Date(2015, 2 - 1, 9),
			maxDate : new Date(2015, 2 - 1, 15),
			onSelect: enableEnd
		}).bind('input', enableEnd);

		$('#origin').change(function(){
			$('#destination').children("option[value^=" + $(this).val() + "]").hide();
		});

	});

	$('input:radio[name="roundTripOrOneWay"]').change(function(){
			if($('#oneWay').is(":checked")) {

				$('#returnDateP').hide();
			}
			else {

				$('#returnDateP').show();
			}

	});
});

