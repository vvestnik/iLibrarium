<!-- Left column -->
<div class="col-sm-3">
    <!-- Tasks -->
    <?php
    if (isset($_SESSION['aut_user'])) {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><span class="glyphicon glyphicon-flash"></span> Tasks</h1>
        </div>
        <div class="list-group">
            <!-- Show to blog-writers -->
            <?php
            if (in_array('Blog-writer', $_SESSION['aut_user']->getRolesArray())) {
                ?>
                <a href="new_post.php" class="list-group-item">Write a post</a>
                <?php
            }
            ?>
            <!-- Show to store-sellers -->
            <?php
            if (in_array('Store-seller', $_SESSION['aut_user']->getRolesArray())) {
                ?>
                <a href="image_upload.php" class="list-group-item">Upload an image</a>
                <a href="new_author.php" class="list-group-item">Register an author</a>
                <a href="new_book.php" class="list-group-item">Register a book</a>
                <a href="new_genre.php" class="list-group-item">Register a genre</a>                                 
            <?php
            }
            ?>
            <!-- Show to all -->
            <a href="orders.php" class="list-group-item">Orders</a>  
            <!-- Show to admins -->
            <?php
            if (in_array('Admin', $_SESSION['aut_user']->getRolesArray())) {
            ?>
                <a href="avatar_upload.php" class="list-group-item">Upload an avatar</a>
            <?php
            }
            ?>
            <!-- Show to HR-workers and admins -->
            <?php
            if (in_array('HR-worker', $_SESSION['aut_user']->getRolesArray()) || in_array('Admin', $_SESSION['aut_user']->getRolesArray())) {
            ?>
                <a href="admin.php" class="list-group-item">Administrate users</a>
            <?php
            }
            ?>
            <!-- Show to Regional managers -->
            <?php
            if (in_array('Regional-manager', $_SESSION['aut_user']->getRolesArray())) {
            ?>
                <a href="new_store.php" class="list-group-item">Add new store</a>
            <?php
            }
            ?>

        </div>
    </div>
    <?php
    }
    ?>


    <!-- Genres chart -->
    <?php
    $genresChart = new GenresChart(4);
    $genresChart->showChart();
    ?>
    <!-- Thumbnails --> 
    <?php
    $randomThumbnails = new RandomThumbnails(3);
    $randomThumbnails->showThumbnails();
    ?>

</div><!--/Left Column-->