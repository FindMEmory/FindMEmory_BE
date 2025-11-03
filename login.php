<?php
require_once __DIR__ . '/db_connect.php';

$login_id = $_POST['login_id'];
$login_pwd = $_POST['login_pwd'];

$sql = "SELECT * FROM users
        WHERE login_id='$login_id' AND login_pwd='$login_pwd'";

$result = mysqli_query($conn, $sql);

$login_result = 0;
while ($row = mysqli_fetch_array($result)) {
    $login_result = 1;
}

echo $login_result;
?>
