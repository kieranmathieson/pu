<?php
/**
 * Confirm that the user wants to delete a person.
 * Receives id as a GET param.
 */
// Initialize the app.
// noinspection DuplicatedCode
require_once 'library/code/init.php';
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
// Set the page title shown in the header template.
$pageTitle = 'Confirm delete person|PU';
// noinspection DuplicatedCode
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
<h1>Confirm person deletion</h1>
<p><span class="font-weight-bold">User name: </span><?php print $person->getUserName(); ?></p>
<p><span class="font-weight-bold">First name: </span><?php print $person->getFirstName(); ?></p>
<p><span class="font-weight-bold">Last name: </span><?php print $person->getLastName(); ?></p>
<p><span class="font-weight-bold">Email: </span><?php print $person->getEmail(); ?></p>
<p>
    Are you <strong>sure</strong> you want to delete this person?
</p>
<p class="mt-3">
    <a class="btn btn-danger mr-8" title="All data about this person will be lost."
       href="person-delete.php?id=<?php print $personId; ?>" role="button">Delete</a>
    <a class="btn btn-secondary" title="Forgedaboudit"
       href="person-view.php?id=<?php print $personId; ?>" role="button">Cancel</a>
</p>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>