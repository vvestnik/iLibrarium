<?php

/**
 * Class for store
 * Is used for reading and writing info about store to DB. Also, for showing store
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Store {
    
    /**
     * Id of the store
     *
     * @var int 
     */
    private $id;
    
    /**
     * Name of the store
     *
     * @var string 
     */
    private $name;
    
    /**
     * Address of the store
     *
     * @var string 
     */
    private $address;
    
    /**
     * Sets the id
     * 
     * @param int $id id of the store
     */
    private function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets the Name
     * 
     * @param string $name Name of the store
     */
    private function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Sets the address
     * 
     * @param string $address Address of the store
     */
    private function setAddress(string $address) {
        $this->address = $address;
    }

    /**
     * Returns id of the store
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns name of the store
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns address of the store
     * 
     * @return string
     */
    public function getAddress(): string {
        return $this->address;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1 or 2 parameters.
     * In case of 1 parameter the object is build by Id of the user from DB
     * In case of 2 parameter the object is build by name and address
     * The option with 2 parameters is used when the store is being created and id is 
     * unknown yet
     */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 2){
            $this->constructByInput(func_get_arg(0), func_get_arg(1));
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }
    }
    
    /**
     * Constructs class based on id, gets info from DB
     * 
     * @param int $id id of the store
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT name, address FROM store WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setName($record['name']);
            $this->setAddress($record['address']);            
        }
        else{
            echo 'There\'s no user with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs class based on name and address and writes to DB
     * 
     * @global string $alert
     * @param string $name
     * @param string $address
     */
    private function constructByInput(string $name, string $address){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setName(clean($name));
        $result = $link->query("SELECT * FROM store WHERE name = '$this->name'");
        if(!$result->fetch()){            
            $this->setAddress(clean($address));
            $link->exec("INSERT INTO store (name, address) VALUES('$this->name', '$this->address')");
            $this->setId($link->lastInsertId());            
        }
        else{
            $alert .= 'Store with the name already exists';
        }
        $connection = null;
    }
}
