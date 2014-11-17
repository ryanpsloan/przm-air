<?php
/**
 * Created by PhpStorm.
 * User: zwg2
 * Date: 11/11/14
 * Time: 12:24 PM
 */


$startDate = "2014-12-01 00:00:00";
$formatDateTime = "Y-m-d H:i:s";

//$output2 = "01:42:00";
//$output2String = explode(":",$output2);
//
//$output3 = "10:36:00";
//$output3String = explode(":",$output3);
//$duration = DateInterval::createFromDateString("$output2String[0] hour + $output2String[1] minutes");


//$date->add($duration);
//$date2=DateTime::createFromFormat($formatDateTime, $startDate);
//$date2->add(DateInterval::createFromDateString("1 day"));



$date = DateTimeImmutable::createFromFormat($formatDateTime, $startDate);

$output = array("ABQ","DFW","1:42","8:10","10:52","1","177.08","12:30","15:12","2","212.50","16:50","19:32","3","247.92");

//$basePriceFlight1 = (int) $output[6];
//$basePriceFlight2 = (int) $output[10];
//$basePriceFlight3 = (int) $output[14];
$totalPriceArray = $output[10] + $output[6] + $output[14];
$totalBasePrice = round($output[10] + $output[6] + $output[14], 2);

$tp = round($output[10],4);

$explode2 	= explode(":",$output[2]);
$explode3 	= explode(":",$output[3]);
$explode4 	= explode(":",$output[4]);
$explode7 	= explode(":",$output[7]);
$explode8 	= explode(":",$output[8]);
$explode11	= explode(":",$output[11]);
$explode12 	= explode(":",$output[12]);

//second, use the exploded strings to create the DateIntevals
$duration 			= DateInterval::createFromDateString("$explode2[0] hour + $explode2[1] minutes");
$departureTime1 	= DateInterval::createFromDateString("$explode3[0] hour + $explode3[1] minutes");
$arrivalTime1 		= DateInterval::createFromDateString("$explode4[0] hour + $explode4[1] minutes");
$departureTime2 	= DateInterval::createFromDateString("$explode7[0] hour + $explode7[1] minutes");
$arrivalTime2 		= DateInterval::createFromDateString("$explode8[0] hour + $explode8[1] minutes");
$departureTime3 	= DateInterval::createFromDateString("$explode11[0] hour + $explode11[1] minutes");
$arrivalTime3 		= DateInterval::createFromDateString("$explode12[0] hour + $explode12[1] minutes");

//third, add the relevant intervals to the current date in the loop to make a DATETIME for each flight
$dateTimeDep1 = $date->add($departureTime1);
$dateTimeArr1 = $date->add($arrivalTime1);
$dateTimeDep2 = $date->add($departureTime2);
$dateTimeArr2 = $date->add($arrivalTime2);
$dateTimeDep3 = $date->add($departureTime3);
$dateTimeArr3 = $date->add($arrivalTime3);

var_dump($date);
var_dump($duration);
var_dump($dateTimeDep1);
var_dump($dateTimeArr1);
var_dump($dateTimeDep2);
var_dump($dateTimeArr2);
var_dump($dateTimeDep3);
var_dump($dateTimeArr3);

var_dump($totalPriceArray);
var_dump($totalBasePrice);
var_dump($output[10]);
var_dump($tp);

$dayOfWeek1 = date("N",$date->getTimestamp());
echo $dayOfWeek1;


//add 1 day to immutable $date object
$loopByDay = DateInterval::createFromDateString("1 day");
$date=$date->add($loopByDay);
var_dump($date);



$dayOfWeek2 = date("N",$date->getTimestamp());
echo $dayOfWeek2;

?>
