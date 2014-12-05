<?php
/**
 * @param $query
 * @return array
 * @throws mysqli_sql_exception
 */

require_once("/etc/apache2/capstone-mysql/przm.php");

class Results {
	public static function db_all($query)
	{
		//Open connection
		$link = MysqliConfiguration::getMysqli();

		$array = array();

		// Execute multi query
		if (($hmm = mysqli_multi_query($link,$query)) !== false)
		{
			do
			{
				$count = 0;
				// Store result set
				if ($result=mysqli_store_result($link))
				{
					while ($row=mysqli_fetch_assoc($result))
					{
						$array[$count] = $row;
						$count++;
					}
					mysqli_free_result($result);
				}
			}
			while (mysqli_next_result($link));
		} else {
			throw(new mysqli_sql_exception("Derp (".$link->errno.")".$link->error));
		}

		//mysqli_close($link);

		return($array);
	}
}
