<?php
/**
 * Initialize the app. Put as much init code as possible here, rather than in
 * each page. This will make changing initialization code easier.
 */
// Start session, call before any output emitted.
session_start();
// Grab some constants.
require_once 'library/code/constants.php';
// Load current user global.
loadCurrentUser();
// Load code to check role(s) required for page.
require_once 'library/code/role-check.php';

/**
 * Initialize the app. Called at the start of every page.
 */
function loadCurrentUser() {
    // Some app-wide global variables.
    /** @var $currentUser Person */
    global $currentUser;
    /** @var $dbConnector DbConnector */
    global $dbConnector;
    // Set $currentUser to Person object, or null.
    require_once 'DbConnector.php';
    require_once 'Person.php';
    $dbConnector = new DbConnector();
    // Is there a current user? If so, id will be in the session.
    if (isset($_SESSION['current user id'])) {
        $userId = $_SESSION['current user id'];
        $currentUser = new Person();
        $errorMessage = $currentUser->load($userId);
        if ($errorMessage != '') {
            logError('init.php', "Person load failed for user id: $userId");
            print INTERNAL_ERROR_MESSAGE;
            exit();
        }
    } else {
        $currentUser = null;
    }
}