<?php
$env = parse_ini_file(__DIR__ . '/.env');

$db_addr = $env['DB_ADDR'];
$db_id   = $env['DB_ID'];
$db_pwd  = $env['DB_PWD'];
$db_name = $env['DB_NAME'];

$conn = mysqli_connect($db_addr, $db_id, $db_pwd, $db_name);

if (!$conn) {
    die("DB 연결 실패: " . mysqli_connect_error());
}
?>
