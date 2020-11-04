<?php
// Logout by erasing the session variable that
// stores the current user's id.
session_start();
// Record event.
require_once 'library/code/constants.php';
require_once 'library/code/utils.php';
logTransaction('logout.php', "Logout by {$_SESSION['current user id']}");
unset($_SESSION['current user id']);
header('Location: index.php');