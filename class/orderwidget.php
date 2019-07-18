<?php

/**
 * Class for order widget
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class OrderWidget {
    
    /**
     * How many orders to show in the widget?
     *
     * @var int 
     */
    private $ordersCount;
    
    /**
     * User, whose orders are shown
     *
     * @var User 
     */
    private $user;
    
    /**
     * Sets user
     * 
     * @param User $user User
     */
    private function setUser(User $user) {
        $this->user = $user;
    }
    
    /**
     * Sets max number of the orders
     * 
     * @param int $ordersCount max number of orders
     */
    private function setOrdersCount(int $ordersCount) {
        $this->ordersCount = $ordersCount;
    }

    /**
     * Constructs order widget for a user and max order number
     * 
     * @param User $user user 
     * @param int $ordersCount max number of orders to show
     */
    public function __construct(User $user, int $ordersCount) {
        $this->setOrdersCount($ordersCount);
        $this->setUser($user);
    }
    
    /**
     * Returns array for generating orders widget or flase, if there's no orders on the account
     * 
     * @return boolean or array
     */
    private function getOrdersArray(){
        $connection = new Connection();
        $link = $connection->connect();
        $my_id = $this->user->getId();
        $count = $link->query("SELECT count(*) FROM transaction WHERE registered_for = '$my_id'")->fetchColumn();
        if($count > 0){
            $result = $link->query("SELECT id FROM transaction WHERE registered_for = '$my_id' ORDER BY date_registered DESC LIMIT $this->ordersCount");
            $orders = [];
            while($record = $result->fetch()){
                $orders[] = $record;
            }
            $connection = null;
            return $orders;
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * generates HTML tags for header of the widget
     */
    private function generateHead(){
        echo '<div class="panel panel-default">';
        echo  '<div class="panel-heading">';
        echo   '<h1 class="panel-title"><span class="glyphicon glyphicon-flag"></span>Your last orders</h1>';
        echo  '</div>';
        echo  '<div class="panel-body">';
    }
    
    /**
     * Shows the widget
     */
    public function showWidget(){
        $this->generateHead();
        
        #there are orders, show last id
        if($this->getOrdersArray()){
            foreach($this->getOrdersArray() as $record){
                echo '<p>Your order:<br>#' . $record['id'];
                echo '</p>';
                echo '<p><a href="order.php?id=' . $record['id'] . '" class="btn btn-default">Show me!</a></p>';
            }
        }
        #Ooops! no orders
        else{
            echo '<p>You have no orders yet</p>';
        }
        
        $this->generateFoot();
    }
    
    /**
     * Generates Html tags for footer of the widget
     */
    private function generateFoot(){
        echo  '</div>';
        echo '</div>';
    }        
}
