<?php
// Initialize the app.
require_once 'library/code/init.php';
// Set the page title shown in the header template.
$pageTitle = 'Reporting errors|PU';
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
        <h1>Reporting errors</h1>
        <p>Please take a screenshot of the page with the error, and
        send it to ????. Explain what you were doing when the error occurred. </p>
        <p>Please use the subject line "Doggos are great, but there's a PU error."</p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>