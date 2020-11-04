<?php
/**
 * View own account.
 */
// Initialize the app.
require_once 'library/code/init.php';
global $currentPerson;
// Check access to this page.
$accessOk =
       isCurrentUserHasRole(ADMIN_ROLE)
    || isCurrentUserHasRole(STAFF_ROLE)
    || isCurrentUserHasRole(FACULTY_ROLE)
    || isCurrentUserHasRole(STUDENT_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Your account|PU';
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
<h1>Your account</h1>
<div class="mt-2 mb-2">
    <a class="btn btn-primary"
       href="own-account-edit.php"
       role="button">Edit</a>
</div>
<p><span class="font-weight-bold">User name: </span><?php print $currentPerson->getUserName(); ?></p>
<p><span class="font-weight-bold">First name: </span><?php print $currentPerson->getFirstName(); ?></p>
<p><span class="font-weight-bold">Last name: </span><?php print $currentPerson->getLastName(); ?></p>
<p><span class="font-weight-bold">Email: </span><?php print $currentPerson->getEmail(); ?></p>
<p><span class="font-weight-bold">Telephone: </span><?php print $currentPerson->getTelephone(); ?></p>
<p><span class="font-weight-bold">About</span></p>
<p><?php print $currentPerson->getAbout(); ?></p>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>