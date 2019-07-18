<?php

/**
 * Class for post
 * Is used for reading and writing info about post to DB. Also, for showing the post
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Post {
    
    /**
     * id of the post
     *
     * @var int 
     */
    private $id;
    
    /**
     * Text of the post
     *
     * @var string 
     */
    private $content;
    
    /**
     * Header of the post
     *
     * @var string 
     */
    private $header;
    
    /**
     * Timestamp of submitting the post
     *
     * @var DateTime 
     */
    private $date;
    
    /**
     * User, who wrote the post
     *
     * @var User 
     */
    private $user;
    
    /**
     * Returns id of the post
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns content of the post
     * 
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }
    
    /**
     * Returns header of the post
     * 
     * @return string
     */
    public function getHeader(): string {
        return $this->header;
    }

    /**
     *  Returns content of the post
     * 
     * @return \DateTime
     */
    public function getDate(): DateTime {
        return $this->date;
    }

    /**
     * Returns the author of the post
     * 
     * @return \User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * Sets id of the post
     * 
     * @param int $id id of the post
     */
    private function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets the content of the post
     * 
     * @param string $content content of the post
     */
    private function setContent(string $content) {
        $this->content = $content;
    }
    
    /**
     * Sets header
     * 
     * @param string $header header of the post
     */
    private function setHeader(string $header) {
        $this->header = $header;
    }

    /**
     * Sets submit date and time
     * 
     * @param DateTime $date submit date and time
     */
    private function setDate(DateTime $date) {
        $this->date = $date;
    }

    /**
     * Sets author of the post
     * 
     * @param User $user author of the post
     */
    private function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1 or 3 parameters.
     * In case of 1 parameter the object is build by Id of the post from DB
     * In case of 3 parameter the object is build by header, content and user
     * The option with 3 parameters is used when the post is being created and id is 
     * unknown yet
     */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 3){
            $this->constructByInput(func_get_arg(0), func_get_arg(1), func_get_arg(2));          
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }
    }
    
    /**
     * Constructs th object from the DB based on id given
     * 
     * @param int $id id of the post
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT content, header, date, user_id FROM post WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setContent($record['content']);
            $this->setHeader($record['header']);
            $this->setDate(new DateTime($record['date']));
            $this->setUser(new User($record['user_id']));
        }
        else{
            echo 'There\'s no comment with such Id';
        }
        $connection = null;
    }
    
    /**
     * constructs the object based on the input given, then writes it to the DB
     * 
     * @global type $alert
     * @param string $header header of the post
     * @param string $content content of the post
     * @param int $user_id id of the post author
     */
    private function constructByInput(string $header, string $content, int $user_id){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setHeader(clean($header));
        $this->setContent(clean($content));
        $this->setUser(new User(clean($user_id)));  
        $link->exec("INSERT INTO post (header, content, date, user_id) VALUES('$this->header', '$this->content', NOW(), '" . $this->user->getId() . "')");
        $this->setId($link->lastInsertId());
        $result = $link->query("SELECT date FROM post WHERE id = '$this->id'");
        $record = $result->fetch();
        $this->setDate(new DateTime($record['date']));
        $connection = null;
    }
    
    /**
     * Returns submit date and time in string
     * 
     * @return string 
     */
    public function getFormattedDate(): string {
        return $this->getDate()->format('H:i d.m.Y');
    }
    
    /**
     * Removes the post from DB
     */
    public function removePost(){
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("DELETE FROM post WHERE id = '" . $this->id . "'");
        $connection = null;
    }
}
