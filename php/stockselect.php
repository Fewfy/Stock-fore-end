<?php
function checkDT($datetime){
    $list = explode(" ", $datetime);
    $date = explode("-", $list[0]);
    $time = explode(":", $list[1]);
    if (count($date) != 3 || count($time) != 3)
        return false;
    if (ctype_digit($date[0]))
        $year = intval($date[0]);
    else
        return false;
    if (ctype_digit($date[1]))
        $month = intval($date[1]);
    else
        return false;
    if (ctype_digit($date[2]))
        $day = intval($date[2]);
    else
        return false;
    if (ctype_digit($time[0]))
        $hour = intval($time[0]);
    else
        return false;
    if (ctype_digit($time[1]))
        $minute = intval($time[1]);
    else
        return false;
    if (ctype_digit($time[2]))
        $second = intval($time[2]);
    else
        return false;
    if (checkdate($month, $day, $year)
        && $hour >= 0 && $hour < 24
        && $minute >= 0 && $minute < 60
        && $second >= 0 && $second < 60)
        return true;
    else
        return false;
}
$attr = htmlspecialchars($_GET["attr"]);
$key = htmlspecialchars($_GET["key"]);
$key1 = htmlspecialchars($_GET["key1"]);
$key2 = htmlspecialchars($_GET["key2"]);
$return = true;
if ($attr == "all")
{
    $querytype = "all";
}
else if ($attr == "stockid" || $attr == "apid")
{
    $querytype = "one";
}
else if ($attr == "circulation" || $attr == "date" || $attr == "inipri" || $attr == "strpri" || $attr == "buypri" || $attr == "sellpri" || $attr == "curmaxpri" || $attr == "curminpri" || $attr == "openingpri" || $attr == "closingpri" || $attr == "totalstock" || $attr == "curstock")
{
    $querytype = "two";
}
else
{
    $return = false;
    $error = "Error attr!";
}
if ($return && $attr != "all")
{
    if ($attr == "stockid")
    {
        if ($key == null || strlen($key) != 6 || !ctype_alnum($key))
        {
            $return = false;
            $error = "Error stockid!";
        }
    }
    else if ($attr == "apid")
    {
        if ($key == null || strlen($key) != 10 || !ctype_alnum($key))
        {
            $return = false;
            $error = "Error apid!";
        }
    }
    else if ($attr == "date")
    {
        if (!checkDT($key1) || !checkDT($key2))
        {
            $return = false;
            $error = "Error date!";
        }
    }
    else
    {
        if ($key1 == null || !is_numeric($key1) || $key2 == null || !is_numeric($key2))
        {
            $return = false;
            $error = "Error number!";
        }
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
    if ($querytype == "all")
    {
        $stmt = $mysqli->prepare("select * from stockinfo");
    }
    else if ($querytype == "one")
    {
        $stmt = $mysqli->prepare("select * from stockinfo where " . $attr . " = ?");
        $stmt->bind_param("s", $key);
    }
    else if ($querytype == "two")
    {
        if ($attr != "date")
        {
            $stmt = $mysqli->prepare("select * from stockinfo where " . $attr . " between ? and ?");
            $stmt->bind_param("dd", $key1, $key2);
        }
        else
        {
            $stmt = $mysqli->prepare("select * from stockinfo where " . $attr . " between '" . $key1 . "' and '" . $key2 . "'");
        }
    }
    $return = $stmt->execute();
    if ($return)
    {
        $res = $stmt->get_result();
        $data = $res->fetch_all(MYSQLI_ASSOC);
    }
    $cnt = count($data);
    for ($i = 0; $i < $cnt; $i++)
    {
        $stmt = $mysqli->prepare("select count(*) from usrcmd where stockid = ?");
        $stmt->bind_param("s", $data[$i]['stockid']);
        $stmt->execute();
        $res = $stmt->get_result();
        $temp = $res->fetch_all(MYSQLI_ASSOC);
        $data[$i]['inscnt'] = $temp[0]['count(*)'];
    }
    $stmt->close();
    $mysqli->close();
}
if ($return)
    echo json_encode($data);
else
{
    $null = array();
    echo json_encode($null);
}
?>

