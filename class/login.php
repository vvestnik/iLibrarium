<?php

/**
 * Class for login
 * Login process
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Login {
    
    /**
     * Entered username
     *
     * @var string 
     */
    private $nickname;
    
    /**
     * Entered password
     *
     * @var string 
     */
    private $password;
    
    /**
     * Sets username
     * 
     * @param string $nickname entered username
     */
    private function setNickname(string $nickname) {
        $this->nickname = $nickname;
    }

    /**
     * Sets password
     * 
     * @param string $password entered password
     */
    private function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * Constructs the class
     * 
     * @param string $nickname entered username
     * @param string $password entered password
     */
    public function __construct(string $nickname, string $password) {        
        $this->setNickname($nickname);
        $this->setPassword($password);
    }
    
    /**
     * Checks if username/password combination is correct.
     * Returns false if the combo is incorrect
     * Returns id of the user if correct
     * 
     * @return boolean
     */
    public function authUser() {
        if (empty($this->nickname) || empty($this->password)) {
            return false;
        }
        $connection = new Connection();
        $link = $connection->connect();
        $count = $link->query("SELECT count(*) FROM user WHERE nickname = '$this->nickname'")->fetchColumn();         
        if ($count == 1) {
            $result = $link->query("SELECT password, id FROM user WHERE nickname = '$this->nickname'");
            $record = $result->fetch();
            $check = password_verify($this->password, $record['password']);
            $connection = null;
            if($check){
                return $record['id'];
            }
            else{
                return false;
            }
        } else {
            return false;
        }        
    }
}
