<?php
// Initialize the app.
require_once 'library/code/init.php';
/** @var $currentUser Person */
global $currentUser;
// Already logged in?
if (! is_null($currentUser)) {
    header('Location: index.php');
    exit();
}
require_once 'library/code/check-login.php';
$pageTitle = 'Login|PU';
// Place for error messages.
$errorMessage = null;
//$username = '';
if ($_POST) {
    // There is data to validate.
    $loginOK = false;
    // Was username and password entered?
    if (isset($_POST['username']) && isset($_POST['username'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $errorMessage = checkLogin($username, $password);
        // Did it work?
        if ($errorMessage == '') {
            // Login OK.
            // Save the id in the session, to show there is a current user.
            $id = $currentUser->getId();
            $_SESSION['current user id'] = $id;
            // Record event.
            logTransaction('login.php', "Login by $id");
            // Go back to the home page.
            header('Location: index.php');
            exit();
        }
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
<h1>Log in</h1>
<?php
    // Is there an error message to show?
    if ($errorMessage != '') {
        ?>
        <p class="border border-danger rounded m-4 p-3">
            <?php print $errorMessage; ?>
        </p>
        <?php
    }
?>
<form name="login" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>
    <p class="mt-3">
        <button type="submit" class="btn btn-primary">Log in</button>
        <a class="btn btn-secondary" href="index.php" role="button">Cancel</a>
    </p>
</form>
<?php
require_once 'library/page-elements/footer.php';
?>
</body>
</html>