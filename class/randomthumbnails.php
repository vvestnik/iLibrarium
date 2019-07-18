<?php

/**
 * Class for random thumbnails widget
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class RandomThumbnails {
    
    /**
     * How many rows of thumbnails?
     *
     * @var int 
     */
    private $rowsCount;

    /**
     * Sets row count
     * 
     * @param int $rowsCount How many rows of thumbnails?
     */
    private function setRowsCount(int $rowsCount){
        $this->rowsCount = $rowsCount;
    }
    
    /**
     * Random thumbnails
     * 
     * @param int $rowsCount
     */
    public function __construct(int $rowsCount) {
        $this->setRowsCount($rowsCount);
    }
    
    /**
     * Returns array needed to gegenrate the thumbnails
     * 
     * @return array
     */
    private function getThumbnailsArray(): array {
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT filename, id FROM image WHERE id <> '1' AND id <> '2' ORDER BY RAND() LIMIT " . $this->rowsCount * 2);
        $thumbnails = array();
        while ($record = $result->fetch()){
            $thumbnails[] = $record;
        }
        $connection = null;
        return $thumbnails;
    }
    
    /**
     * generates html content. Header of the widget
     */
    private function generateHead() {
        echo '<h3><span class="glyphicon glyphicon-camera"></span> Images</h3>';
        echo  '<div class="row">';
    }
    
    /**
     * generates html content. Footer of the widget
     */
    private function generateFoot() {
        echo '</div>';
    }
    
    /**
     * shows widget on a webpage
     */
    public function showThumbnails() {
        $this->generateHead();
        $thumbnailsArray = $this->getThumbnailsArray();
        for($i = 0; $i < $this->rowsCount * 2; $i ++){
            echo '<div class="col-xs-6">';
            echo  '<a href="big_img.php?img_id=' . $thumbnailsArray[$i]['id'] . '" class="thumbnail">';
            echo   '<img src="images/thumbnails/' . $thumbnailsArray[$i]['filename'] . '" alt="">';
            echo  '</a>';
            echo '</div>';
        }
        $this->generateFoot();
    }
}
