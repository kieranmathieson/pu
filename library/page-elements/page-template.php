<?php
/**
 * This is the page template. To make a new page:
 * - Copy this template.
 * - Adjust the role check code.
 * - Set $pageTitle.
 * - Uncomment globals if needed.
 */
// Initialize the app.
require_once 'library/code/init.php';
// Check access to this page. Adjust the roles.
$accessOk = isCurrentUserHasRole('faculty') || isCurrentUserHasRole('staff');
checkAccess($accessOk);
// Set the page title shown in the header template.
$pageTitle = 'Admin|PU';
// Uncomment global if needed for the page's logic.
/** @var $currentUser Person */
// global $currentUser;
/** @var $dbConnector DbConnector */
//global $dbConnector;
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
        <h1>Something</h1>
        <p>Some stuff</p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>