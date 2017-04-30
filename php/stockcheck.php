<?php
$stockid = htmlspecialchars($_POST["stockid"]);
$return = true;
if ($stockid == null || strlen($stockid) != 6 || !ctype_alnum($stockid))
{
    $return = false;
}
if ($return)
{
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
    $stmt = $mysqli->prepare("select 1 from stockinfo where stockid = ? limit 1");
    $stmt->bind_param("s", $stockid);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    if (count($data) == 0)
        $return = true;
    else
        $return = false;
    $stmt->close();
    $mysqli->close();
}
echo $return;
?>

