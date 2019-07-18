<div class="col-sm-3">

    <!-- Show only if not logged in --> 
    <!-- Login -->
    <?php
    if (!isset($_SESSION['aut_user'])) {
    ?>   
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-log-in"></span> 
                    Log In
                </h3>
            </div>
            <div class="panel-body">
                <form method="post" action="auth.php">
                    <div class="form-group">
                        <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-default">Log In</button><a href="register.php" class="btn btn-default pull-right">Register</a>
                </form>
            </div>
        </div>
    <?php
    }
    ?>
    <!-- Last order menu -->
    <?php
    #Is user logged in?
    if (isset($_SESSION['aut_user'])) {
        $ordersWidget = new OrderWidget($_SESSION['aut_user'], 2);
        $ordersWidget->showWidget();
    }
    ?>


    <!-- Generate random carousel for 3 books--> 
    <!-- Carousel --> 
    <?php
    $carousel = new CarouselWidget(4);
    $carousel->showWidget();
                        			  
    ?>    
    
    
    

</div><!--/Right Column -->