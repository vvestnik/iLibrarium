<?php

/**
 * Class for connection to DB
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Connection {
    /**
     * User for DB connection
     *
     * @var string 
     */
    private $dbuser = 'root';
    
    /**
     * Password for DB connection
     *
     * @var string 
     */
    private $dbpassword = '';
    
    /**
     * Host for DB connection
     *
     * @var string 
     */
    private $hostname = 'localhost';
    
    /**
     * Database name
     *
     * @var string 
     */
    private $dbname = 'library';
    
    /**
     * Sets the connection to the DB
     * 
     * @return \PDO
     */
    public function connect(): PDO {
        $link = new PDO('mysql:host=' . $this->hostname . ';dbname=' . $this->dbname, $this->dbuser, $this->dbpassword);
        return $link;
    }
}
