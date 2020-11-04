<?php
/**
 * Delete a course.
 * Receives id as a GET param.
 */
// Initialize the app.
// noinspection DuplicatedCode
require_once 'library/code/init.php';
require_once 'library/code/Course.php';
/** @var $currentUser Person */
global $currentUser;
// Check access to this page.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE) || isCurrentUserHasRole(STAFF_ROLE);
checkAccess($accessOk, __FILE__);
// Make sure there is a course id that's real.
$courseIdOk = isCourseIdInGetOK();
if (!$courseIdOk) {
    accessDenied('course-delete: bad id');
}
$courseId = $_GET['id'];
// Load the course record.
$course = new Course();
$course->load($courseId);
// Delete the record. Returns an error message.
$errorMessage = $course->delete();
// Set the page title shown in the header template.
$pageTitle = 'Delete course|PU';
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
<h1>Course deletion</h1>
<p><span class="font-weight-bold">Code: </span><?php print $course->getCode(); ?></p>
<p><span class="font-weight-bold">Title: </span><?php print $course->getTitle(); ?></p>
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