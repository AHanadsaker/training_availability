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

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM accounts WHERE username = $user";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $role = $row["role"];
}

if (
    $role == "TA" ||
    $role == "ATM" ||
    $role == "DATM" ||
    $role == "WM" ||
    $role == "Super"
) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $cid = test_input($_POST["cid"]);

        $sql = "SELECT * FROM availability WHERE cid = '$cid' ORDER BY start ASC";
        $result = $conn->query($sql);

        echo "<center>
    <h2>Availability Results</h2>
    <table id=\"customers\">
    <tr><th>Type</th><th>Start</th><th>End</th></tr>";

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $start_utc = $row["start"];
                $starttime = $start_utc - $mult_bias;
                $end_utc = $row["end"];
                $endtime = $end_utc - $mult_bias;

                echo "<tr><td>" .
                    $row["type"] .
                    "</td><td>" .
                    date("m/d/Y H:i", $starttime) .
                    "</td><td>" .
                    date("m/d/Y H:i", $endtime) .
                    "</td></tr>";
            }
        } else {
            echo "<tr><td colspan=\"20\">There is no availability posted for CID " .
                $cid .
                ".</td></tr>";
        }
        echo "</table></center>";

        $sql = "SELECT * FROM sessions WHERE student_cid = '$cid' ORDER BY start ASC";
        $result = $conn->query($sql);

        echo "<center>
    <h2>Scheduled Session Results</h2>
    <table id=\"customers\">
    <tr><th>Start</th><th>End</th><th>Trainer</th><th>Status</th></tr>";

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $start_utc = $row["start"];
                $starttime = $start_utc - $mult_bias;
                $end_utc = $row["end"];
                $endtime = $end_utc - $mult_bias;

                echo "<tr><td>" .
                    date("m/d/Y H:i", $starttime) .
                    "</td><td>" .
                    date("m/d/Y H:i", $endtime) .
                    "</td><td>";
                $trainer_cid = $row["trainer_cid"];
                $data = file_get_contents(
                    "https://api.vatusa.net/user/" .
                        $trainer_cid .
                        "?apikey=" .
                        $vatusaapi .
                        ""
                ); //data read from json file
                $trainer = json_decode($data); //decode a data
                echo $trainer->data->fname . " " . $trainer->data->lname;
                echo "</td><td>";
                if ($row["status"] == "1") {
                    echo "<font color=green>Scheduled</font>";
                } elseif ($row["status"] == "2") {
                    echo "<font color=red>Canceled</font>";
                }
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan=\"20\">There are no sessions for CID " .
                $cid .
                ".</td></tr>";
        }
        echo "</table></center>";
        $conn->close();
    } else {
         ?>
<center>
    <table id="customers">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <tr><th colspan="20"><center>Search Trainee Availability</center></th></tr>
        <tr><td>Student CID:</td><td><input type="text" name="cid" maxlength="9" size="10" required></td></tr>
        <tr><td colspan="20"><center><input type="submit" name="submit" value="Submit"></center></td></tr>
    </table>
</center>
<?php
    }
} else {
    echo "You are not authorized to access this portion of the system. Contact a Senior Staff member for assistance.";
}
?>
<hr>
<center><a href="<?php echo $core_url; ?>/backend">Return Home</a></center>