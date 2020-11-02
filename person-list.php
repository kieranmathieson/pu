<?php
// Initialize the app.
require_once 'library/code/init.php';
// Check access to this page. Adjust the roles.
$accessOk = isCurrentUserHasRole(ADMIN_ROLE);
checkAccess($accessOk);
// Set the page title shown in the header template.
$pageTitle = 'List users|PU';
// Uncomment global if needed for the page's logic.
/** @var $currentUser Person */
// global $currentUser;
/** @var $dbConnector DbConnector */
//global $dbConnector;
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
<h1>List people</h1>
<p>Here are all the people.</p>
<?php
//Check the sort order.
$sortField = 'username';
if (isset($_GET['sort'])) {
    $sortField = trim($_GET['sort']);
    // Make sure that the sort field is valid.
    $sortFieldValid =
        $sortField == 'username'
        || $sortField == 'last_name'
        || $sortField == 'email'
    ;
    if (! $sortFieldValid) {
        print "<h2>Sort field $sortField is not valid. Sorting by user name.</h2>";
        $sortField = 'username';
    }
}
?>
<table class="table">
    <thead>
        <tr>
            <th><a href="person-list.php?sort=username" title="Sort by username">Username</a></th>
            <th><a href="person-list.php?sort=last_name" title="Sort by last name">Person name</a></th>
            <th><a href="person-list.php?sort=email" title="Sort by last name">Email</a></th>
            <th>Telephone</th>
            <th>Operations</th>
        </tr>
    </thead>
    <tbody>
    <?php
    // Create query.
    $queryData = [
        'sort' => $sortField
    ];
    $sql = "select * from people order by :sort";
    // Run the query.
    /** @var $dbConnector DbConnector */
    global $dbConnector;
    // Get a DB connection.
    $dbConnection = $dbConnector->getConnection();
    try {
        $stmnt = $dbConnection->prepare($sql);
        $queryResult = $stmnt->execute($queryData);
    } catch (PDOException $e) {
        print $e->getMessage();
        exit();
    }
    // Loop over results.
    foreach ($stmnt->fetchAll() as $userRow) {
        // Make a person object.
        $person = new Person();
        $person->populateFromDatabaseRow($userRow);
        // Output.
        print '<tr>';
        print "<td><a href='person-view.php?id={$person->getId()}'>{$person->getUserName()}</a></td>";
        print "<td><a href='person-view.php?id={$person->getId()}'>{$person->getFullName()}</a></td>";
        print "<td>{$person->getEmail()}</td>";
        print "<td>{$person->getTelephone()}</td>";
        print "
            <td>
                <a class='btn btn-primary btn-sm' href='person-view.php?id={$person->getId()}'>View</a> 
                <a class='btn btn-primary btn-sm' href='person-edit.php?id={$person->getId()}'>Edit</a> ";
        // Can't erase your own record.
        if ($currentUser->getId() != $person->getId()) {
            print "<a class='btn btn-primary btn-sm' href='person-confirm-delete.php?id={$person->getId()}'>Delete</a><br>";
        }
        print '   </td>';
        print '</tr>';
    }
    ?>
    </tbody>
</table>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>