$(document).ready(function() {

	$(window).load(function(){
		$(':input','#flightSearchForm  ').not('.form-control,:button, :submit, :reset, :hidden').val('')
	});

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

	$('input:radio[name=""]').change(function(){
		if($(this).val() == 'Yes'){
			alert("test");
		}
	});
});

function isOneWay() {
	if (document.getElementById('oneWay').checked) {
		document.getElementById('returnP').style.display = 'none';
	}
	else{
		document.getElementById('returnP').style.display = 'block';
	}
}