<?php

/**
 * Class for genres chart
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class GenresChart {
    
    /**
     * How many genres in top
     *
     * @var int 
     */
    private $topX;
    
    /**
     * String values needed for Bootstrap to change color of the bar
     *
     * @var array 
     */
    private $colorsArray = ['success', 'info', 'warning', 'danger'];
    
    /**
     * Sets the value, that indicates, how many genres to show in top
     * 
     * @param int $topX how many genres in top
     */
    private function setTopX(int $topX) {
        $this->topX = $topX;
    }
    
    /**
     * Chart of genres
     * 
     * @param int $topX how many genres in top
     */
    public function __construct(int $topX) {
        $this->setTopX($topX);
    }
    
    /**
     * Returns array needed to generate the chart
     * 
     * @return array
     */
    private function getGenresArray(): array {
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT COUNT(book.id) AS count, genre.name FROM genre INNER JOIN book_has_genre ON genre.id = book_has_genre.genre_id INNER JOIN book ON book.id = book_has_genre.book_id GROUP BY genre.name ORDER BY count DESC LIMIT $this->topX");
        $topGenres = array();
        while ($record = $result->fetch()){
            $topGenres[] = $record;
        }
        $connection = null;
        return $topGenres;
    }
    
    /**
     * Generates html conten. Header of the widget
     */
    private function generateHead() {
        echo '<div class="panel panel-default">';
        echo  '<div class="panel-heading">';
        echo   '<h3 class="panel-title">';
        echo    '<span class="glyphicon glyphicon-tags"></span>';
        echo    ' Genres';
        echo   '</h3>';
        echo  '</div>';
        echo  '<div class="panel-body">';
    }
    
    /**
     * generates html content. footer of the widget
     */
    private function generateFoot() {
        echo  '</div>';
        echo '</div>';
    }
    
    /**
     * shows widget on a webpage
     */
    public function showChart() {
        $this->generateHead();
        $colorPointer = 0;
        $genresArray = $this->getGenresArray();
            for($i = 0; $i < $this->topX; $i ++){
            echo '<div class="progress">';
            echo  '<div class="progress-bar progress-bar-' . $this->colorsArray[$colorPointer] . '" role="progressbar" aria-valuenow="' . $genresArray[$i]['count'] . '" aria-valuemin="0" aria-valuemax="' . $genresArray[0]['count'] . '" style="width:' . $genresArray[$i]['count'] / $genresArray[0]['count'] * 100 . '%">';
            echo   $genresArray[$i]['name'];
            echo  '</div>';
            echo '</div>';
            $colorPointer ++;
            if($colorPointer == 4){
                $colorPointer = 0;
            }
        }
        $this->generateFoot();
    }
}
