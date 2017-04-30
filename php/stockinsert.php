<?php
$stockid = htmlspecialchars($_POST["stockid"]);
$circulation = htmlspecialchars($_POST["circulation"]);
$apid = htmlspecialchars($_POST["apid"]);
$inipri = htmlspecialchars($_POST["inipri"]);
$return = true;
if ($stockid == null || strlen($stockid) != 6 || !ctype_alnum($stockid))
{
    $return = false;
}
if ($circulation == null || !ctype_digit($circulation))
{
    $return = false;
}
else
{
    $circulation = intval($circulation);
    if ($circulation <= 0)
    {
        $return = false;
    }
}
if ($apid == null || strlen($apid) != 10 || !ctype_alnum($apid))
{
    $return = false;
}
if ($inipri == null || !is_numeric($inipri))
{
    $return = false;
}
else
{
    $inipri = floatval($inipri);
    if ($inipri <= 0)
    {
        $return = false;
    }
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
    $stmt = $mysqli->prepare("insert into stockinfo (stockid, circulation, apid, inipri) values (?,?,?,?)");
    $stmt->bind_param("sisd", $stockid, $circulation, $apid, $inipri);
    $return = $stmt->execute();
    $stmt->close();
    $mysqli->close();
}
echo $return;
?>

