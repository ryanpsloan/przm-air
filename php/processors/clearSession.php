<?php
session_start();
unset($_SESSION['priceWithOutboundPath']);
unset($_SESSION['priceWithInboundPath']);
unset($_SESSION['flightIds']);
unset($_SESSION['prices']);
unset($_SESSION['numTravelers']);
unset($_SESSION['outboundFlightCount']);
unset($_SESSION['travelerIds']);

header("Location: https://bootcamp-coders.cnm.edu/~rsloan/przmair/index.php");
?>