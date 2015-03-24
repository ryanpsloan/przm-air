<?php
// static method (singleton) Has no properties, just a static method
class MysqliConfiguration {
  private static $mysqli = null;

  // connect to mySQL
  public static function getMysqli() {
    if(self::$mysqli === null) {
      try {
        // throw exceptions instead of PHP errors
        mysqli_report(MYSQLI_REPORT_STRICT);

        // connect!
        self::$mysqli = new mysqli("localhost", "root", "przmair", "przm");
		  // echo "<p>MysqliConfiguration getMysqli static mysqli przm.php 15</p>";
			//var_dump(self::$mysqli);
      } catch(mysqli_sql_exception $error) {
	throw(new mysqli_sql_exception("Unable to connect to mySQL", 0, $error));
      }
    }
    return(self::$mysqli);
  }

  // concrete object meant to be closed when complete
  public static function getMySqliConcrete() {
    try {
       // throw exceptions instead of PHP errors
       mysqli_report(MYSQLI_REPORT_STRICT);

       // connect!
       $mysqli = new mysqli("localhost", "root", "przmair", "przm");
       return($mysqli);
     } catch(mysqli_sql_exception $error) {
       throw(new mysqli_sql_exception("Unable to connect to mySQL", 0, $error));
     }
  }
}


?>
