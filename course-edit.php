<?php
/**
 * Create/edit course.
 * This page validates and saves, as well as showing the form.
 * Course id passed in GET if it's an edit.
 * POST data present for fields if there is data to be validated. That
 * happens for both edit and insert.
 */
// Initialize the app.
require_once 'library/code/init.php';
require_once 'library/code/Course.php';
// Check access to this page.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE) || isCurrentUserHasRole(STAFF_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Create course|PU';
if (isset($_GET['id'])) {
    $pageTitle = 'Edit course|PU';
}
global $dbConnector;
// Course id to edit is passed in the URL. If not there, must be a create.
// Show an insert by setting the id to 0.
$courseId = 0;
// Was a course id given?
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];
    // Validate.
    if (! is_numeric($courseId) || $courseId <= 0) {
        // Freak out.
        accessDenied("course-edit: bad id: $courseId");
    }
    // Does the course exist?
    $course = new Course();
    $errorMessage = $course->load($courseId);
    if ($errorMessage != '') {
        // Something went wrong. Freak out.
        accessDenied('course-edit: bad data in load');
    }
}
// Init fields to MT.
$code = '';
$title = '';
$maxEnrollments = 0;
$instructor = 0;
$validationErrorMessage = '';
// Is there POST data?
if ($_POST) {
    // Grab each of the fields.
    // The constants are form field names from this page, not DB names.
    $code = getFieldValueFromPost('code');
    $title = getFieldValueFromPost('title');
    $maxEnrollments = getFieldValueFromPost('max-enrollments');
    $instructor = getFieldValueFromPost('instructor');
    // Try making a course with this data.
    $course = new Course();
    $validationErrorMessage .= $course->populateFields(
        $code,
        $title,
        $maxEnrollments,
        $instructor
    );
    // Are there errors?
    if ($validationErrorMessage == '') {
        // No errors yet.
        if ($courseId == 0) {
            // This is a new record.
            // Make sure code isn't being used.
            $dbConnection = $dbConnector->getConnection();
            $sql = 'select course_id from courses where lower(code) = lower(?)';
            try {
                $stmnt = $dbConnection->prepare($sql);
                $queryResult = $stmnt->execute([$code]);
            } catch (PDOException $e) {
                logError(__FILE__, $e->getMessage());
                return INTERNAL_ERROR_MESSAGE;
            }
            // Get no rows?
            $numRows = $stmnt->rowCount();
            if ($numRows > 0) {
                $validationErrorMessage .= "A course with the code $code already exists<br>";
            }
            // All still OK?
            if ($validationErrorMessage == '') {
                // This will save the new id in the object.
                $validationErrorMessage = $course->saveAsNew();
                // Still OK?
                if ($validationErrorMessage == '') {
                    // To course list view.
                    header("Location: course-list.php");
                    exit();
                }
            }
        } else {
            // This is an edit.
            // Set the id - hasn't been done yet, since there is no
            // form field for it.
            $course->setId($courseId);
            $validationErrorMessage = $course->update();
            // Still OK?
            if ($validationErrorMessage == '') {
                // To course list.
                header("Location: course-list.php");
                exit();
            }
        }
    }
}
else {
    // There is no post data.
    if ($courseId != 0) {
        // There is an id, but no post data.
        // This happens when clicking on Edit link in course-list.php
        // Load the course data for the form.
        $course = new Course();
        $errorMessage = $course->load($courseId);
        if ($errorMessage != '') {
            // Something bad happened.
            print $errorMessage;
            exit();
        }
        // Copy data from $course into vars for the form.
        $code = $course->getCode();
        $title = $course->getTitle();
        $maxEnrollments = $course->getMaxEnrollments();
        $instructor = $course->getInstructor();
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
    if ($courseId == 0) {
        print 'Create';
    }
    else {
        print 'Edit';
    }
    ?> course</h1>
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
$formAction = ($courseId == 0) ? 'course-edit.php' : "course-edit.php?id=$courseId";
?>
<form name="course-edit" method="post" action="<?php print $formAction; ?>">
    <div class="form-group">
        <label for="code">Code</label>
        <input type="text" class="form-control" id="code" name="code"
               value="<?php print $code; ?>">
    </div>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title"
               value="<?php print $title; ?>">
    </div>
    <div class="form-group">
        <label for="max-enrollments">Maximum enrollments</label>
        <input type="text" class="form-control" id="max-enrollments" name="max-enrollments"
               value="<?php print $maxEnrollments; ?>">
    </div>
    <div class="form-group">
        <label for="instructor">Instructor</label>
        <select class="form-control" id="instructor" name="instructor">
            <option value="0">TBA</option>
    <?php
    // Make an instructor combo.
    $sql = "select * from people where faculty=1 order by last_name";
    // Run the query.
    /** @var $dbConnector DbConnector */
    global $dbConnector;
    // Get a DB connection.
    $dbConnection = $dbConnector->getConnection();
    try {
        $stmnt = $dbConnection->prepare($sql);
        $queryResult = $stmnt->execute();
    } catch (PDOException $e) {
        logError(__FILE__, $e->getMessage());
        print INTERNAL_ERROR_MESSAGE;
        exit();
    }
    // Loop over results.
    foreach ($stmnt->fetchAll() as $userRow) {
        // Make a person object.
        $person = new Person();
        $errors = $person->populateFromDatabaseRow($userRow);
        $selected = ($person->getId() == $instructor) ? 'selected' : '';
        if ($errors == '') {
            print "<option $selected value='{$person->getId()}'>{$person->getFullName()}</option>";
        }
    }
    ?>
        </select>
    </div>

    <p class="mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <a class="btn btn-danger" title="All changes will be lost."
           href="course-list.php" role="button">Cancel</a>
    </p>

</form>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>

