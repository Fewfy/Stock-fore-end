<?php
$host = 'localhost';
$user = 'root';
$passwd = 'xhz636';
$database = 'stock';
$mysqli = new mysqli($host, $user, $passwd, $database);
if (mysqli_connect_error())
{
    echo mysqli_connect_error();
    exit;
}
$mysqli->set_charset("utf8");
$stmt = $mysqli->prepare("delete from stockinfo where stockid = ?");
$stmt->bind_param("s", $stockid);
$stockid = $_POST["stockid"];
$done = $stmt->execute();
echo $done;
$stmt->close();
$mysqli->close();
?>

