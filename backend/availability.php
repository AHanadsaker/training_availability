<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require "../requires.php";
include "../style.php";
$user = $_SESSION["name"];
$timezone = "UTC";

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM accounts WHERE username = $user";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $role = $row["role"];
}

echo "<center><h2>Trainee Future Availability</h2></center>";
echo "<center><table id=\"customers\"><th>Student (Rating)</th><th>Type</th><th>Start</th><th>End</th><th>Options</th></tr>";
echo "<tr><td colspan=\"20\"><center><font color=red><i>All times are in <b>your local timezone</b></i></font></center></td></tr>";

$sql = "SELECT * FROM availability WHERE start > $now ORDER BY start ASC";
$result = $conn->query($sql);

foreach ($result as $row) {
    echo "<tr><td><a target=\"_blank\" href=\"https://zlcartcc.org/roster/" .
        $row["cid"] .
        "\">";
    $cid = $row["cid"];

    $data = file_get_contents(
        "https://api.vatusa.net/user/" . $cid . "?apikey=" . $vatusaapi . ""
    ); //data read from json file
    $users = json_decode($data); //decode a data
    echo htmlentities($users->data->fname) .
        " " .
        htmlentities($users->data->lname) .
        " (" .
        htmlentities($users->data->rating_short) .
        ")";
    echo "</a></td><td>" . $row["type"] . "</td>";
    $start_utc = $row["start"];
    $starttime = $start_utc - $mult_bias;
    $end_utc = $row["end"];
    $endtime = $end_utc - $mult_bias;

    echo "<td>" . date("m/d/Y H:i", $starttime) . "</td>";
    echo "<td>" . date("m/d/Y H:i", $endtime) . "</td>";

    echo "<td><center><a title=\"Schedule Session\" href=\"https://txpg.net/vzlc/backend/schedule.php?id=" .
        $row["id"] .
        "\"><i class=\"fa fa-calendar\"></i></a> <a target=\"_blank\" title=\"VATUSA Training Notes\" href=\"https://www.vatusa.net/mgt/controller/" .
        $row["cid"] .
        "#training\"><i class=\"fa-solid fa-flag-usa\"></i></a>";
    if (
        $role == "TA" ||
        $role == "ATM" ||
        $role == "DATM" ||
        $role == "WM" ||
        $role == "Super"
    ) {
        echo " <a title=\"Delete Availability\" href=\"https://txpg.net/vzlc/backend/delete.php?id=" .
            $row["id"] .
            "\"><i class=\"fa-solid fa-trash\"></i></a></center>";
    }
    echo "</td></tr>";
}
echo "</table>";
$conn->close();
?>
    <hr>
    <a href="<?php echo $core_url; ?>/backend">Return Home</a>
</center>
