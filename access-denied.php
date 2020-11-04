<?php
// Initialize the app.
require_once 'library/code/init.php';
// Log it.

// Set the page title shown in the header template.
$pageTitle = 'Access denied|PU';
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
        <h1>Access denied</h1>
        <p>Sorry, you don't have access to that function.</p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>