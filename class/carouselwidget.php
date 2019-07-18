<?php

/**
 * Class for carousel widget
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class CarouselWidget {
    
    /**
     * How many books will be in the carousel
     *
     * @var int 
     */
    private $booksCount;
    
    /**
     * Sets the number of books in the widget
     * 
     * @param int $booksCount Number of books in the carousel
     */
    private function setBooksCount(int $booksCount) {
        $this->booksCount = $booksCount;
    }
    
    /**
     * Class for carousel widget
     * 
     * @param int $booksCount Number of books
     */
    public function __construct(int $booksCount) {
        $this->setBooksCount($booksCount);
    }
    
    /**
     * Returns array of ids from db to display in the widget
     * 
     * @return array
     */
    private function getBooksArray(): array{
        $books = array();
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT id FROM book ORDER BY RAND() LIMIT $this->booksCount");        
        while ($record = $result->fetch()){
            $books[] = new Book($record['id']);
        } 
        $connection = null;
        return $books;
    }
    
    /**
     * Generates html content. Header of the widget
     */
    private function generateHead(){
        echo '<h3><span class="glyphicon glyphicon-random"></span> Random books</h3>';
        echo '<div id="side-carousel" class="carousel slide" data-ride="carousel">';
        echo  '<ol class="carousel-indicators">';
        echo   '<li data-target="#side-carousel" data-slide-to="0" class="active"></li>';
        for($i = 1; $i < $this->booksCount; $i ++){
            echo   '<li data-target="#side-carousel" data-slide-to="' . $i . '"></li>';
        }
        echo  '</ol>';
        echo '<div class="carousel-inner" role="listbox">';
    }
    
    /**
     * shows widget on webpage
     */
    public function showWidget(){
        $this->generateHead();
        
        $books = $this->getBooksArray();
        for($i = 0; $i < $this->booksCount; $i ++){ 
            if($i == 0){
                echo '<div class="item active">';
            }
            else{
                echo '<div class="item">';
            }
            echo  '<a href="book_view.php?id=' . $books[$i]->getId() . '">';
            echo   '<img class="img-responsive" src="images/carousel/' . $books[$i]->getDefImage()->getFilename() . '" alt="">';
            echo  '</a>';
            echo  '<div class="carousel-caption">';
            echo   '<h3>' . $books[$i]->getName() . '</h3>';
            $authors = '';            
            foreach ($books[$i]->getAuthorsArray() as $authorFullName){
                $authors .= $authorFullName . '<br>';
            }
            echo   '<p>' . $authors . '</p>';
            echo  '</div>';
            echo '</div>';            
        } 
        $this->generateFoot();
    }
    
    /**
     * generates html content. footer of the widget
     */
    private function generateFoot(){
        echo  '</div>';
        echo  '<a class="left carousel-control" href="#side-carousel" role="button" data-slide="prev">';
        echo   '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>';
        echo   '<span class="sr-only">Previous</span>';
        echo  '</a>';
        echo  '<a class="right carousel-control" href="#side-carousel" role="button" data-slide="next">';
        echo   '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>';
        echo   '<span class="sr-only">Next</span>';
        echo  '</a>';
        echo '</div>';
    }
}