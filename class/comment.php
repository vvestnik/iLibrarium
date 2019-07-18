<?php

/**
 * Class for comment
 * Is used for reading and writing info about comment to DB. Also, for showing the comment
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Comment {
    
    /**
     * id of the comment
     *
     * @var int 
     */
    private $id;
    
    /**
     * Text of the comment
     *
     * @var string 
     */
    private $content;
    
    /**
     * Timestamp of submitting the comment
     *
     * @var DateTime 
     */
    private $date;
    
    /**
     * The commented book
     *
     * @var Book 
     */
    private $book;
    
    /**
     * User, who commented
     *
     * @var User 
     */
    private $user;
    
    /**
     * Returns id of the comment
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns content of the comment
     * 
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }

    /**
     * Returns date and time of submitting the comment
     * 
     * @return \DateTime
     */
    public function getDate(): DateTime {
        return $this->date;
    }

    /**
     * Returns the commented book object
     * 
     * @return \Book
     */
    public function getBook(): Book {
        return $this->book;
    }

    /**
     * Returns the user, who sent the comment
     * 
     * @return \User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * Sets the id of the comment
     * 
     * @param int $id id of the comment
     */
    private function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets content of the comment
     * 
     * @param string $content content of the comment
     */
    private function setContent(string $content) {
        $this->content = $content;
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
     * Sets the commented book
     * 
     * @param Book $book commented book
     */
    private function setBook(Book $book) {
        $this->book = $book;
    }

    /**
     * Set the author of the comment
     * 
     * @param User $user Author of the comment
     */
    private function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1 or 3 parameters.
     * In case of 1 parameter the object is build by Id of the comment from DB
     * In case of 3 parameter the object is build by content, book and user
     * The option with 3 parameters is used when the comment is being created and id is 
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
     * Constructs the object from the DB based on comment id
     * 
     * @param int $id id of the comment
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT content, date, book_id, user_id FROM comment WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setContent($record['content']);
            $this->setDate(new DateTime($record['date']));
            $this->setBook(new Book($record['book_id']));
            $this->setUser(new User($record['user_id']));
        }
        else{
            echo 'There\'s no comment with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs object based on inputs. Writes it to DB
     * 
     * @global type $alert
     * @param string $content content of the comment
     * @param int $book_id commented book
     * @param int $user_id author of the comment
     */
    private function constructByInput(string $content, int $book_id, int $user_id){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setContent(clean($content));
        $this->setBook(new Book(clean($book_id)));
        $this->setUser(new User(clean($user_id)));        
        $link->exec("INSERT INTO comment (content, date, book_id, user_id) VALUES('$this->content', NOW(), '" . $this->book->getId() . "', '" . $this->user->getId() . "')");
        $this->setId($link->lastInsertId());
        $result = $link->query("SELECT date FROM comment WHERE id = '$this->id'");
        $record = $result->fetch();
        $this->setDate(new DateTime($record['date']));
        $connection = null;
    }
    
    /**
     * Returns date in string
     * 
     * @return string
     */
    public function getFormattedDate(): string {
        return $this->getDate()->format('H:i d.m.Y');
    }
    
    /**
     * removes the comment from DB
     */
    public function removeComment(){
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("DELETE FROM comment WHERE id = '" . $this->id . "'");
        $connection = null;
    }
}
