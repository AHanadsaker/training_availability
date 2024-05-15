<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}

require "../requires.php";
include "../style.php";
$user = $_SESSION["name"];
?>
<center>
    <h2>My Upcoming Training Sessions</h2>
<table id="customers">
    <tr><th>Student</th><th>Start</th><th>End</th><th>Status</th><th>Options</th></tr>
    <tr><td colspan="20"><center><font color=red><i>All times are in <b>your local timezone</b></i></font></center></td></tr>
<?php
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM sessions WHERE trainer_cid = '$user' AND start > $now ORDER BY start ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $start_utc = $row["start"];
        $starttime = $start_utc - $mult_bias;
        $end_utc = $row["end"];
        $endtime = $end_utc - $mult_bias;
        echo "<tr><td>" .
            $row["student_cid"] .
            "</td><td>" .
            date("m/d/Y H:i", $starttime) .
            "</td><td>" .
            date("m/d/Y H:i", $endtime) .
            "</td><td>";
        if ($row["status"] == "1") {
            echo "<font color=green>Scheduled</font>";
        } elseif ($row["status"] == "2") {
            echo "<font color=red>Canceled</font>";
        }
        echo "</td><td>";
        if ($row["status"] == "1") {
            echo "<a title=\"Cancel Session\" href=\"" .
                $core_url .
                "/cancel.php?id=" .
                $row["id"] .
                "\"><i class=\"fa-solid fa-x\"></i></a>";
        }
        echo "</td></tr>";
    }
}
$conn->close();
?>
</table>
<?php
    include('footer.php');
?>