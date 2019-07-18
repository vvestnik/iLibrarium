<?php

/**
 * Class for avatar
 * Is used for reading and writing info about avatar to DB. Also, for showing avatar
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Avatar extends ImageCommon{
    
    /**
     * Path to avatars
     *
     * @var string 
     */
    private $avatar_path = 'avatars/';
    
    /**
     * Max width in px
     *
     * @var int 
     */
    private $width_max = 50;
    
    /**
     * Max height in px
     *
     * @var int 
     */
    private $height_max = 50;
    
    /**
     * Constructor of the class
     * 
     * Accepts 1, 2 or 3 parameters.
     * In case of 1 parameter the object is build by Id of the image from DB
     * In case of 2 parameter the object is build by Id of the image from DB and user, who uploaded the image
     * In case of 3 parameter the object is build by file, author and description 
     * The option with 3 parameters is used when the avatar is being created and id is 
     * unknown yet
     */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 2){
            $this->constructByIdUser(func_get_arg(0), func_get_arg(1));          
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
     * @param int $id id of the image
     */
    private function constructById(int $id){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT filename, date, description, width, height, author, uploaded_by FROM avatar WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setFilename($record['filename']);
            $this->setDate(new DateTime($record['date']));
            $this->setDescription($record['description']);
            $this->setWidth($record['width']);
            $this->setHeight($record['height']);
            $this->setAuthor($record['author']);
            $this->setUploadedBy(new User($record['uploaded_by'], $this));
            
        }
        else{
            $alert .= 'There\'s no avatar with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs an object with id based on info from DB and User object
     * 
     * @param int $id id of the image
     * @param User $user who has uploaded the avatar
     */
    private function constructByIdUser(int $id, User $user){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT filename, date, description, width, height, author FROM avatar WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setFilename($record['filename']);
            $this->setDate(new DateTime($record['date']));
            $this->setDescription($record['description']);
            $this->setWidth($record['width']);
            $this->setHeight($record['height']);
            $this->setAuthor($record['author']);
            $this->setUploadedBy($user);
            
        }
        else{
            $alert .= 'There\'s no avatar with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs class, then creates a record in DB and gets all needed info to complete class construction
     * 
     * @param array $file
     * @param string $author
     * @param string $description
     */
    private function constructByInput(array $file, string $author, string $description){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setAuthor(clean($author)); 
        $this->setDescription(clean($description));
        
        $this->setFilename(clean($file['name']));
        $info = pathinfo($this->filename);
        $fileextension = $info['extension'];
        if (!in_array($fileextension, $this->nameending)) {
            $alert .= 'Wrong file extension. Upload not allowed.<br>';
        }
        if ($this->size_max < $file['size'] || 50 > $file['size']) {
            $alert .= 'File size is incorrect. Upload not allowed.<br>';
        }
        if (!in_array($file['type'], $this->MIMETypes)) {
            $alert .= 'Wrong file type. Upload not allowed.<br>';
        }
        if (is_file($this->avatar_path . $this->filename)) {
            $alert .= 'File with the same name already exists. Upload not allowed.<br>';
        }

        $fileinfos = getimagesize($file['tmp_name']);
        $this->setWidth($fileinfos[0]);
        $this->setHeight($fileinfos[1]);
        if ($this->width > $this->width_max || $this->height > $this->height_max) {
            $alert .= 'The file is too big (in pixels). Upload not allowed.<br>';
        }      
        if(empty($alert)){
            $this->setUploadedBy($_SESSION['aut_user']);        
            $link->exec("INSERT INTO avatar (filename, date, description, width, height, author, uploaded_by) VALUES('$this->filename', NOW(), '$this->description', '$this->width', '$this->height', '$this->author', '" . $this->uploadedBy->getId() . "')");
            $this->setId($link->lastInsertId());
            $result = $link->query("SELECT date FROM image WHERE id = '$this->id'");
            $record = $result->fetch();
            $this->setDate(new DateTime($record['date']));
            move_uploaded_file($file['tmp_name'], $this->avatar_path . $this->filename);
        }
        $connection = null;
    }   
}
