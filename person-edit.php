<?php
/**
 * Create/edit person.
 * This page validates and saves, as well as showing the form.
 * Person id passed in GET if it's an edit.
 * POST data present for fields if there is data to be validated. That
 * happens for both edit and insert.
 *
 * There is a different page for non-admins editing their own account.
 */
// Initialize the app.
require_once 'library/code/init.php';
global $currentUser;
// User id to edit is passed in the URL. If not there, must be a create.
// Show an insert by setting the id to 0.
$personId = 0;
$personToEdit = null;
// Was a person id given?
if (isset($_GET['id'])) {
    $personId = $_GET['id'];
    // Validate.
    if (! is_numeric($personId) || $personId <= 0) {
        // Freak out.
        accessDenied("person-edit: bad id: $personId");
    }
    // Does the person exist?
    $personToEdit = new Person();
    $errorMessage = $personToEdit->load($personId);
    if ($errorMessage != '') {
        // Something went wrong. Freak out.
        accessDenied('person-edit: bad data in load');
    }
}
// If get to here, $personToEdit will have a person object.
// Check access to this page.
// Admins are OK.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Create person|PU';
if (isset($_GET['id'])) {
    $pageTitle = 'Edit person|PU';
}
// Init person fields to MT.
$userName = '';
$firstName = '';
$lastName = '';
$email = '';
$telephone = '';
$about = '';
$isAdmin = false;
$isStaff = false;
$isFaculty = false;
$isStudent = false;
$validationErrorMessage = '';
// Is there POST data?
if ($_POST) {
    // Grab each of the fields.
    // The constants are form field names from this page, not DB names.
    $userName = getFieldValueFromPost('username');
    $plainPassword1 = getFieldValueFromPost('password1');
    $plainPassword2 = getFieldValueFromPost('password2');
    $firstName = getFieldValueFromPost('first-name');
    $lastName = getFieldValueFromPost('last-name');
    $email = getFieldValueFromPost('email');
    $telephone = getFieldValueFromPost('telephone');
    $about = getFieldValueFromPost('about');
    $isAdmin = isset($_POST['admin']);
    $isStaff = isset($_POST['staff']);
    $isFaculty = isset($_POST['faculty']);
    $isStudent = isset($_POST['student']);
    // Validate.
    // Password needs special logic.
    $encryptedPassword = '';
    // Is this an edit?
    if ($personId != 0) {
        // This is an edit.
        // Were both password field MT?
        if ($plainPassword1 == '' && $plainPassword2 == '') {
            // No passwords entered.
            // Load password from current value.
            $priorPerson = new Person();
            $priorPerson->load($personId);
            $encryptedPassword = $priorPerson->getEncryptedPassword();
        }
        else {
            // User typed in password(s).
            // Set password from input fields.
            // Check whether passwords match.
            if ($plainPassword1 != $plainPassword2) {
                // If there is no password error message already, add one.
                if (! containsString($validationErrorMessage, 'password')) {
                    $validationErrorMessage .= "Passwords do not match.<br>\n";
                }
            }
            else {
                // Passwords match.
                $encryptedPassword = sha1($plainPassword1);
            }
        }
    }
    else {
        // A new record.
        // Check whether password data typed in.
        if ($plainPassword1 == '' && $plainPassword2 == '') {
            // Both fields MT.
            // If there is no password error message already, add one.
            if (! containsString($validationErrorMessage, 'password')) {
                $validationErrorMessage .= "Please enter the password twice.<br>\n";
            }
        }
        else {
            // There is password data.
            // Check whether passwords match.
            if ($plainPassword1 != $plainPassword2) {
                // If there is no password error message already, add one.
                if (! containsString($validationErrorMessage, 'password')) {
                    $validationErrorMessage .= "Passwords do not match.<br>\n";
                }
            }
            else {
                // Passwords match.
                $encryptedPassword = sha1($plainPassword1);
            }
        }
    } // End process password for new user.
    // Try making a person with this data.
    $personToEdit = new Person();
    $validationErrorMessage .= $personToEdit->populateFields(
        $userName, $encryptedPassword,
        $lastName, $firstName,
        $email, $telephone,
        $about,
        $isAdmin, $isStaff, $isFaculty, $isStudent
    );
    // Are there errors?
    if ($validationErrorMessage == '') {
        // No errors yet.
        if ($personId == 0) {
            // New person.
            // Make sure the username and email aren't already being used.
            if (isUsernameExists($userName)) {
                $validationErrorMessage .= "Sorry, someone already has the username $userName<br>";
            }
            if (isEmailExists($email)) {
                $validationErrorMessage .= "Sorry, someone already has the email address $email<br>";
            }
            // Still OK?
            if ($validationErrorMessage == '') {
                // This will save the new id in the object.
                $validationErrorMessage = $personToEdit->saveAsNew();
                // Still OK?
                if ($validationErrorMessage == '') {
                    // To person view.
                    header("Location: person-view.php?id={$personToEdit->getId()}");
                    exit();
                }

            }
        }
        else {
            // Existing person.
            // Set the id - hasn't been done yet, since there is no
            // form field for it.
            $personToEdit->setId($personId);
            $validationErrorMessage = $personToEdit->update();
            // Still OK?
            if ($validationErrorMessage == '') {
                // To person view.
                header("Location: person-view.php?id={$personToEdit->getId()}");
                exit();
            }
        }
    }
}
else {
    // There is no post data.
    if ($personId != 0) {
        // There is an id, but no post data.
        // This happens when clicking on Edit link in person-list.php
        // Load the person's data for the form.
        $personToEdit = new Person();
        $errorMessage = $personToEdit->load($personId);
        if ($errorMessage != '') {
            // Something bad happened.
            print $errorMessage;
            exit();
        }
        // Copy data from $person into vars for the form.
        $userName = $personToEdit->getUserName();
        $lastName = $personToEdit->getLastName();
        $firstName = $personToEdit->getFirstName();
        $email = $personToEdit->getEmail();
        $telephone = $personToEdit->getTelephone();
        $about = $personToEdit->getAbout();
        $isAdmin = $personToEdit->isAdmin();
        $isStaff = $personToEdit->isStaff();
        $isFaculty = $personToEdit->isFaculty();
        $isStudent = $personToEdit->isStudent();
    }
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
<h1><?php
    if ($personId == 0) {
        print 'Create';
    }
    else {
        print 'Edit';
    }
    ?> person</h1>
<?php
if ($validationErrorMessage != '') {
    ?>
    <div class="border border-danger rounded m-4 p-3">
        <p class="lead">Errors</p>
        <?php print $validationErrorMessage; ?>
    </div>
    <?php
}
// What is the form action?
// No id at the end for new records, id for editing.
$formAction = ($personId == 0) ? 'person-edit.php' : "person-edit.php?id=$personId";
?>
<form name="person-edit" method="post" action="<?php print $formAction; ?>">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username"
               value="<?php print $userName; ?>">
    </div>
    <div class="form-group">
        <label for="password1">Password</label>
        <input type="password" class="form-control"
               aria-describedby="password1Help"
               id="password1" name="password1">
        <small id="password1Help" class="form-text text-muted">Leave blank to not change the password.</small>
    </div>
    <div class="form-group">
        <label for="password2">Password again</label>
        <input type="password" class="form-control"
               aria-describedby="password2Help"
               id="password2" name="password2">
        <small id="password2Help" class="form-text text-muted">Leave blank to not change the password.</small>
    </div>
    <?php
    // Bring in HTML code for form fields shared with other programs.
    include_once 'library/page-elements/person-basic-fields.php';
    ?>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="admin" name="admin"
            <?php print makeCheckboxValueFromBool($isAdmin); ?> >
        <label class="form-check-label" for="admin">Admin</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="staff" name="staff"
            <?php print makeCheckboxValueFromBool($isStaff); ?> >
        <label class="form-check-label" for="staff">Staff</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="faculty" name="faculty"
            <?php print makeCheckboxValueFromBool($isFaculty); ?> >
        <label class="form-check-label" for="faculty">Faculty</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="student" name="student"
            <?php print makeCheckboxValueFromBool($isStudent); ?> >
        <label class="form-check-label" for="student">Student</label>
    </div>
    <p class="mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-danger" title="All changes will be lost."
           href="person-view.php?id=<?php print $personId; ?>" role="button">Cancel</a>
    </p>
    </form>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>