<?php

/**
 * Class for author
 * Is used for reading and writing info about book to DB. Also, for showing infos about the author
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Author {
    
    /**
     * Id of the author
     *
     * @var int 
     */
    private $id;
    
    /**
     * Name of the author
     *
     * @var string 
     */
    private $name;
    
    /**
     * Surname of the author
     *
     * @var string 
     */
    private $surname;
    
    /**
     * A short description of the author
     *
     * @var string 
     */
    private $description;
    
    /**
     * Default image of the author
     *
     * @var Image 
     */
    private $defImage;
    
    /**
     * All images of the author stored in an object
     *
     * @var Images 
     */
    private $images;
    
    /**
     * Sets id of the author
     * 
     * @param int $id id of the author
     */
    private function setId(int $id) {
        $this->id = $id;
    }
    
    /**
     * Returns name of the author
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns surname of the author
     * 
     * @return string
     */
    public function getSurname(): string {
        return $this->surname;
    }

    
    /**
     * Sets the name of the author
     * 
     * @param string $name Name of the author
     */
    private function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Sets the surname of the author
     * 
     * @param string $surname Surname of the author
     */
    private function setSurname(string $surname) {
        $this->surname = $surname;
    }

    /**
     * Sets the description
     * 
     * @param string $description Description of the author
     */
    private function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * Sets images object for the author
     * 
     * @param Images $images Object of all images of the author
     */
    private function setImages(Images $images) {
        $this->images = $images;
    }

    /**
     * Sets the default image for the author
     * 
     * @param Image $defImage default image object
     */
    public function setDefImage(Image $defImage) {
        $this->defImage = $defImage;
    }

    /**
     * Returns id of the author
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns the description of the author
     * 
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Returns the default image of the author
     * 
     * @return \Image
     */
    public function getDefImage(): Image {
        return $this->defImage;
    }

    /**
     * Returns the object of all images of the author
     * 
     * @return \Images
     */
    public function getImages(): Images {
        return $this->images;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1 or 3 parameters.
     * In case of 1 parameter the object is build by Id of the author from DB
     * In case of 3 parameter the object is build by name, surname and description 
     * The option with 3 parameters is used when the author is being created and id is 
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
     * Constructs an object with id based on info from DB
     * 
     * @param int $id id of the author
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT name, surname, description, def_img_id FROM author WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setName($record['name']);
            $this->setSurname($record['surname']);
            $this->setDescription($record['description']);
            $this->setDefImage(new Image($record['def_img_id']));
            $result = $link->query("SELECT image_id FROM author_has_image WHERE author_id = '$this->id'");
            $images = new Images();
            foreach ($result as $record){
                $images[] = new Image($record['image_id']);
            }
            $this->setImages($images);
            
        }
        else{
            echo 'There\'s no author with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs object, then creates a record in DB and gets all needed info to complete object construction
     * 
     * @param string $name Name of the author
     * @param string $surname Surname of the author
     * @param string $description Short description of the author
     */
    private function constructByInput(string $name, string $surname, string $description){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setName(clean($name));
        $this->setSurname(clean($surname));
        $this->setDescription(clean($description));
        $link->exec("INSERT INTO author (name, surname, description) VALUES('$this->name', '$this->surname', '$this->description')");
        $this->setId($link->lastInsertId());
        $link->exec("INSERT INTO author_has_image (author_id) VALUES('$this->id')");
        $images = new Images();
        $def_image = new Image(1);
        $images[] = $def_image;
        $this->setDefImage($def_image);
        $this->setImages($images);
        $connection = null;
    }
    
    /**
     * Checks, if a placeholder is bound to the author
     * 
     * @return bool
     */
    private function checkForDefaultImage(): bool{
        $default = false;
        $defaultImage = new Image(1);
        foreach ($this->getImages() as $image){
            if($image->getId() == $defaultImage->getId()){
                $default = true;
            }
        }
        return $default;
    }
    
    /**
     * Removes given image from Images array of the object, no DB change
     * 
     * @param Image $image
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
     * Adds the given image to the object. Unbinds placeholder if it was bound. All changes are written to DB
     * 
     * @param Image $image
     */
    public function addImage(Image $image){
        $connection = new Connection();
        $link = $connection->connect();
        $this->images[] = $image;
        $link->query("INSERT INTO author_has_image (author_id, image_id) VALUES ('" . $this->getId() . "', '" . $image->getId() . "')");
        
        if($this->checkForDefaultImage()){
            $this->removeImage($this->defImage);
            $link->query("DELETE FROM author_has_image WHERE author_id = '" . $this->getId() . "' AND image_id = '1'");
            $this->setDefImage($image);
            $link->query("UPDATE author SET def_img_id = '" . $this->defImage->getId() . "' WHERE id = '" . $this->getId() . "'");
        }
        $link = null;
    }
    
    /**
     * Checks if the given image is bound to the object
     * 
     * @param Image $image
     * @return boolean
     */
    public function isImageSet(Image $image){
        foreach ($this->getImages() as $authorImage){
            if($authorImage->getId() == $image->getId()){
                return true;
            }
        }
        return false;
    }
    
    /**
     * Returns name and surname of the author as a string
     * 
     * @return string
     */
    public function getFullname(): string{
        return $this->name . ' ' . $this->surname;
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
        $link->exec("UPDATE author SET def_img_id = '" . $image->getId() . "' WHERE id = '" . $this->getId() . "'");
        $connection = null;
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
            $link->exec("DELETE FROM author_has_image WHERE author_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
            $_SESSION['success_message'] = 'Image unbound';
        #image is default, need to set new default image
        } else {
            #is there any other image?
            $count_img = count($this->getImages());
            #no, new default image is placeholder
            if ($count_img == 1) {
                $this->changeDefImage(new Image(1));
                $this->images[] = new Image(1);
                $link->query("INSERT INTO author_has_image (author_id) VALUES ('" . $this->getId() . "')");
                $this->removeImage($image);
                $link->exec("DELETE FROM author_has_image WHERE author_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
                $_SESSION['success_message'] = 'Image unbound. New default image set.';
            #yes, delete connection, set another image as default
            } else {
                $this->removeImage($image);
                $link->exec("DELETE FROM author_has_image WHERE author_id = '" . $this->getId() . "' AND image_id = '" . $image->getId() . "'");
                $this->changeDefImage($this->getImages()[0]);               
                $_SESSION['success_message'] = 'Image unbound. New default image set.';
            }
        }
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
     * Edit author based on given data
     * 
     * @param string $name
     * @param string $surname
     * @param string $description
     */
    public function editAuthor(string $name, string $surname, string $description){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setName(clean($name));
        $this->setSurname(clean($surname));
        $this->setDescription(clean($description));        
        $link->exec("UPDATE author SET name = '$this->name', surname = '$this->surname', description = '$this->description' WHERE id = '$this->id'");        
        $connection = null;
    }
}
