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
$session_id = $_GET["id"];

$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * FROM availability WHERE id ='" . mysqli_real_escape_string($conn, $_GET['id']) ."'";
$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    echo "<center><table id=\"customers\">";
    echo "<tr><th>Student (Rating)</th><th>Type</th><th>Start</th><th>End</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        $cid = $row["cid"];
        echo "<td colspan=\"20\"><font color=red><Center>The below time is your <b>local</b> time</center></font></td>";
        echo "<tr><td><a target=\"_blank\" href=\"https://zlcartcc.org/roster/" .
            $cid .
            "\">";
        $data = file_get_contents(
            "https://api.vatusa.net/user/" . $cid . "?apikey=" . $vatusaapi . ""
        ); //data read from json file
        $users = json_decode($data); //decode a data
        echo $users->data->fname .
            " " .
            $users->data->lname .
            " (" .
            $users->data->rating_short .
            ")";
        echo "</a></td><td>" . $row["type"] . "</td>";
        $start_utc = $row["start"];
        $starttime = $start_utc - $mult_bias;
        $end_utc = $row["end"];
        $endtime = $end_utc - $mult_bias;

        echo "<td>" . date("m/d/Y H:i", $starttime) . "</td>";
        echo "<td>" . date("m/d/Y H:i", $endtime) . "</td></tr>";
        echo "</table>";
    }
    mysqli_close($conn);
    ?>
<hr>
<table id="customers">
    <form method="post" action="<?php echo htmlspecialchars(
        "https://txpg.net/vzlc/backend/dispatch.php"
    ); ?>">
    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
    <tr><td colspan="20"><font color=red><Center>Enter time as your <b>local</b> time</Center></td></tr>
    <tr><td><b>Start Time:</b></td><td><input type="datetime-local" name="start_time" required></td></tr>
    <tr><td><b>End Time:</b></td><td><input type="datetime-local" name="end_time" required></td></tr>
    <tr><td colspan="20"><center><input type="submit" name="submit" value="Schedule"></center></td></tr>
    </form>
</table>
<?php
} else {
    echo "This availability submission could not be loaded. Please try, again.";
}
?>
<hr>
<center><a href="<?php echo $core_url; ?>/backend">Return Home</a></center>