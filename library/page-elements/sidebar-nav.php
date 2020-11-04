<?php
global $currentUser;
// Show menu items depending on roles.
$myEnrollmentsMenu = $currentUser->isStudent();
$managePeopleMenu = $currentUser->isAdmin();
$manageCoursesMenu = $currentUser->isAdmin() || $currentUser->isStaff();
$manageEnrollmentsMenu = $currentUser->isAdmin() || $currentUser->isStaff();
$facultyCourseMenu = $currentUser->isFaculty();
// All logged in users have these.
print '<p><a href="account.php">Your account</a></p>';
print '<p><a href="logout.php">Logout</a></p>';

if ($myEnrollmentsMenu) {
    ?>
    <p><a href="my-enrollments.php">My enrollments</a></p>
    <?php
}
if ($managePeopleMenu) {
    ?>
    <p class="lead">People</p>
    <p><a href="person-list.php">List people</a></p>
    <p><a href="person-edit.php">Add new person</a></p>
    <?php
}
if ($manageCoursesMenu) {
    ?>
    <p class="lead">Courses</p>
    <p><a href="course-list.php">List courses</a></p>
    <p><a href="course-edit.php">Add new course</a></p>
    <?php
}
if ($manageEnrollmentsMenu) {
    ?>
    <?php
}
?>
