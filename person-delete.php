<?php
/**
 * Delete a person.
 * Receives id as a GET param.
 */
// Initialize the app.
// noinspection DuplicatedCode
require_once 'library/code/init.php';
/** @var $currentUser Person */
global $currentUser;
// Check access to this page.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE);
checkAccess($accessOk);
// Make sure there is a person id that's real.
$personIdOk = isPersonIdInGetOK();
if (!$personIdOk) {
    accessDenied();
}
$personId = $_GET['id'];
// Make sure this is not the current user's id.
// Can't erase your own record.
if ($currentUser->getId() == $personId) {
    accessDenied();
}
// Load the person record.
$person = new Person();
$person->load($personId);
// Delete the record. Returns an error message.
$errorMessage = $person->delete();
// Set the page title shown in the header template.
$pageTitle = 'Delete person|PU';
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
        <h1>Person deletion</h1>
        <p><span class="font-weight-bold">User name: </span><?php print $person->getUserName(); ?></p>
        <p><span class="font-weight-bold">First name: </span><?php print $person->getFirstName(); ?></p>
        <p><span class="font-weight-bold">Last name: </span><?php print $person->getLastName(); ?></p>
        <p><span class="font-weight-bold">Email: </span><?php print $person->getEmail(); ?></p>
        <p class="lead">Result</p>
        <p><?php
            if ($errorMessage == '') {
                print 'Successfully deleted';
            }
            else {
                print 'Something may have gone wrong. Error message: ' & $errorMessage;
            }
        ?></p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>