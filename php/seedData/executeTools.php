<?php
require_once("/etc/apache2/capstone-mysql/przm.php");
$mysqli = MysqliConfiguration::getMysqli();
$zones = timezone_identifiers_list();

foreach ($zones as $zone)
{
	$zone = explode('/', $zone); // 0 => Continent, 1 => City

	// Only use "friendly" continent names
	if ($zone[0] == 'Africa' || $zone[0] == 'America' || $zone[0] == 'Antarctica' || $zone[0] == 'Arctic' || $zone[0] == 'Asia' || $zone[0] == 'Atlantic' || $zone[0] == 'Australia' || $zone[0] == 'Europe' || $zone[0] == 'Indian' || $zone[0] == 'Pacific')
	{
		if (isset($zone[1]) != '')
		{
			$locations[$zone[0]][$zone[0]. '/' . $zone[1]] = str_replace('_', ' ', $zone[1]); // Creates array(DateTimeZone => 'Friendly name')
		}
	}
}
/*require_once("tools.php");
$baseDate = "2014-12-01";
$fileName = "weekDay01.csv";
readCSV($mysqli, $fileName,$baseDate,25,5);
echo"<p> weekDay seed data set to flight </p><br>";


$baseDate = "2014-12-06";
$fileName = "weekEnd01.csv";
$totalSeats = 25;
$numDays = 2;
readCSV($mysqli, $fileName,$baseDate,$totalSeats,$numDays);
echo"<p> weekEnd seed data set to flight </p><br>";*/

?>