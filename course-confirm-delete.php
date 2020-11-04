<?php
/**
 * Confirm that the user wants to delete a course.
 * Receives id as a GET param.
 */

// TODO: check course record not referred to in enrollments.

// Initialize the app.
require_once 'library/code/init.php';
require_once 'library/code/Course.php';
global $currentUser;
// Check access to this page.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE) || isCurrentUserHasRole(STAFF_ROLE);
checkAccess($accessOk, __FILE__);
// Make sure there is a course id that's real.
$courseIdOk = isCourseIdInGetOK();
if (!$courseIdOk) {
    accessDenied('confirm-course-delete: bad id');
}
$courseId = $_GET['id'];
// Load the course record.
$course = new Course();
$course->load($courseId);
// Set the page title shown in the header template.
$pageTitle = 'Confirm delete course|PU';
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
<h1>Confirm course deletion</h1>
<p><span class="font-weight-bold">Code: </span><?php print $course->getCode(); ?></p>
<p><span class="font-weight-bold">Title: </span><?php print $course->getTitle(); ?></p>
<p>
    Are you <strong>sure</strong> you want to delete this course?
</p>
<p class="mt-3">
    <a class="btn btn-danger mr-8" title="All data about this course will be lost."
       href="course-delete.php?id=<?php print $courseId; ?>" role="button">Delete</a>
    <a class="btn btn-secondary" title="Forgedaboudit"
       href="course-list.php" role="button">Cancel</a>
</p>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>