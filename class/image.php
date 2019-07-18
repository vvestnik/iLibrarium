<?php

/**
 * Class for image
 * Is used for reading and writing info about image to DB. Also, for showing image
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Image extends ImageCommon {
    
    /**
     * Tags of the image
     *
     * @var string 
     */
    private $tags;
    
    /**
     * Path to images
     *
     * @var string 
     */
    private $image_path = 'images/';
    
    /**
     * Path to thumbnails
     *
     * @var string 
     */
    private $thumbnail_path = 'thumbnails/';
    
    /**
     * Path to images sized for carousel
     *
     * @var string
     */
    private $carousel_path = 'carousel/';
    
    /**
     * Max width in px
     *
     * @var int 
     */
    private $width_max = 800;
    
    /**
     * Max height in px
     *
     * @var int 
     */
    private $height_max = 800;
    
    /**
     * Height of the thumbnail
     *
     * @var int 
     */
    private $thumbnailHeight = 100;
    
    /**
     * Height of the carousel image
     *
     * @var int 
     */
    private $carouselHeight = 300;
    
    /**
     * Sets tags for the image
     * 
     * @param string $tags Tags for the image
     */
    private function setTags(string $tags) {
        $this->tags = $tags;
    }

    /**
     * Returns tags of the image
     * 
     * @return string
     */
    public function getTags(): string {
        return $this->tags;
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
        $result = $link->query("SELECT filename, date, description, tags, width, height, author, uploaded_by FROM image WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setFilename($record['filename']);
            $this->setDate(new DateTime($record['date']));
            $this->setDescription($record['description']);
            $this->setTags($record['tags']);
            $this->setWidth($record['width']);
            $this->setHeight($record['height']);
            $this->setAuthor($record['author']);
            $this->setUploadedBy(new User($record['uploaded_by']));
            
        }
        else{
            $alert .= 'There\'s no image with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs an object, then creates a record in DB and gets all needed info to complete class construction
     * 
     * @param array $file
     * @param string $tags
     * @param string $author
     * @param string $description
     */
    private function constructByInput(array $file, string $tags, string $author, string $description){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setTags(clean($tags));
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
        if (is_file($this->image_path . $this->filename)) {
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
            $link->exec("INSERT INTO image (filename, date, description, tags, width, height, author, uploaded_by) VALUES('$this->filename', NOW(), '$this->description', '$this->tags', '$this->width', '$this->height', '$this->author', '" . $this->uploadedBy->getId() . "')");
            $this->setId($link->lastInsertId());
            $result = $link->query("SELECT date FROM image WHERE id = '$this->id'");
            $record = $result->fetch();
            $this->setDate(new DateTime($record['date']));
            move_uploaded_file($file['tmp_name'], $this->image_path . $this->filename);
            $this->createThumbnail($fileinfos);
            $this->createCarouselImage($fileinfos);
        }
        $connection = null;
    }
    
    /**
     * Creates a thumbnail for the image
     * 
     * @param array $fileinfos output from fileinfos() command
     */
    private function createThumbnail(array $fileinfos){
        $thumbnailWidth = intval($this->width * $this->thumbnailHeight / $this->height);
        $type = $fileinfos[2];
        switch ($type) {
            case '1':
                $bigimage = imagecreatefromgif($this->image_path . $this->filename);
                $thumbnail = imagecreatetruecolor($thumbnailWidth, $this->thumbnailHeight);
                imagecopyresized($thumbnail, $bigimage, 0, 0, 0, 0, $thumbnailWidth, $this->thumbnailHeight, $this->width, $this->height);
                imagegif($thumbnail, $this->image_path . $this->thumbnail_path . $this->filename);
                break;
            case '2':
                $bigimage = imagecreatefromjpeg($this->image_path . $this->filename);
                $thumbnail = imagecreatetruecolor($thumbnailWidth, $this->thumbnailHeight);
                imagecopyresized($thumbnail, $bigimage, 0, 0, 0, 0, $thumbnailWidth, $this->thumbnailHeight, $this->width, $this->height);
                imagejpeg($thumbnail, $this->image_path . $this->thumbnail_path . $this->filename);
                break;
            case '3':
                $bigimage = imagecreatefrompng($this->image_path . $this->filename);
                $thumbnail = imagecreatetruecolor($thumbnailWidth, $this->thumbnailHeight);
                imagecopyresized($thumbnail, $bigimage, 0, 0, 0, 0, $thumbnailWidth, $this->thumbnailHeight, $this->width, $this->height);
                imagepng($thumbnail, $this->image_path . $this->thumbnail_path . $this->filename);
                break;
        }
    }
    
    /**
     * Creates a smaller image, that is used for carousel widget later
     * 
     * @param array $fileinfos output from fileinfos() command
     */
    private function createCarouselImage(array $fileinfos){
        $this->carouselHeight = 300;
        $carouselWidth = intval($this->width * $this->carouselHeight / $this->height);
        $type = $fileinfos[2];
        switch ($type) {
            case '1':
                $bigimage = imagecreatefromgif($this->image_path . $this->filename);
                $carousel = imagecreatetruecolor($carouselWidth, $this->carouselHeight);
                imagecopyresized($carousel, $bigimage, 0, 0, 0, 0, $carouselWidth, $this->carouselHeight, $this->width, $this->height);
                imagegif($carousel, $this->image_path . $this->carousel_path . $this->filename);
                break;
            case '2':
                $bigimage = imagecreatefromjpeg($this->image_path . $this->filename);
                $carousel = imagecreatetruecolor($carouselWidth, $this->carouselHeight);
                imagecopyresized($carousel, $bigimage, 0, 0, 0, 0, $carouselWidth, $this->carouselHeight, $this->width, $this->height);
                imagejpeg($carousel, $this->image_path . $this->carousel_path . $this->filename);
                break;
            case '3':
                $bigimage = imagecreatefrompng($this->image_path . $this->filename);
                $carousel = imagecreatetruecolor($carouselWidth, $this->carouselHeight);
                imagecopyresized($carousel, $bigimage, 0, 0, 0, 0, $carouselWidth, $this->carouselHeight, $this->width, $this->height);
                imagepng($carousel, $this->image_path . $this->carousel_path . $this->filename);
                break;
        }
    }

    /**
    * Constructor of the class
    * 
    * Accepts 1 or 4 parameters.
    * In case of 1 parameter the object is build by Id of the image from DB
    * In case of 4 parameter the object is build by file, tags, author and description 
    * The option with 4 parameters is used when the image is being created and id is 
    * unknown yet
    */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 4){
            $this->constructByInput(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3));          
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }
    }
    
}
