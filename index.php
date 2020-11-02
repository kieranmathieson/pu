<?php
// Initialize the app.
require_once 'library/code/init.php';
// No access check for this page. All can see it.
// $accessOk = isCurrentUserHasRole('faculty') || isCurrentUserHasRole('staff');
// checkAccess($accessOk);
// Set the page title shown in the header template.
$pageTitle = 'PU';
?><!doctype html>
<html lang="en">
    <head>
        <?php
        require_once 'library/page-elements/head.php';
        ?>
    </head>
    <body>
        <?php
        require_once 'library/page-elements/page-top.php';
        ?>
        <h1>Welcome</h1>
        <p>Welcome to PU, the home of the Stinking Snakes.</p>
        <p><img src="images/snake.png" alt="Stinking snake"> </p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>