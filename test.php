<?php
// Connect to the database.
require_once 'library/code/db_connect.php';
$pdo_connect = db_connect();
// Load user record, if user is logged in.
$user_id = 1;
if ($user_id != 0) {
    $sql = 'select * from people where person_id = ?';
    $stmnt = $pdo_connect->prepare($sql);
    $f = $stmnt->execute([$user_id]);
    $r=6;
    $hashed = sha1('password');
    $user = $stmnt->fetch();
    $loaded_pw = $user['password'];
    if ($loaded_pw == $hashed) {
        echo '<h2>Same</h2>';
    }
    else {
        echo '<h2>not Same</h2>';
    }
}


