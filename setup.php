<?php 
/**
* Creates the table for hit counter.
*/

# Database connection settings #
define('DB_NAME', 'databasename');   
define('DB_USER', 'theusername');
define('DB_PASS', 'thepassword');
define('DB_HOST', 'thehostname');

# Table name settings, feel free to modify these as you wish
# Remember to change tableName and tablePrefix accordingly in hit-counter.php
define('TABLE_NAME', 'hit_count');
define('TABLE_PREFIX', 'hc_');

$pdo = new PDO("mysql:dbname=". DB_NAME. ";host=". DB_HOST, DB_USER, DB_PASS);

$sql = "DROP TABLE IF EXISTS `". TABLE_PREFIX.TABLE_NAME ."`";

$pdo->exec($sql);

$sql = "CREATE TABLE `" . TABLE_PREFIX.TABLE_NAME . "` (
  `route` varchar(255) NOT NULL,
  `hostname` varchar(144) NOT NULL,
  `hit_count` int(11) NOT NULL,
  UNIQUE KEY `route_UNIQUE` (`route`,`hostname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
";

$pdo->exec($sql);

echo "Tables created.\n";
?>