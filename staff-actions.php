<?php
// Initialize the app.
require_once 'library/code/init.php';
// Only admins can access.
$accessOk = isCurrentUserHasRole(STAFF_ROLE);
checkAccess($accessOk);
// Set the page title shown in the header template.
$pageTitle = 'Staff actions|PU';
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
        <h1>Staff actions</h1>
        <ul>
            <li><a href="list-courses.php">List courses</a></li>
            <li><a href="add-course.php">Add new course</a></li>
        </ul>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>