PHP Hit Counter
=====
A simple hit counter using PDO and MySQL.

Written because, why not?

How it works
-------
1. First you'll need modify the setup script and change the following lines to your actual database connection settings.
  ```php
  <?php
  # Database connection settings #
  define('DB_NAME', 'databasename');   
  define('DB_USER', 'theusername');
  define('DB_PASS', 'thepassword');
  define('DB_HOST', 'thehostname');
  ```

  then you can just execute the script by running
  ```
  $ php setup.php
  ```
  1.1 If you don't feel like doing all of that, you can execute the SQL below and you're set.
  ```sql
  DROP TABLE IF EXISTS `hc_hit_count`;

  CREATE TABLE `hc_hit_count` (
    `route` varchar(255) NOT NULL,
    `hostname` varchar(144) NOT NULL,
    `hit_count` int(11) NOT NULL,
    UNIQUE KEY `route_UNIQUE` (`route`,`hostname`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
  ```
2. Then simply include the class and initialize it
```php
<?php
include "hitCounter.php";
$hc = new hitCounter($yourPDOconnection);
$hc->init();
?>
```
3. That's it! it will automatically record every time a route is accessed

Documentation
------
Since it's one file with less that 200 lines of code, there isn't much to be documented.
But here's a list of the public methods: 
#### Public methods

##### hitCounter::\__construct(PDO $pdoLink)
  Creates a hitCounter instance. Takes a PDO Object as a parameter and sets up the table name to be used.

##### *public* hitCounter::init()
  Sets the current hostname and route being accessed using the php globals and then registers the hit on the database.
  
##### *public* hitCounter::registerHit($route, $hostname)
  Registers a hit using the given route and hostname. If page was already accessed before then the hit count is increased by one instead of adding a new entry in the table.

##### *public* hitCounter::getHits() 
  Returns the amount of hits for the current route and hostname.
  
##### *public* hitCounter::getHitsForRoute($route, $hostname)
  Returns the amount of hits for the given route and hostname.
  
#### Class properties 

##### *public* string hitCounter::$currentRoute 
The route that is being accessed
##### *public* string hitCounter::$currentHostname
Hostname of the server, loaded from the host http header. if the header is not present then localhost is used
##### *private* PDO hitCounter::$pdo 
The PDO object passed to the constructor
##### *private* string hitCounter::$tablePrefix
Optional table name prefix used when setting up the database, default is "hc_".
##### *private* string hitCounter::$tableName
The name of the table where the hit count data is going to be stored, default is "hit_count".