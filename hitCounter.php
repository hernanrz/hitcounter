<?php 
/**
* Class that keeps track of how many times a
* route has been accessed using PDO and MySQL
*
* (i.e, how many times a php page has been loaded) 
*/
class hitCounter {
  
  /**
  * The route that is being accessed
  */
  public $currentRoute;
  
  /**
  * Hostname of the server, loaded from the host http header.
  * if the header is not present then localhost is used
  */
  public $currentHostname = "localhost";
  
  /**
  * The PDO link passed to the class
  */
  private $pdo;
  
  /**
  * Optional table name prefix used when setting up the database
  */
  private $tablePrefix = "hc_";
  
  /**
  * The name of the table where the hit count data is going to be stored
  */
  private $tableName = "hit_count";
  
  function __construct(PDO $pdoLink) {
    $this->pdo = $pdoLink;
    
    $this->tableName = $this->tablePrefix . $this->tableName;
  }
  
  /**
  * Sets the hostname and the current route and then registers it in the database
  */
  public function init() {
    return $this->setHostnameFromGlobals()
                ->setRouteFromGlobals()
                ->registerHit($this->currentRoute, $this->currentHostname);
  }
  
  /**
  * Registers a hit using the given route and hostname.
  * If the page was already accessed before then the
  * hit count is increased by one
  */
  public function registerHit($route, $hostname) {
    $sql = "INSERT INTO " . $this->tableName. " (route, hostname, hit_count) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE hit_count = hit_count + 1";
    
    $statement = $this->pdo->prepare($sql);
    
    $parameters = [
      $route,
      $hostname,
      1
    ];
    
    $statement->execute($parameters);
    
    return $this;
  }
  
  /**
  * Gets the amount of hits for the given route and hostname
  * @return int the amount of hits, 0 if no entries were found
  */
  public function getHitsForRoute($route, $hostname) {
    $sql = "SELECT hit_count FROM ". $this->tableName . " WHERE route = ? AND hostname = ?";

    $statement = $this->pdo->prepare($sql);
    
    $parameters = [
      $route,
      $hostname
    ];
    
    $statement->execute($parameters);
    
    if($row = $statement->fetch()) {
      return $row["hit_count"];
    }
    
    return 0;
  }
  
  /**
  * Gets the amount of hits for the current route and hostname
  */
  public function getHits() {
    return $this->getHitsForRoute($this->currentRoute, $this->currentHostname);
  }
  
  /**
  * Sets the route being accessed
  */
  private function setRouteFromGlobals() {
    $this->currentRoute = $_SERVER["REQUEST_URI"];
    
    return $this;  
  }
  
  /**
  * Sets the host being accessed, if there's a http host header present
  */
  private function setHostnameFromGlobals() {  
    if(isset($_SERVER["HTTP_HOST"])) {
      $this->currentHostname = $_SERVER["HTTP_HOST"];
    }
    
    return $this;
  }
};
?>