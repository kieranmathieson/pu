<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">PU</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="courses.php">Courses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="people.php">People</a>
            </li>
        </ul>
        <?php
        global $currentUser;
        if (is_null($currentUser)) {
            ?>
            <div class="nav-item float-right">
                <a id="login-link" href="login.php">Login</a>
            </div>
            <?php
        }
        ?>
    </div>
</nav>
