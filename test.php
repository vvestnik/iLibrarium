<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once './includes/functions.php';
        function classLoad($className){
            require './class/' . strtolower($className) . '.php';
        }
        spl_autoload_register('classLoad');
        
        
        ###########################################################
        ######     TODO:    CLASSES FOR FORMS AND TABLES     ######
        ###########################################################
        
//        $result = $link->query('SELECT * FROM user');
//        $record = $result->fetch();
//        echo $record['name'];
        
        //$myBook = new Book(1, 2, 3, 4, 5, 6);        
        
//        $myAuthor = new Author($link, 'Alexandr', 'Pushkin', 'Russian classic ');
//        echo $myAuthor->getDescription();
////        $myAuthor = new Author($link, 2);
//        $myAuthor1 = new Author($link, 3);
//        $authors = new BookAuthors();
//        $authors[] = $myAuthor;
//        $authors[] = $myAuthor1;
//        $authors[] = $myAuthor1;
////        $authors[] = 1;
//        echo '<pre>';
//        print_r($authors);
//        echo '</pre>';
        $alert = '';
//        $mybook = new Book($link, 3);
//        
//        echo '<pre>';
//        print_r($mybook);
//        echo '</pre>';
//        $myAvatar = new Avatar($link, 5);
//        echo $alert;
//        echo '<pre>';
//        print_r($myAvatar);
//        echo '</pre>';
//        $myUser = new User($link, 4);
//        echo $alert;
//        echo '<pre>';
//        print_r($myUser);
//        echo '</pre>';
        
//        $myGenre = new Genre($link, 'wtf', 'name');
//        
//        echo '<pre>';
//        print_r($myGenre);
//        echo '</pre>';
//        $myGenres = new BookGenres();
//        $myGenres = [3,4,5];
//        $myBook = new Book($link, 'The Fellowship of the Ring', '234h23e2', 3, 'wtfboooooooook', [2,3], [4,5]);
//        $myUser = new User($link, 'dff', 'sdf', 'vvvestnik', '234234@mail.ru', 'tyrw');
//        $myUser = new User($link, 4);
        //$myRole = new Role($link, 'Admin', 'name');
//        $myLogin = new Login($link, 'vvestnik', 'password1');
//        $myLogin->authUser();
//        $roles = new UserRoles();
//        $roles[1] = new Role($link, 4, 'id');
//        $myBook = new Book($link, 3);
//        $myChart = new GenresChart($link, 5);
//        $myBookInst = new BookInstance($link, 3, new Store($link, 1), 'add');
//        $myGenre = new Genre(2, 'id');
//        $book = new Book(3);
        $userby = new User(4);
        $userfor = new User(11);
        $placereg = new Store(1);
        $placetake = new Store(3);
        $book = new BookInstance(3, $placetake);
        $order = new Transaction($userby, $userfor, $placereg, $book, $placereg);
        $order->pickedUp();
        $order->returnBook($placetake);
        echo '<pre>';
        print_r($order);
        echo '</pre>';
        
        
        echo $alert;
        ?>
    </body>
</html>
