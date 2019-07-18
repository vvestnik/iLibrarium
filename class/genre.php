<?php

/**
 * Class for genre
 * Is used for reading and writing info about genre to DB. Also, for showing genre
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Genre {
    
    /**
     * Id of the genre
     *
     * @var int 
     */
    private $id;
    
    /**
     * Nmae of the genre
     *
     * @var string
     */
    private $genreName;

    /**
     * Sets id of the genre
     * 
     * @param int $id id of the genre
     */
    private function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets Genre name
     * 
     * @param string $genreName genre name
     */
    private function setGenreName(string $genreName) {
        $this->genreName = $genreName;
    }
    
    /**
     * Returns id of the genre
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }
    
    /**
     * Returns genre name
     * 
     * @return string
     */
    public function getGenreName(): string {
        return $this->genreName;
    }

    /**
     * Construct a Genre from DB with method 'id'
     * or from input with method 'name'
     * 
     * @global string $alert
     * @param string $method 'id' or 'name'
     * @param type $secondParam id from DB or name to write to DB
     * @throws \InvalidArgumentException
     */
    public function __construct(string $method, $secondParam) {   
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        if($method == 'id'){          
            $this->setId($secondParam);
            $result = $link->query("SELECT name FROM genre WHERE id = '$this->id'");
            $record = $result->fetch();
            if($record){
                $this->setGenreName($record['name']);
            }
            else{
                $alert .= 'There\'s no genre with such Id';
            }
        }
        elseif($method == 'name'){
            $this->setGenreName(clean($secondParam));
            $result = $link->query("SELECT * FROM genre WHERE name = '$this->genreName'");
            if(!$result->fetch()){
                $link->exec("INSERT INTO genre (name) VALUES('$this->genreName')");
                $this->setId($link->lastInsertId());
            }
            else{
                $alert .= 'Role already exists';
            }
        }
        else{
            throw new \InvalidArgumentException('Incorrect method');
        }
        $connection = null;
    }
}
