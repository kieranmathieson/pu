<?php
function checkLogin(string $username, string $plainPassword): string {
    global $currentUser;
    global $dbConnector;
    // There is a username and password to check.
    // Get a DB connection.
    $dbConnection = $dbConnector->getConnection();
    // Fetch the user id from the database.
    $queryData = [
        'username' => $username
    ];
    $sql = 'SELECT person_id, password FROM people WHERE username = :username';
    try {
        $stmnt = $dbConnection->prepare($sql);
        $queryResult = $stmnt->execute($queryData);
    } catch (PDOException $e) {
        return $e->getMessage();
    }
    // Check for execution success.
    if (!$queryResult) {
        return 'Login failed.';
    }
    // Check number of rows returned.
    if ($stmnt->rowCount() == 0) {
        // Didn't find the user.
        return 'Login failed.';
    }
    $userRow = $stmnt->fetch();
    // Check whether the password is right.
    $encryptedPassword = $userRow['password'];
    if (sha1($plainPassword) != $encryptedPassword) {
        // Passwords don't match.
        return 'Login failed.';
    }
    // Password matches.
    $personId = $userRow['person_id'];
    // Load the user, receiving any errors.
    $currentUser = new Person();
    $errorMessage = $currentUser->load($personId);
    return $errorMessage;
}