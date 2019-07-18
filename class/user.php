<?php

/**
 * Class for user
 * Is used for reading and writing info about image to DB. Also, for authorisation
 *
 * @author vvestnik
 * @version 1.0
 * @category Advanced PHP Project
 */
class User {
    
    /**
     * Id of the user
     *
     * @var int 
     */
    protected $id;
    
    /**
     * Name of the user
     *
     * @var string 
     */
    protected $name;
    
    /**
     * Surname of the user
     *
     * @var string 
     */
    protected $surname;
    
    /**
     * Nickname of the user
     *
     * @var string 
     */
    protected $nickname;
    
    /**
     * Email of the user
     *
     * @var string 
     */
    protected $email;
    
    /**
     * Hashed password of the user
     *
     * @var string 
     */
    private $password;
    
    /**
     * Avatar of the user
     *
     * @var Avatar 
     */
    protected $avatar;
    
    /**
     * Roles of the user
     *
     * @var UserRoles 
     */
    private $roles;
    
    /**
     * If employed, id
     *
     * @var int 
     */
    private $staffId;
    
    /**
     * Place of employment
     *
     * @var Store 
     */
    private $store;
    
    /**
     * Returns place of employment
     * 
     * @return \Store
     */
    public function getStore(): Store {
            return $this->store;
    }

    /**
     * Sets place of employment
     * 
     * @param Store $store Store
     */
    private function setStore(Store $store) {
        $this->store = $store;
    }

        
    /**
     * Sets staffId
     * 
     * @param int $staffId id of employee
     */
    private function setStaffId(int $staffId) {
        $this->staffId = $staffId;
    }

    /**
     * Returns staffId
     * 
     * @return int
     */
    function getStaffId(): int {
        return $this->staffId;
    }

        
    /**
     * Sets the id of the user
     * 
     * @param int $id id of the user
     */
    protected function setId(int $id) {
        $this->id = $id;
    }

    /**
     * Sets the name of the user
     * 
     * @param string $name Name of the user
     */
    protected function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Sets the surname of the user
     * 
     * @param string $surname Surname of the user
     */
    protected function setSurname(string $surname) {
        $this->surname = $surname;
    }

    /**
     * Sets the nickname of the user
     * 
     * @param string $nickname Nickname of the user
     */
    protected function setNickname(string $nickname) {
        $this->nickname = $nickname;
    }

    /**
     * Sets email of the user
     * 
     * @param string $email Email of the user
     */
    protected function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * Sets password of the user
     * 
     * @param string $password Hashed password of the user
     */
    private function setPassword(string $password) {
        $this->password = $password;
    }

    /**
     * Sets the avatar of the user
     * 
     * @param Avatar $avatar Avatar of the user
     */
    private function setAvatar(Avatar $avatar) {
        $this->avatar = $avatar;
    }
    
    /**
     * Sets roles of the user
     * 
     * @param UserRoles $roles Object of roles of the user
     */
    private function setRoles(UserRoles $roles) {
        $this->roles = $roles;
    }

    /**
     * Returns user's roles
     * 
     * @return \UserRoles
     */
    private function getRoles(): UserRoles {
        return $this->roles;
    }

    /**
     * Returns id of the user
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * Returns name of the user
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Returns surname of the user
     * 
     * @return string
     */
    public function getSurname(): string {
        return $this->surname;
    }

    /**
     * Returns nickname of the user
     * 
     * @return string
     */
    public function getNickname(): string {
        return $this->nickname;
    }

    /**
     * Returns email of the user
     * 
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Returns hashed password of the user
     * 
     * @return string
     */
    private function getPassword(): string {
        return $this->password;
    }

    /**
     * Returns Avatar object of the user
     * 
     * @return \Avatar
     */
    public function getAvatar(): Avatar {
        return $this->avatar;
    }

