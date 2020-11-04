<?php
/**
 * View one person record. Expect id passed as GET.
 */
// Initialize the app.
require_once 'library/code/init.php';
// Check access to this page.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Admin|PU';
// Uncomment global if needed for the page's logic.
/** @var $currentUser Person */
// global $currentUser;
/** @var $dbConnector DbConnector */
//global $dbConnector;
// Is there a person id in the URL?
$personIdOk = isPersonIdInGetOK();
if (!$personIdOk) {
    accessDenied('person-view: bad id');
}
$personId = $_GET['id'];
$person = new Person();
$person->load($personId);
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
        <h1>Person view</h1>
        <?php
        if ($currentUser->isAdmin()) {
            ?>
            <div class="mt-2 mb-2">
                <a class="btn btn-primary"
                   href="person-edit.php?id=<?php print $personId; ?>"
                   role="button">Edit</a>
                <a class="btn btn-primary"
                   href="person-confirm-delete.php?id=<?php print $personId; ?>"
                   role="button">Delete</a>
            </div>
        <?php
        }
        ?>
        <p><span class="font-weight-bold">User name: </span><?php print $person->getUserName(); ?></p>
        <p><span class="font-weight-bold">First name: </span><?php print $person->getFirstName(); ?></p>
        <p><span class="font-weight-bold">Last name: </span><?php print $person->getLastName(); ?></p>
        <p><span class="font-weight-bold">Email: </span><?php print $person->getEmail(); ?></p>
        <p><span class="font-weight-bold">Telephone: </span><?php print $person->getTelephone(); ?></p>
        <p><span class="font-weight-bold">About</span></p>
        <p><?php print $person->getAbout(); ?></p>
        <p><span class="font-weight-bold">Admin? </span><?php print $person->isAdmin() ? 'Yes' : 'No'; ?></p>
        <p><span class="font-weight-bold">Staff? </span><?php print $person->isStaff() ? 'Yes' : 'No'; ?></p>
        <p><span class="font-weight-bold">Faculty? </span><?php print $person->isFaculty() ? 'Yes' : 'No'; ?></p>
        <p><span class="font-weight-bold">Student? </span><?php print $person->isStudent() ? 'Yes' : 'No'; ?></p>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>