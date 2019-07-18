<?php
/**
 * Class for transaction (order)
 * Is used for reading and writing info about order to DB. Also, for showing the order
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class Transaction {
    
    /**
     * Id of the order
     *
     * @var int 
     */
    private $id;
    
    /**
     * Who placed the order
     *
     * @var User 
     */
    private $registeredBy;
    
    /**
     * For whom the order is placed
     *
     * @var User 
     */
    private $registeredFor;
    
    /**
     * The store, where the order was created
     *
     * @var Store 
     */
    private $placeRegistered;
    
    /**
     * The book to be borowed
     *
     * @var BookInstance 
     */
    private $bookInstance;
    
    /**
     * Timestamp of creating the order
     *
     * @var DateTime 
     */
    private $dateRegistered;
    
    /**
     * When the order is ready to be taken
     *
     * @var DateTime 
     */
    private $dateReady;
    
    /**
     * When the book should be returned
     *
     * @var DateTime 
     */
    private $dateBefore;
    
    /**
     * The store, where book will be ready to be picked up
     *
     * @var Store 
     */
    private $placeToTake;
    
    /**
     * When the book was picked up
     *
     * @var DateTime 
     */
    private $dateTaken;
    
    /**
     * When the book was returned
     *
     * @var DateTime 
     */
    private $dateReturned;
    
    /**
     * Where the book was returned
     *
     * @var Store
     */
    private $placeReturned;
    
    /**
     * Returns id of the order
     * 
     * @return int
     */
    function getId(): int {
        return $this->id;
    }

    /**
     * Returns the user, who has created the order
     * 
     * @return \User
     */
    function getRegisteredBy(): User {
        return $this->registeredBy;
    }

    /**
     * Returns the user, for whom the order is created
     * 
     * @return \User
     */
    function getRegisteredFor(): User {
        return $this->registeredFor;
    }

    /**
     * Returns the store, where the order was placed
     * 
     * @return \Store
     */
    function getPlaceRegistered(): Store {
        return $this->placeRegistered;
    }

    /**
     * Returns the book instance, which was borrowed
     * 
     * @return \BookInstance
     */
    function getBookInstance(): BookInstance {
        return $this->bookInstance;
    }

    /**
     * Returns date and time of registration of the order
     * 
     * @return \DateTime
     */
    function getDateRegistered(): DateTime {
        return $this->dateRegistered;
    }

    /**
     * Returns date and time when the book is ready to be picked up
     * 
     * @return \DateTime
     */
    function getDateReady(): DateTime {
        return $this->dateReady;
    }

    /**
     * Returns date before the book should be returned
     * 
     * @return \DateTime
     */
    function getDateBefore(): DateTime {
        return $this->dateBefore;
    }

    /**
     * Returns store, where the book was(will be) ready
     * 
     * @return \Store
     */
    function getPlaceToTake(): Store {
        return $this->placeToTake;
    }

    /**
     * Returns date and time when the book was picked up
     * 
     * @return \DateTime
     */
    function getDateTaken() {
        return $this->dateTaken;
    }

    /**
     * Returns the date and time of return of the book
     * 
     * @return \DateTime
     */
    function getDateReturned() {
        return $this->dateReturned;
    }

    /**
     * Returns store, where the book was returned
     * 
     * @return \Store
     */
    function getPlaceReturned() {
        return $this->placeReturned;
    }

    /**
     * Sets id of the order
     * 
     * @param int $id id of the order
     */
    function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets user who registered the order
     * 
     * @param User $registeredBy user who registered the order
     */
    function setRegisteredBy(User $registeredBy) {
        $this->registeredBy = $registeredBy;
    }

    /**
     * Sets the user, for whom the order is created
     * 
     * @param User $registeredFor the user, for whom the order is created
     */
    function setRegisteredFor(User $registeredFor) {
        $this->registeredFor = $registeredFor;
    }

    /**
     * Sets the store, where the order was placed
     * 
     * @param Store $placeRegistered the store, where the order was placed
     */
    function setPlaceRegistered(Store $placeRegistered) {
        $this->placeRegistered = $placeRegistered;
    }

    /**
     * Sets the book instance, which was borrowed
     * 
     * @param BookInstance $bookInstanceId the book instance, which was borrowed
     */
    function setBookInstance(BookInstance $bookInstance) {
        $this->bookInstance = $bookInstance;
    }

    /**
     * Sets date and time of registration of the order
     * 
     * @param DateTime $dateRegistered date and time of registration of the order
     */
    function setDateRegistered(DateTime $dateRegistered) {
        $this->dateRegistered = $dateRegistered;
    }

    /**
     * Sets date and time when the book is ready to be picked up
     * 
     * @param DateTime $dateReady date and time when the book is ready to be picked up
     */
    function setDateReady(DateTime $dateReady) {
        $this->dateReady = $dateReady;
    }

    /**
     * Sets date before the book should be returned
     * 
     * @param DateTime $dateBefore date before the book should be returned
     */
    function setDateBefore(DateTime $dateBefore) {
        $this->dateBefore = $dateBefore;
    }

    /**
     * Sets store, where the book was(will be) ready
     * 
     * @param Store $placeToTake store, where the book was(will be) ready
     */
    function setPlaceToTake(Store $placeToTake) {
        $this->placeToTake = $placeToTake;
    }

    /**
     * Sets date and time when the book was picked up
     * 
     * @param DateTime $dateTaken date and time when the book was picked up
     */
    function setDateTaken(DateTime $dateTaken) {
        $this->dateTaken = $dateTaken;
    }

    /**
     * Sets the date and time of return of the book
     * 
     * @param DateTime $dateReturned the date and time of return of the book
     */
    function setDateReturned(DateTime $dateReturned) {
        $this->dateReturned = $dateReturned;
    }

    /**
     * Sets store, where the book was returned
     * 
     * @param Store $placeReturned store, where the book was returned
     */
    function setPlaceReturned(Store $placeReturned) {
        $this->placeReturned = $placeReturned;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1 or 3 parameters.
     * In case of 1 parameter the object is build by Id of the post from DB
     * In case of 3 parameter the object is build by registeredBy, registeredFor and bookInstance
     * The option with 3 parameters is used when the post is being created and id is 
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
     * Constructs the object from DB based on id given
     * 
     * @param int $id id of the order (transaction)
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT registered_by, registered_for, place_registered, book_instance_id, date_registered, date_ready, date_before, place_to_take, date_taken, date_returned, place_returned FROM transaction WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setRegisteredBy(new User($record['registered_by']));
            $this->setRegisteredFor(new User($record['registered_for']));
            $this->setPlaceRegistered(new Store($record['place_registered']));
            $this->setBookInstance(new BookInstance($record['book_instance_id']));
            $this->setDateRegistered(new DateTime($record['date_registered']));
            $this->setDateReady(new DateTime($record['date_ready']));
            $this->setDateBefore(new DateTime($record['date_before']));
            $this->setPlaceToTake(new Store($record['place_to_take']));
            if($record['date_taken']){
                $this->setDateTaken(new DateTime($record['date_taken']));
            }
            if($record['date_returned']){
                $this->setDateReturned(new DateTime($record['date_returned']));
                $this->setPlaceReturned(new Store($record['place_returned']));
            }            
        }
        else{
            echo 'There\'s no comment with such Id';
        }
        $connection = null;
    }
    
    /**
     * Constructs the object based on input given. Writes to DB
     * 
     * @global string $alert
     * @param User $registeredBy who registered the order
     * @param User $registeredFor for whom the order is registered
     * @param BookInstance $bookInstance which book is borrowed
     */
    private function constructByInput(User $registeredBy, User $registeredFor, BookInstance $bookInstance){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setBookInstance($bookInstance);
        if ($this->bookInstance->getState()->getId() == 1){
            if($registeredBy->isEmployee()){
                $placeRegistered = $registeredBy->getStore();
            }
            else{
                $placeRegistered = new Store(2);
                $registeredFor = $registeredBy;
            }
            $this->setRegisteredBy($registeredBy);
            $this->setRegisteredFor($registeredFor);
            $this->setPlaceRegistered($placeRegistered);
            $this->setPlaceToTake($this->bookInstance->getStore());
            $link->exec("INSERT INTO transaction (registered_by, registered_for, place_registered, book_instance_id, book_instance_book_id, date_registered, place_to_take) VALUES('" . $this->registeredBy->getId() . "', '" . $this->registeredFor->getId() . "', '" . $this->placeRegistered->getId() . "', '" . $this->bookInstance->getInstanceId() . "', '" . $this->bookInstance->getId() . "', NOW(), '" . $this->placeToTake->getId() . "')");
            $this->setId($link->lastInsertId());
            $result = $link->query("SELECT date_registered FROM transaction WHERE id = '$this->id'");
            $record = $result->fetch();
            $this->setDateRegistered(new DateTime($record['date_registered']));            
            if($this->placeRegistered->getId() == 2 || $this->placeRegistered->getId() != $this->placeToTake->getId()){
                $pickupDate = clone $this->dateRegistered->add(new DateInterval('P1D'));
            }
            else{
                $pickupDate = clone $this->dateRegistered;
            }
            $this->setDateReady($pickupDate); 
            $dateReady = $this->dateReady;
            $dateBefore = clone $dateReady;
            $months = $this->bookInstance->getLoanTime();
            $interval_spec = 'P' . $months . 'M';
            $interval = new DateInterval($interval_spec);
            $dateBefore->add($interval);
            $this->setDateBefore($dateBefore);
            $link->exec("UPDATE transaction SET date_ready = '" . $this->dateReady->format("Y-m-d H:i:s") . "', date_before = '" . $this->dateBefore->format("Y-m-d H:i:s") . "' WHERE id = '" . $this->id . "'");         
            $this->bookInstance->setState(new State(2));
            $link->exec("UPDATE book_instance SET state_id = '" . $this->bookInstance->getState()->getId() . "' WHERE id = '" . $this->bookInstance->getInstanceId() . "'");
        }
        else{
            $alert .= 'Book is already reserved. Contact staff or admins';
        }
        $connection = null;
    }
    
    /**
     * sets state of the order as picked up:
     * the book is no more reserwed but borrowed.
     * Date and time added to order data
     * 
     * @global string $alert
     */
    public function pickedUp(){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        if(!$this->dateTaken){
            $link->exec("UPDATE transaction SET date_taken = NOW() WHERE id = '$this->id'");
            $result = $link->query("SELECT date_taken FROM transaction WHERE id = '$this->id'");
            $record = $result->fetch();
            $this->setDateTaken(new DateTime($record['date_taken']));
            $this->bookInstance->setState(new State(3));
            $link->exec("UPDATE book_instance SET state_id = '" . $this->bookInstance->getState()->getId() . "' WHERE id = '" . $this->bookInstance->getInstanceId() . "'");
        }
        else{
            $alert .= 'Already taken';
        }
        $connection = null;
    }
    
    /**
     * Sets order state as returned:
     * book is stored in the store and gets state In stock.
     * Date and time and place of return are written to DB
     * 
     * @global string $alert
     * @param Store $store place of return of the book
     */
    public function returnBook(Store $store){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        if(!$this->dateReturned){
            $this->setPlaceReturned($store);
            $link->exec("UPDATE transaction SET date_returned = NOW(), place_returned = '" . $this->placeReturned->getId() . "' WHERE id = '$this->id'");
            $result = $link->query("SELECT date_returned FROM transaction WHERE id = '$this->id'");
            $record = $result->fetch();
            $this->setDateReturned(new DateTime($record['date_returned']));
            $this->bookInstance->setState(new State(1));
            $link->exec("UPDATE book_instance SET state_id = '" . $this->bookInstance->getState()->getId() . "' WHERE id = '" . $this->bookInstance->getInstanceId() . "'");
        }
        else{
            $alert .= 'Already returned';
        }
        $connection = null;
    }
    
    /**
     * Returns Date and time given in input in string
     * 
     * @param DateTime $date DateTime object to be formatted to string
     * @return string
     */
    private function getFormattedDateTime(DateTime $date): string{
        return $date->format('H:i d.m.Y');
    }
    
    /**
     * Returns Date given in input in string
     * 
     * @param DateTime $date DateTime object to be formatted to string
     * @return string
     */
    private function getFormattedDate(DateTime $date): string{
        return $date->format('d.m.Y');
    }
    
    /**
     * Returns Date and time of registraction of the order in string
     * 
     * @return string
     */
    public function getFormattedRegistered(): string{
        return $this->getFormattedDateTime($this->getDateRegistered());
    }
    
    /**
     * Retuns "Ready for delivery date" in string
     * 
     * @return string
     */
    public function getFormattedReady(): string{
        return $this->getFormattedDate($this->getDateReady());
    }
    
    /**
     * Returns the latest date of return in string
     * 
     * @return string
     */
    public function getFormattedBefore(): string{
        return $this->getFormattedDate($this->getDateBefore());
    }
    
    /**
     * Returns the pickup date in string
     * 
     * @return string
     */
    public function getFormattedTaken(): string{
        return $this->getFormattedDateTime($this->getDateTaken());
    }
    
    /**
     * Returns the return date in string
     * 
     * @return string
     */
    public function getFormattedReturned(): string{
        return $this->getFormattedDateTime($this->getDateReturned());
    }
    
    /**
     * Shows a button that leads to PDF
     */
    public function viewPDF(){
        echo '<h5><a href="Orders/Order_' . $this->getId() . '_' . md5($this->registeredBy->getFullName() . $this->getId() . 'haha') . '.pdf"><span class="label label-primary">View order as PDF</span></a></h5>';
    }
    
    /**
     * Generates the PDF with order info and saves it to the server
     */
    public function generatePDF() {
        $pdf = new FPDF;
        $pdf->SetFont('Helvetica', 'B', 35);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetTopMargin(40);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(1);
        $pdf->AddPage();
        $pdf->Image('images/logo.png', 100, 10, 100, 0);
        $pdf->Line(10, 30, 200, 30);
        $text = 'Order #' . $this->id . "\n\n";
        $pdf->Write(15, $text);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(10, 65, 200, 65);
        
        $text = "Store:\n";
        $pdf->SetFontSize(20);
        $saveX = $pdf->GetX();
        $saveY = $pdf->GetY();
        $pdf->Write(10, $text);
        $pdf->SetFont('','', 15);
        $text = $this->placeRegistered->getName() . "\n" . $this->placeRegistered->getAddress() . "\n";
        $pdf->MultiCell(90, 7, utf8_decode(html_entity_decode($text)));
        $y1 = $pdf->GetY() + 5;
        
        $text = "Place of delivery:\n";
        $pdf->SetFont('', 'B', 20);
        $pdf->SetXY($saveX + 100, $saveY);
        $pdf->Write(10, $text);
        $pdf->SetFont('','', 15);
        $text = $this->placeToTake->getName() . "\n";      
        $pdf->SetX($saveX +100);
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $text = $this->placeToTake->getAddress() . "\n";
        $pdf->SetX($saveX +100);
        $pdf->MultiCell(90, 7, utf8_decode(html_entity_decode($text)));
        $y2 = $pdf->GetY() + 5;
        if($y1 > $y2){
            $y = $y1;
        }
        else{
            $y = $y2;
        }        
        $pdf->Line(10, $y , 200, $y);
        
        if($this->placeRegistered->getId() != 2){
            $text = "Specialist:\n";
            $pdf->SetFont('', 'B', 20);
            $pdf->SetY($y + 5);
            $pdf->Write(10, $text);
            $pdf->SetFont('','', 15);
            $text = $this->registeredBy->getFullName() . "\n" . $this->registeredBy->getEmail() . "\n";
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
            $y = $pdf->GetY() + 5;
            $pdf->Line(10, $y, 200, $y);
        }
        
        $text = "Customer:\n";
        $pdf->SetFont('', 'B', 20);
        $pdf->SetY($y + 5);
        $pdf->Write(10, $text);
        $pdf->SetFont('','', 15);
        $text = $this->registeredFor->getFullName() . "\n" . $this->registeredFor->getEmail() . "\n";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $y = $pdf->GetY() + 5;
        $pdf->Line(10, $y, 200, $y);
        
        $text = "Book:\n";
        $pdf->SetFont('', 'B', 20);
        $pdf->SetY($y + 5);
        $pdf->Write(10, $text);
        $pdf->SetFont('','', 15);
        $text = $this->bookInstance->getName() . "\n";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $text = '';
        foreach ($this->bookInstance->getAuthorsArray() as $author){
            $text .= $author . "\n";
        }
        $pdf->SetFont('', 'I');
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $y = $pdf->GetY() + 5;        
        $pdf->Line(10, $y, 200, $y);
        
        $text = "Important dates:\n";
        $pdf->SetFont('', 'B', 20);
        $pdf->SetY($y + 5);
        $pdf->Write(10, $text);
        $pdf->SetFont('','', 15);
        $text = "Registration: ";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $pdf->SetX($saveX + 37);
        $text = $this->getFormattedRegistered();
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $pdf->SetX($saveX + 100);
        $text = "Delivery: ";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $pdf->SetX($saveX + 128);
        $text = $this->getFormattedReady() . "\n";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $text = "Return before: ";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $pdf->SetX($saveX + 37);
        $text = $this->getFormattedBefore();
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $pdf->SetX($saveX + 100);
        $text = '';
        if($this->getDateTaken()){
            $text = "Picked up: ";
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
            $pdf->SetX($saveX + 128);
            $text = $this->getFormattedTaken();
        }
        $text .= "\n";
        $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        $y = $pdf->GetY() + 5;
        $pdf->Line(10, $y, 200, $y);
        
        if($this->getDateReturned()){
            $text = "Return:\n";
            $pdf->SetFont('', 'B', 20);
            $pdf->SetY($y + 5);
            $pdf->Write(10, $text);
            $pdf->SetFont('','', 15);
            $text = "Date: ";
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
            $pdf->SetX($saveX + 37);
            $text = $this->getFormattedReturned();
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
            $pdf->SetX($saveX + 100);
            $text = "Place: ";
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
            $pdf->SetX($saveX + 128);
            $text = $this->getPlaceReturned()->getName() . "\n";
            $pdf->Write(7, utf8_decode(html_entity_decode($text)));
        }
        
        
        $pdf->SetLineWidth(1);
        $pdf->Line(10, 287, 200, 287);
        
        $pdf->Output('F', './Orders/Order_' . $this->getId() . '_' . md5($this->registeredBy->getFullName() . $this->getId() . 'haha') . '.pdf');
    }
}
