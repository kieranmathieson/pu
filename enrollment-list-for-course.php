<?php
/**
 * Show the enrollments for a course. Course id will be in GET.
 */
// Initialize the app.
require_once 'library/code/init.php';
require_once 'library/code/Course.php';
global $dbConnector;
global $currentUser;
// Was a valid course id passed?
if (! isCourseIdInGetOK()) {
    accessDenied(__FILE__ . ' bad id');
}
$courseId = $_GET['id'];
$course = new Course();
$errorMessage = $course->load($courseId);
if ($errorMessage != '') {
    print INTERNAL_ERROR_MESSAGE;
    exit();
}
// Check access to this page.
// Admins and staff have access.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE)
    || isCurrentUserHasRole(STAFF_ROLE);
if (! $accessOk) {
    // Faculty have access only if they are teaching the course.
    if (isCurrentUserHasRole(FACULTY_ROLE) ) {
        if ($currentUser->getId() == $course->getInstructor()) {
            $accessOk = true;
        }
    }
}
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'Enrollments|PU';
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
    <h1>Enrollments for course</h1>
    <p>Course: <?php print "{$course->getCode()} {$course->getTitle()}"; ?></p>
    <?php
    // Get a connection to the DB.
    $dbConnection = $dbConnector->getConnection();
    // Make SQL query arguments array.
    $queryData = [
        'courseId' => $courseId
    ];
    // Create the SQL.
    $sql =
        "select person_id from people, enrollments 
            where enrollments.course= :courseId
            and enrollments.person = people.person_id
            order by people.last_name";
    // Run the SQL.
    try {
        $stmnt = $dbConnection->prepare($sql);
        $queryResult = $stmnt->execute($queryData);
    }
    catch (PDOException $e) {
        return $e->getMessage();
    }
    // Check number of rows returned.
    $numEnrollments = $stmnt->rowCount();
    if ($numEnrollments == 0) {
        // No enrollments yet.
        print '<p>There are no enrollments for the course.</p>';
    }
    else {
        print "<p>Enrollments</p>
               <ul>";
        // There are enrollments. List the people.
        foreach ($stmnt->fetchAll() as $resultRow) {
            // Make a person object.
            $person = new Person();
            $errorMessage = $person->load($resultRow['person_id']);
            if ($errorMessage != '') {
                // Something went wrong.
                print "<li>Error! $errorMessage </li>";
            }
            else {
                print "<li><a href='person-view.php?id={$person->getId()}'>{$person->getFullName()}</a></li>";
            }
        }
        print '</ul>';
        print "<p>Number of students: $numEnrollments</p>";
    }    ?>
    <?php
    require_once 'library/page-elements/footer.php';
    ?>
    </body>
</html>