    /**
     * Constructor of the class
     * 
     * Accepts 1, 2 or 5 parameters.
     * In case of 1 parameter the object is build by Id of the user from DB
     * In case of 2 parameter the object is build by Id of the user from DB and his avatar
     * In case of 5 parameter the object is build by name, surname, nickname, email and password
     * The option with 5 parameters is used when the user is being created and id is 
     * unknown yet
     */
    public function __construct() {
        $numargs = func_num_args();
        if($numargs == 1){
            $this->constructById(func_get_arg(0));
        }
        elseif($numargs == 2){
            $this->constructByIdAvatar(func_get_arg(0), func_get_arg(1));
        }
        elseif($numargs == 5){
            $this->constructByInput(func_get_arg(0), func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));          
        }
        else{
            throw new \InvalidArgumentException('Incorrect number of arguments');
        }
    }
    
    /**
     * Constructs a class with id based on info from DB
     * 
     * @param int $id id of the author
     */
    private function constructById(int $id){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT name, surname, nickname, email, password, avatar_id FROM user WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setName($record['name']);
            $this->setSurname($record['surname']);
            $this->setNickname($record['nickname']);
            $this->setEmail($record['email']);
            $this->setPassword($record['password']);
            $this->setAvatar(new Avatar($record['avatar_id'], $this));
            $this->setRolesFromDB();
            $result = $link->query("SELECT * FROM staff WHERE user_id = '$this->id'");
            if($record = $result->fetch()){
                $this->setStaffId($record['id']);
                $this->setStore(new Store($record['store_id']));
            }
        }
        else{
            echo 'There\'s no user with such Id';
        }
        $connection = null;
    }
    /**
     * Constructs a class with id based on info from DB
     * 
     * @param int $id id of the user
     * @param Avatar $avatar avatar of the user
     */
    private function constructByIdAvatar(int $id, Avatar $avatar){
        $connection = new Connection();
        $link = $connection->connect();
        $this->setId($id);
        $result = $link->query("SELECT name, surname, nickname, email, password, avatar_id FROM user WHERE id = '$this->id'");
        $record = $result->fetch();
        if($record){
            $this->setName($record['name']);
            $this->setSurname($record['surname']);
            $this->setNickname($record['nickname']);
            $this->setEmail($record['email']);
            $this->setPassword($record['password']);
            $this->setAvatar($avatar); 
            $this->setRolesFromDB();
            $result = $link->query("SELECT * FROM staff WHERE user_id = '$this->id'");
            if($record = $result->fetch()){
                $this->setStaffId($record['id']);
                $this->setStore(new Store($record['store_id']));
            }
            
        }
        else{
            echo 'There\'s no user with such Id';
        }
        $connection =null;
    }
    /**
     * read all roles from db to the class
     */
    private function setRolesFromDB(){
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT role_id FROM user_has_role WHERE user_id = '$this->id'");
        $roles = new UserRoles();
        foreach ($result as $record){
            $roles[] = new Role('id', $record['role_id']);
        }
        $this->setRoles($roles);
        $connection = null;
    }


    /**
     * Constructs the class based on input and writes data to DB
     * 
     * @global type $alert
     * @param string $name Name of the user
     * @param string $surname Surname of the user
     * @param string $nickname Username of the user
     * @param string $email Email of the user
     * @param string $password Hashed password of the user
     */
    private function constructByInput(string $name, string $surname, string $nickname, string $email, string $password){
        global $alert;
        $connection = new Connection();
        $link = $connection->connect();
        $this->setNickname(clean($nickname));
        $result = $link->query("SELECT * FROM user WHERE nickname = '$this->nickname'");
        if(!$result->fetch()){
            if(is_mail($email)){  
                $this->setEmail(clean($email));
                $result = $link->query("SELECT * FROM user WHERE email = '$this->email'");
                if(!$result->fetch()){
                    $this->setName(clean($name));
                    $this->setSurname(clean($surname));
                    $this->setPassword(password_hash($password, PASSWORD_DEFAULT));
                    $link->exec("INSERT INTO user (name, surname, nickname, email, password) VALUES('$this->name', '$this->surname', '$this->nickname', '$this->email', '$this->password')");
                    $this->setId($link->lastInsertId());
                    $this->setAvatar(new Avatar(5));
                    $link->exec("INSERT INTO user_has_role (user_id) VALUES ('" . $this->getId() . "')");
                    $this->setRolesFromDB();
                }
                else{
                    $alert .= 'User with the email already exists';
                }
            }
            else{
                $alert .= 'Please enter a valid email';
            }
        }
        else{
            $alert .= 'User with the nickname already exists';
        }
        $connection = null;
    }
    
    /**
     * Returns array of role names
     * 
     * @return array
     */
    public function getRolesArray(): array {
        $roles = array();
        foreach ($this->roles as $role){
            $roles[] = $role->getRoleName();
        }
        return $roles;
    }
    
    /**
     * Returns array of role ids
     * 
     * @return array
     */
    public function getRoleIdsArray(): array {
        $roles = array();
        foreach ($this->roles as $role){
            $roles[] = $role->getId();
        }
        return $roles;
    }
    
    /**
     * Remove avatar and password from the class to write to session
     */
    public function prepareForSession(){
        $this->avatar = null;
        $this->password = null;
    }
    
    /**
     * If given role is in the class do nothing, if not, redirrect to index page
     * 
     * @param Role $role
     */
    public function checkRole(Role $role){
        $grant = false;
        foreach ($this->getRoles() as $userRole){
            if($userRole == $role){
                $grant = true;
            }
        }
        if(!$grant){            
            $_SESSION['login_message'] = 'You do not have required permissions to acces the page';
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * If role is in the class return true, otherwise return false
     * 
     * @param Role $role
     * @return boolean
     */
    public function checkRoleNoRedirrect(Role $role){
        $grant = false;
        foreach ($this->getRoles() as $userRole){
            if($userRole == $role){
                $grant = true;
            }
        }
        return $grant;
    }
    
    /**
     * Return filename of avatar
     * 
     * @return string
     */
    public function getAvatarFilename(){
        return $this->getAvatar()->getFilename();
    }
    
    /**
     * If none of given roles is in the class, redirrect to index page
     * 
     * @param Role $role1
     * @param Role $role2
     */
    public function checkTwoRoles(Role $role1, Role $role2){
        $grant = false;
        foreach ($this->getRoles() as $userRole){
            if($userRole == $role1 || $userRole == $role2){
                $grant = true;
            }
        }
        if(!$grant){            
            $_SESSION['login_message'] = 'You do not have required permissions to acces the page';
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Is user an employee? Returns true/false
     * 
     * @return boolean
     */
    public function isEmployee(){
        if(isset($this->staffId)){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
    
    /**
     * Edit user based on given data with db write.
     * 
     * @param string $name
     * @param string $surname
     */
    public function edit(string $name, string $surname){
        $this->setName($name);
        $this->setSurname($surname);
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("UPDATE user SET name = '$this->name', surname = '$this->surname' WHERE id = '$this->id'");
        $connection = null;
    }
    
    /**
     * Employ the user in the given store, with DB write
     * 
     * @param Store $store
     */
    public function employ(Store $store){
        $this->setStore($store);
        $connection = new Connection();
        $link = $connection->connect();
        $result = $link->query("SELECT * FROM staff WHERE user_id = '$this->id'");
        if(!$record = $result->fetch()){
            $link->exec("INSERT INTO staff (user_id, store_id) VALUES ('$this->id','" . $this->getStore()->getId() . "')");
            $this->setStaffId($link->lastInsertId());
        }
        else{
            $this->setStaffId($record['id']);
            $link->exec("UPDATE staff SET store_id = '" . $this->getStore()->getId() . "' WHERE id = '$this->staffId'");
        }
        $connection = null;
    }
    
    /**
     * Unemploy the user, delete from db
     */
    public function unemploy(){
        unset($this->staffId);
        unset($this->store);
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("DELETE FROM staff WHERE user_id = '$this->id'");
        $connection = null;
    }
    
    /**
     * Assign new roles to the user
     * 
     * @param array $roles
     */
    public function editRoles(array $roles){
        unset($this->roles);
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("DELETE FROM user_has_role WHERE user_id = '$this->id'");
        $newRoles = new UserRoles;
        foreach ($roles as $role){
            $newRoles[] = new Role('id', $role);
            $link->exec("INSERT INTO user_has_role (user_id, role_id) VALUES ('$this->id', '$role')");
        }
        $this->setRoles($newRoles);
        $connection = null;
    }
    
    /**
     * Set avatar then update info in DB
     * 
     * @param Avatar $avatar
     */
    public function setAvatarDbUpdate(Avatar $avatar){
        $this->setAvatar($avatar);
        $connection = new Connection();
        $link = $connection->connect();
        $link->exec("UPDATE user SET avatar_id = '" . $avatar->getId() . "' WHERE id = '" . $this->id . "'");
        $connection = null;
    }
    
    /**
     * Return id of the store, where the usere is employed
     */
    public function getStoreId(){
        $this->getStore()->getId();
    }
    
    public function getFullName(): string {
        return $this->name . ' ' . $this->surname;
    }
}
