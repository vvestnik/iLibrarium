<?php

/**
 * Abstract class for image and avatar
 * Image and avatar extend this class. Just evading double writing
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
abstract class ImageCommon {
    
    /**
     * id of the image
     *
     * @var int 
     */
    protected $id;
    
    /**
     * File name of the image
     *
     * @var string 
     */
    protected $filename;
    
    /**
     * Date and time of adding the image to db
     *
     * @var DateTime 
     */
    protected $date;
    
    /**
     * Short description
     *
     * @var string 
     */
    protected $description;
           
    /**
     * Width of the image
     *
     * @var int 
     */
    protected $width;
    
    /**
     * Height of the image
     *
     * @var int 
     */
    protected $height;
    
    /**
     * Copyright holder of the image
     *
     * @var string 
     */
    protected $author;
    
    /**
     * User, who uploaded the image
     *
     * @var User 
     */
    protected $uploadedBy;    
    
    /**
     * Allowed mimetypes of uploaded images
     *
     * @var array array
     */
    protected $MIMETypes = array('image/jpeg', 'image/gif', 'image/png');
    
    /**
     * Allowed file extensions of uploaded files
     *
     * @var array 
     */
    protected $nameending = array('jpg', 'png', 'gif', 'jpeg');
    
    /**
     * Max size in bytes
     *
     * @var int 
     */
    protected $size_max = 1024 * 1024;
    
    /**
     * Sets the id of the image
     * 
     * @param int $id id of the image
     */
    protected function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets the filename of the image
     * 
     * @param string $filename filename of the image
     */
    protected function setFilename(string $filename) {
        $this->filename = $filename;
    }

    /**
     * Sets date and time
     * 
     * @param DateTime $date date and time of adding the image
     */
    protected function setDate(DateTime $date) {
        $this->date = $date;
    }

    /**
     * Sets the description of the image
     * 
     * @param string $description Short description
     */
    protected function setDescription(string $description) {
        $this->description = $description;
    }

    /**
     * Sets width of the image
     * 
     * @param int $width Width of the image
     */
    protected function setWidth(int $width) {
        $this->width = $width;
    }

    /**
     * Sets height of the image
     * 
     * @param int $height Height of the image
     */
    protected function setHeight(int $height) {
        $this->height = $height;
    }

    /**
     * Sets copyright holder of the image
     * 
     * @param string $author Copyright holder of the image
     */
    protected function setAuthor(string $author) {
        $this->author = $author;
    }

    /**
     * Sets the user who uploaded the image
     * 
     * @param User $uploadedBy User, who uploaded the image
     */
    protected function setUploadedBy(User $uploadedBy) {
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * Returns description of the image
     * 
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * Returns width of the image
     * 
     * @return int
     */
    public function getWidth(): int {
        return $this->width;
    }

    /**
     * Returns height of the image
     * 
     * @return int
     */
    public function getHeight(): int {
        return $this->height;
    }

    /**
     * Returns copyright holder of the image
     * 
     * @return string
     */
    public function getAuthor(): string {
        return $this->author;
    }

    /**
     * Returns id of the image
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns filename of the image
     * 
     * @return string
     */
    public function getFilename(): string {
        return $this->filename;
    }

    /**
     * Returns date and time of adding the image
     * 
     * @return \DateTime
     */
    protected function getDate(): DateTime {
        return $this->date;
    }

    /**
     * Returns the user who uploaded the image
     * 
     * @return \User
     */
    protected function getUploadedBy(): User {
        return $this->uploadedBy;
    }
         
    /**
     * Returns username of the user who uploaded the file
     * 
     * @return string
     */
    public function getUploadedByNick(): string {
        return $this->getUploadedBy()->getNickname();
    }
    
    /**
     * Returns date and time of file's upload in string
     * 
     * @return string
     */
    public function getFormattedDate(): string {
        return $this->getDate()->format('H:i d.m.Y');
    }
}
