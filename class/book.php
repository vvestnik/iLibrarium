<?php

/**
 * Class for book
 * Is used for reading and writing info about book to DB. Also, for showing infos about the book
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Book {
        
    /**
     * Id for book in general (for example Fellowship of the ring, or Game of Thrones), not the id of the instance of the book
     * 
     * @var int 
     */
    protected $id;
    
    /**
     * The name (the title) of the book
     * 
     * @var string 
     */
    private $name;
    
    /**
     * The unique number ISBN
     * 
     * @var string 
     */
    private $isbn;
    
    /**
     * Indicates for how many months the book is borrowed
     * 
     * @var int 
     */
    private $loanTime;
    
    /**
     * A description of the book to show on the website
     * 
     * @var string 
     */
    private $description;
        
    /**
     * All authors of the book stored in object
     * 
     * @var BookAuthors 
     */
    private $authors;
    
    /**
     * All genres of the book stored in object
     * 
     * @var BookGenres 
     */
    private $genres;
    
    /**
     * All images of the book stores in object
     * 
     * @var Images 
     */
    private $images;
    
    /**
     * Default image of the book
     * 
     * @var Image 
     */
    private $defImage;
    
    /**
     * Sets the id of the book in general
     * 
     * @param int $id Id of the book in general
     */
    private function setId(int $id) {
        $this->id = $id;
    }
    
    /**
     * Sets the title of the book
     * 
     * @param string $name The name (the title) of the book
     */
    private function setName(string $name) {
        $this->name = $name;
    }
    
    /**
     * Sets the ISBN of the book
     * 
     * @param string $isbn ISBN code of the book
     */
    private function setIsbn(string $isbn) {
        $this->isbn = $isbn;
    }
    
    /**
     * Sets for how long the book is borrowed
     * 
     * @param int $loan_time months
     */
    private function setLoanTime(int $loanTime) {
        $this->loanTime = $loanTime;
    }

    /**
     * Sets the description of the book
     * 
     * @param string $description Description
     */
    private function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * Sets Authors of the book
     * 
     * @param BookAuthors $authors Object of authors
     */
    private function setAuthors(BookAuthors $authors) {
        $this->authors = $authors;
    }

    /**
     * Sets genres of the book
     * 
     * @param BookGenres $genres Object of genres
     */
    private function setGenres(BookGenres $genres) {
        $this->genres = $genres;
    }

    /**
     * Sets images of the book
     * 
     * @param Images $images Object of images
     */
    private function setImages(Images $images) {
        $this->images = $images;
    }
    
    /**
     * Sets the default image of the book
     * 
     * @param Image $def_image Default image
     */
    public function setDefImage(Image $defImage) {
        $this->defImage = $defImage;
    }

    /**
     * Returns id of the book in general
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns the title of the book
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns ISBN of the book
     * 
     * @return string
     */
    public function getIsbn(): string {
        return $this->isbn;
    }

    /**
     * Returns for how many months the book is borrowed
     * 
     * @return int
     */
    public function getLoanTime(): int {
        return $this->loanTime;
    }

    /**
     * Returns the description of the book
     * 
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }
    
    /**
     * Returns the object of authors of the book
     * 
     * @return \BookAuthors
     */
    public function getAuthors(): BookAuthors {
        return $this->authors;
    }

    /**
     * Returns the object of the book genres of the book
     * 
     * @return \BookGenres
     */
    public function getGenres(): BookGenres {
        return $this->genres;
    }

    /**
     * Returns the object of the images od the book
     * 
     * @return \Images
     */
    public function getImages(): Images {
        return $this->images;
    }

    /**
     * Returns the default image object
     * 
     * @return \Image
     */
    public function getDefImage(): Image {
        return $this->defImage;
    }
    
    /**
     * Constructor of the class
     * 
     * Accepts 1 or 6 parameters.
     * In case of 1 parameter the object is build by Id of the book from DB.
     * In case of 6 parameter the object is build by Title, ISBN, Loan time,
     * Description, Authors and Genres.
     * The option with 6 parameters is used when the book is being created and id is 
     * unknown yet
     */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 6){
            $this->constructByInput(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4), func_get_arg(5));           
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }
    }
    
    /**
     * Constructs the object based on id and infos from DB
     * 
     * @param int $id id of the book
     */
    protected function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT name, isbn, loan_time, description, def_img_id FROM book WHERE id = '$this->id'");
        $record = $result->fetch();        
        if($record){
            $this->setName($record['name']);
            $this->setIsbn($record['isbn']);
            $this->setLoanTime($record['loan_time']);
            $this->setDescription($record['description']);
            $this->setDefImage(new Image($record['def_img_id']));
            $result = $link->query("SELECT author_id FROM book_has_author WHERE book_id = '$this->id'");
            $authors = new BookAuthors();
            foreach ($result as $record){
                $authors[] = new Author($record['author_id']);
            }
            $this->setAuthors($authors);
            $result = $link->query("SELECT image_id FROM book_has_image WHERE book_id = '$this->id'");
            $images = new Images();
            foreach ($result as $record){
                $images[] = new Image($record['image_id']);
            }
            $this->setImages($images);
            $result = $link->query("SELECT genre_id FROM book_has_genre WHERE book_id = '$this->id'");
            $genres = new BookGenres();
            foreach ($result as $record){
                $genres[] = new Genre('id', $record['genre_id']);
            }
            $this->setGenres($genres);
        }
        else{
            echo 'There\'s no book with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs class based on input data and writes to DB new record
     * 
     * @param string $name title of the book
     * @param string $isbn ISBN of the book
     * @param int $loanTime for how many months book is borrowed
     * @param string $description description of the book
     * @param array $authors array of author ids of the book
     * @param array $genres array of genre ids of the book
     */
    private function constructByInput(string $name, string $isbn, int $loanTime, string $description, array $authors, array $genres){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setIsbn(clean($isbn));
        $result = $link->query("SELECT * FROM book WHERE isbn = '$this->isbn'");
        if(!$result->fetch()){
            $this->setName(clean($name));
            $this->setLoanTime(clean($loanTime));
            $this->setDescription(clean($description));
            $link->exec("INSERT INTO book (name, isbn, loan_time, description) VALUES('$this->name', '$this->isbn', '$this->loanTime', '$this->description')");
            $this->setId($link->lastInsertId());
            $auth = new BookAuthors();
            foreach ($authors as $author_id){
                $auth[] = new Author($author_id);
                $link->exec("INSERT INTO book_has_author (book_id, author_id) VALUES('$this->id', '$author_id')");
            }
            $this->setAuthors($auth);
            $gen = new BookGenres();
            foreach ($genres as $genre_id){
                $gen[] = new Genre('id', $genre_id);
                $link->exec("INSERT INTO book_has_genre (book_id, genre_id) VALUES('$this->id', '$genre_id')");
            }
            $this->setGenres($gen);
            $link->exec("INSERT INTO book_has_image (book_id) VALUES('$this->id')");
            $images = new Images();
            $def_image = new Image(2);
            $images[] = $def_image;
            $this->setDefImage($def_image);
            $this->setImages($images);
        }
        else{
            $alert .= 'Book already exists (ISBN)';
        }
        $connection = null;
    }
    
    /**
     * Returns array of authors of the book
     * 
     * @return array
     */
    public function getAuthorsArray(){
        $authors = array();
        foreach ($this->getAuthors() as $author){
            $authors[] = $author->getName() . ' ' . $author->getSurname();
        }
        return $authors;
    }
    
    /**
     * Returns array of ids of authors of the book
     * 
     * @return array
     */
    public function getAuthorsIdArray(){
        $authors = array();
        foreach ($this->getAuthors() as $author){
            $authors[] = $author->getId();
        }
        return $authors;
    }
    
    /**
     * Returns true if placeholder image is bound to the book
     * 
     * @return bool
     */
    private function checkForDefaultImage(): bool{
        $default = false;
        $defaultImage = new Image(2);
        foreach ($this->getImages() as $image){
            if($image->getId() == $defaultImage->getId()){
                $default = true;
            }
        }
        return $default;
    }
    
    /**
     * Removes Image from Images array without removing from db
     * 
     * @param Image $image Image to remove from book
     */
    public function removeImage(Image $image){
        $newImages = new Images();
        foreach ($this->getImages() as $img){            
            if($img->getId() != $image->getId()){
                $newImages[] = $img;
            }
        }
        $this->setImages($newImages);
    }
    
    /**
     * Removes Image from Images array with removing from db
     * 
     * @param Image $image Image to remove from book
     */
    public function unsetImage(Image $image){
        $connection = new Connection();
        $link = $connection->connect();
        #is the image default?
        #image is not default, just delete connection
        if (!$this->isImageDefault($image)) {
            $this->removeImage($image);
            $link->exec("DELETE FROM book_has_image WHERE book_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
            $_SESSION['success_message'] = 'Image unbound';
        #image is default, need to set new default image
        } else {
            #is there any other image?
            $count_img = count($this->getImages());
            #no, new default image is placeholder
            if ($count_img == 1) {
                $this->changeDefImage(new Image(2));
                $this->images[] = new Image(2);
                $link->query("INSERT INTO book_has_image (book_id) VALUES ('" . $this->getId() . "')");
                $this->removeImage($image);
                $link->exec("DELETE FROM book_has_image WHERE book_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
                $_SESSION['success_message'] = 'Image unbound. New default image set.';
            #yes, delete connection, set another image as default
            } else {
                $this->removeImage($image);
                $link->exec("DELETE FROM book_has_image WHERE book_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
                $this->changeDefImage($this->getImages()[0]);               
                $_SESSION['success_message'] = 'Image unbound. New default image set.';
            }
        }
        $connection = null;
    }
    
    /**
     * Add image to the book with adding to db
     * 
     * @param Image $image
     */
    public function addImage(Image $image){
        $connection = new Connection();
        $link = $connection->connect();
        $this->images[] = $image;
        $link->query("INSERT INTO book_has_image (book_id, image_id) VALUES ('" . $this->getId() . "', '" . $image->getId() . "')");
        
        if($this->checkForDefaultImage()){
            $this->removeImage($this->defImage);
            $link->query("DELETE FROM book_has_image WHERE book_id = '" . $this->getId() . "' AND image_id = '2'");
            $this->setDefImage($image);
            $link->query("UPDATE book SET def_img_id = '" . $this->defImage->getId() . "' WHERE id = '" . $this->getId() . "'");
        }
        $link = null;
    }
    
    /**
     * Returns array of genre names
     * 
     * @return array
     */
    public function getGenresArray(): array{
        $genres = array();
        foreach ($this->getGenres() as $genre){
            $genres[] = $genre->getGenreName();
        }
        return $genres;
    }

    /**
     * Returns 2d array of names and ids of authors of the book
     * 
     * @return array
     */
    public function getAuthorsIdNamesArray(){
        $authors = array();
        foreach ($this->getAuthors() as $author){
            $record = [];
            $record['name'] = $author->getName() . ' ' . $author->getSurname();
            $record['id'] = $author->getId();
            $authors[] = $record;
        }
        return $authors;
    }
    
    /**
     * Returns true if there's a book in stock in the system
     * 
     * @return boolean 
     */
    public function isInStock(){
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT id FROM book_instance WHERE state_id = '1' AND book_id = '" . $this->getId() . "'");
        if(!$record = $result->fetch()){
            return false;
        }
        else{
            return true;
        }
        $connection = null;
    }
    
    /**
     * Sets given image as default
     * 
     * @param Image $image
     */
    public function changeDefImage(Image $image){
        $this->setDefImage($image);
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("UPDATE book SET def_img_id = '" . $image->getId() . "' WHERE id = '" . $this->getId() . "'");
        $connection = null;
    }
    
    /**
     * Check if given image is set as default 
     * 
     * @param Image $image
     * @return boolean
     */
    public function isImageDefault(Image $image){
        if($this->getDefImage()->getId() == $image->getId()){
            return true;
        }          
        else{
            return false;
        }
    }
    
    /**
     * Is image in Images array of the book
     * 
     * @param Image $image
     * @return boolean
     */
    public function isImageSet(Image $image){
        foreach ($this->getImages() as $bookImage){
            if($bookImage->getId() == $image->getId()){
                return true;
            }
        }
        return false;
    }
    
    /**
     * How many books in the system overall
     * 
     * @return int
     */
    public function qtyInStock(): int{
        $connection = new Connection();
        $link = $connection->connect();
        $count = $link->query("SELECT count(*) FROM book_instance WHERE book_id = '" . $this->id . "' AND state_id = 1")->fetchColumn();
        $connection = null;
        return $count;
    }
    
    /**
     * Edit book based on given data
     * 
     * @param string $name
     * @param string $isbn
     * @param string $description
     * @param int $loanTime
     * @param array $author_id
     * @param array $genre_id
     */
    public function editBook(string $name, string $isbn, string $description, int $loanTime, array $author_id, array $genre_id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setName(clean($name));
        $this->setIsbn(clean($isbn));
        $this->setDescription(clean($description));
        $this->setLoanTime(clean($loanTime));        
        $link->exec("UPDATE book SET name = '$this->name', isbn = '$this->isbn', description = '$this->description', loan_time = '$this->loanTime' WHERE id = '$this->id'");
        $link->exec("DELETE FROM book_has_author WHERE book_id = '$this->id'");
        $newAuthors = new BookAuthors();
        foreach ($author_id as $author){
            $newAuthors[] = new Author($author);
            $link->exec("INSERT INTO book_has_author (book_id, author_id) VALUES ('$this->id', '$author')");
        }
        $this->setAuthors($newAuthors);
        $link->exec("DELETE FROM book_has_genre WHERE book_id = '$this->id'");
        $newGenres = new BookGenres();
        foreach ($genre_id as $genre){
            $newGenres[] = new Genre('id', $genre);
            $link->exec("INSERT INTO book_has_genre (book_id, genre_id) VALUES ('$this->id', '$genre')");
        }
        $this->setGenres($newGenres);
        $connection = null;
    }
    
    /**
     * is author in authors array?
     * 
     * @param Author $author
     * @return boolean
     */
    public function isAuthorSet(Author $author){
        foreach ($this->authors as $allAuthor){
            if($allAuthor->getId() == $author->getId()){
                return true;
            }
        }
        return false;
    }
    
    /**
     * is Genre in Genres array
     * 
     * @param Genre $genre
     * @return boolean
     */
    public function isGenreSet(Genre $genre){
        foreach ($this->genres as $allGenres){
            if($allGenres->getId() == $genre->getId()){
                return true;
            }
        }
        return false;
    }
    
    /**
     * add new instance of the book to the given store
     * 
     * @param Store $store
     */
    public function addInstance(Store $store){
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("INSERT INTO book_instance (state_id, book_id, store_id) VALUES ('1', '" . $this->id . "', '" . $store->getId() . "')");
        $connection = null;
    }
    
    /**
     * How many books in the given store
     * 
     * @param Store $store
     * @return int
     */
    public function qtyInStore(Store $store): int{
        $connection = new Connection();
        $link = $connection->connect();
        $count = $link->query("SELECT count(*) FROM book_instance WHERE book_id = '" . $this->id . "' AND state_id = 1 AND store_id = '" . $store->getId() . "'")->fetchColumn();
        $connection = null;
        return $count;
    }
    
    /**
     * Remove instance of the book
     * 
     * @param BookInstance $instance
     */
    public function removeInstance(BookInstance $instance){
        $connection = new Connection();
        $link = $connection->connect();
        echo $instance->getInstanceId();
        echo "DELETE FROM book_instance WHERE id = '" . $instance->getInstanceId() . "'";
        $link->exec("DELETE FROM book_instance WHERE id = '" . $instance->getInstanceId() . "'");
        $connection = null;
    }
}