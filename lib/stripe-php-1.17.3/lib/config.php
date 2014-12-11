<?php
require_once('./lib/Stripe.php');

$stripe = array(
	secret_key      => getenv('sk_test_rjlpx8EvsmEGVk5RinBMV0Jj'),
	publishable_key => getenv('pk_test_y7K8SRtvByY4GmoKMeQ2qmn2')
);

Stripe::setApiKey($stripe['secret_key']);
?>