<?php

/**
 * Class for state
 * Is used for reading and writing info about state to DB. Also, for showing state
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class State {
    
    /**
     * Id of the state
     *
     * @var int 
     */
    private $id;
    
    /**
     * Name of the state
     *
     * @var string 
     */
    private $state_name;

    /**
     * Sets the id
     * 
     * @param int $id id of the state
     */
    private function setId(int $id) {
        $this->id = $id;
    }
    
    /**
     * Sets the name
     * 
     * @param string $state_name Name of the state
     */
    public function setState_name(string $state_name) {
        $this->state_name = $state_name;
    }

    
    /**
     * Returns the id of the state
     * 
     * @return int
     */
    public function getId(): int{
        return $this->id;
    }

    /**
     * Returns the name of the state
     * 
     * @return string
     */
    public function getState_name(): string {
        return $this->state_name;
    }

    /**
     * Constructs a State class
     * 
     * @param int $id id of the state
     */
    public function __construct(int $id) {
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT state_name FROM state WHERE id = $this->id");
        if($record = $result->fetch()){
            $this->setState_name($record['state_name']);
        }
        $connection = null;
    }
}
