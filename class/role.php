<?php

/**
 * Class for role
 * Is used for reading and writing info about role to DB. Also, for showing role
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Role {
    
    /**
     * Id of the role
     *
     * @var int 
     */
    private $id;
    
    /**
     * Nmae of the role
     *
     * @var string
     */
    private $roleName;

    /**
     * Sets id of the role
     * 
     * @param int $id id of the role
     */
    private function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets role name
     * 
     * @param string $roleName role name
     */
    private function setRoleName(string $roleName) {
        $this->roleName = $roleName;
    }
    
    /**
     * Returns id of the role
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    
    /**
     * Returns role name
     * 
     * @return string
     */
    public function getRoleName(): string {
        return $this->roleName;
    }

    /**
     * Constructor for role.
     * constructs the object based on DB ('id' method) or input ('name' method).
     * if class is being constructed from the input, writes data to DB if role does not exist. Otherwise constructs class from DB
     * 
     * @global string $alert
     * @param string $method 'id' or 'name'
     * @param type $secondParam if $method = 'id', then int id of the role to be loaded from DB. If $method = 'name', then string name of the role to be written to DB
     * @throws \InvalidArgumentException
     */
    public function __construct(string $method, $secondParam) {
        global $alert;     
        $connection = new Connection();
        $link = $connection->connect();
        if($method == 'id'){           
            $this->setId($secondParam);
            $result = $link->query("SELECT role_name FROM role WHERE id = '$this->id'");
            $record = $result->fetch();
            if($record){
                $this->setRoleName($record['role_name']);
            }
            else{
                $alert .= 'There\'s no role with such Id';
            }
        }
        elseif($method == 'name'){
            $this->setRoleName(clean($secondParam));
            $result = $link->query("SELECT * FROM role WHERE role_name = '$this->roleName'");
            if(!$record = $result->fetch()){
                $link->exec("INSERT INTO role (role_name) VALUES('$this->roleName')");
                $this->setId($link->lastInsertId());
            }
            else{
                $this->setId($record['id']);
            }
        }
        else{
            throw new \InvalidArgumentException('Incorrect method');
        }
        $connection = null;
    }
}
