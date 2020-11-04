<?php
/**
 * List courses. For management use.
 */
// Initialize the app.
require_once 'library/code/init.php';
// Check access to this page. Adjust the roles.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE)
    || isCurrentUserHasRole(STAFF_ROLE)
    || isCurrentUserHasRole(FACULTY_ROLE);
checkAccess($accessOk, __FILE__);
// Set the page title shown in the header template.
$pageTitle = 'List courses|PU';
// Load the Course class.
require_once 'library/code/Course.php';
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
<h1>List courses</h1>
<p>Here are all the courses.</p>
<?php
//Check the sort order.
$sortField = 'code';
if (isset($_GET['sort'])) {
    $sortField = trim($_GET['sort']);
    // Make sure that the sort field is valid.
    $sortFieldValid =
        $sortField == 'code'
        || $sortField == 'title'
    ;
    if (! $sortFieldValid) {
        print "<h2>Sort field $sortField is not valid. Sorting by course code.</h2>";
        $sortField = 'code';
    }
}
?>
<table class="table">
    <thead>
    <tr>
        <th><a href="course-list.php?sort=code" title="Sort by code">Code</a></th>
        <th><a href="course-list.php?sort=title" title="Sort by title">Title</a></th>
        <th>Maximum enrollments</th>
        <th>Instructor</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "select * from courses order by $sortField";
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
    foreach ($stmnt->fetchAll() as $courseRow) {
        // Make a course object.
        $course = new Course();
        $course->populateFromDatabaseRow($courseRow);
        // Get the instructor.
        $instructorName = 'TBA';
        if ($course->getInstructor() > 0) {
            $instructor = new Person();
            $instructorErrorMessage = $instructor->load($course->getInstructor());
            if ($instructorErrorMessage == '') {
                $instructorName = $instructor->getFullName();
            }
            else {
                $instructorName = "Lookup error";
            }
        }
        // Output.
        print '<tr>';
        print "<td>{$course->getCode()}</td>";
        print "<td>{$course->getTitle()}</a></td>";
        print "<td>{$course->getMaxEnrollments()}</td>";
        print "<td>$instructorName</td>";
        print "
            <td>
                <a class='btn btn-primary btn-sm' href='course-edit.php?id={$course->getId()}'>Edit</a>
                <a class='btn btn-primary btn-sm' href='course-confirm-delete.php?id={$course->getId()}'>Delete</a><br>
           </td>
        </tr>";
    }
    ?>
    </tbody>
</table>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>