<?php

/**
 * Class for book instance
 * Extends Book class, used for reservations
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class BookInstance extends Book{
    
    /**
     * Id of the specific book (for example, two Fellowship of the Ring books will have same id but different instance id)
     * 
     * @var int 
     */
    private $instanceId;
    
    /**
     * State of the specific book
     * 
     * @var State 
     */
    private $state;
    
    /**
     * The store, where the book is located, if not borrowed.
     * 
     * @var Store 
     */
    private $store;
    
    /**
     * Sets the id of the specified book
     * 
     * @param int $instanceId Id of the specified book
     */
    private function setInstanceId(int $instanceId) {
        $this->instanceId = $instanceId;
    }

    /**
     * Sets the state of the book
     * 
     * @param State $state State of book
     */
    public function setState(State $state) {
        $this->state = $state;
    }

    /**
     * Sets the store, where the book is located
     * 
     * @param Store $store Store
     */
    private function setStore(Store $store) {
        $this->store = $store;
    }

    /**
     * Returns id of the specified book
     * 
     * @return int
     */
    public function getInstanceId(): int {
        return $this->instanceId;
    }
    
    /**
     * Returns state of the specified book
     * 
     * @return \State
     */
    public function getState(): State {
        return $this->state;
    }

    /**
     * Returns store, where book is located
     * 
     * @return \Store
     */
    public function getStore(): Store {
        return $this->store;
    }
    
    /**
    * Constructor of the class
    * 
    * Accepts 1 or 2 parameters.
    * In case of 1 parameter the object is build by Id of the image from DB
    * In case of 2 parameter the object is build by id of the book and the store 
    * The option with 2 parameters is used when the instance is randomly picked from the store and instance id is 
    * unknown yet
    */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructByInstanceId(func_get_arg(0));
        }
        elseif($numargs == 2){
            $this->constructByBookId(func_get_arg(0), func_get_arg(1));           
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }  
    }

    /**
     * Constructs a random book instance of the given book in the given store
     * 
     * @param int $id
     * @param Store $store
     */
    public function constructByBookId(int $id, Store $store){
        parent::__construct($id);
        $this->setStore($store);
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT id FROM book_instance WHERE book_id = '$this->id' AND state_id = 1 AND store_id = '" . $this->store->getId() . "' ORDER BY RAND() LIMIT 1");
        $record = $result->fetch();
        if($record){
            $this->setInstanceId($record['id']);
            $this->setState(new State(1));
        }
        else{
            echo 'No such books in the store';
        }
        $connection = null;
    }
    
    /**
     * Constructs the specified book instance based on instance id given
     * 
     * @param int $id
     */
    public function constructByInstanceId(int $id){
        $this->setInstanceId($id);
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT state_id, book_id, store_id FROM book_instance WHERE id = '$this->instanceId'");
        $record = $result->fetch();
        $this->setState(new State($record['state_id']));
        if(!empty($record['store_id'])){
            $this->setStore(new Store($record['store_id']));
        }
        parent::__construct($record['book_id']);
        $connection = null;
    }
}
