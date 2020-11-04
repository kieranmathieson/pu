<?php
/**
 * Edit own account.
 * This page validates and saves, as well as showing the form.
 *
 * $currentUser already has data in it for the current user.
 */
// Initialize the app.
require_once 'library/code/init.php';
global $currentUser;
// Check access to this page.
$accessOk =
    isCurrentUserHasRole(ADMIN_ROLE)
    || isCurrentUserHasRole(STAFF_ROLE)
    || isCurrentUserHasRole(FACULTY_ROLE)
    || isCurrentUserHasRole(STUDENT_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Edit your account|PU';
// Init changeable person fields to MT.
$firstName = '';
$lastName = '';
$email = '';
$telephone = '';
$about = '';
$errorMessage = '';
// Is there POST data? True if there was a validation error when trying to save.
if ($_POST) {
    // The constants are form field names from this page, not DB names.
    $firstName = getFieldValueFromPost('first-name');
    $lastName = getFieldValueFromPost('last-name');
    $email = getFieldValueFromPost('email');
    $telephone = getFieldValueFromPost('telephone');
    $about = getFieldValueFromPost('about');
    // Move data into $currentUser, and validate.
    $errorMessage .= $currentUser->setFirstName($firstName);
    $errorMessage .= $currentUser->setLastName($lastName);
    $errorMessage .= $currentUser->setEmail($email);
    $errorMessage .= $currentUser->setTelephone($telephone);
    $errorMessage .= $currentUser->setAbout($about);
    // Are there errors?
    if ($errorMessage == '') {
        // No errors yet.
        // Try to update the account.
        $errorMessage = $currentUser->update();
        // Did it work?
        if ($errorMessage == '') {
            // Worked!
            // Show the new data.
            header('Location: own-account.php');
            exit();
        }
    }
}
else {
    // There is no post data.
    // Show the current data in the form fields.
    $lastName = $currentUser->getLastName();
    $firstName = $currentUser->getFirstName();
    $email = $currentUser->getEmail();
    $telephone = $currentUser->getTelephone();
    $about = $currentUser->getAbout();
}
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
        <h1>Edit your account</h1>
        <?php
        // Are any error messages waiting to be shown?
        if ($errorMessage != '') {
            ?>
            <div class="border border-danger rounded m-4 p-3">
                <p class="lead">Errors</p>
                <?php print $errorMessage; ?>
            </div>
            <?php
        }
        ?>
        <form name="person-edit" method="post">
            <?php
            include_once 'library/page-elements/person-basic-fields.php';
            ?>
            <p class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a class="btn btn-danger" title="All changes will be lost."
                   href="own-account.php" role="button">Cancel</a>
            </p>
        </form>
        <?php
        require_once 'library/page-elements/footer.php';
        ?>
    </body>
</html>