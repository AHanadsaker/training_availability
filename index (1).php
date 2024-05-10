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

$data = file_get_contents(
    "https://api.vatusa.net/user/" . $user . "?apikey=" . $vatusaapi . ""
); //data read from json file
$users = json_decode($data); //decode a data
$user_first = $users->data->fname;
$user_last = $users->data->lname;
$user_rating = $users->data->rating_short;

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "SELECT * FROM accounts WHERE username = $user";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $role = $row["role"];
}
$conn->close();
?>
<center>
    <h3>Trainer Interface</h3>
    <table id="customers" width="75%">
        <tr><th colspan="20"><b><center>Welcome, <?php echo $user_first; ?> <?php echo $user_last; ?> (<?php echo $user_rating; ?>)</center></b></th></tr>
        <tr><td><a href="<?php echo $core_url; ?>/backend/availability.php">Trainee Future Availability</a></td><td><a href="<?php echo $core_url; ?>/backend/sessions.php">My Sessions</a></td></tr><?php if (
    $role == "TA" ||
    $role == "ATM" ||
    $role == "DATM" ||
    $role == "WM" ||
    $role == "Super"
) { ?><tr><th colspan="20"><center><u><b>RESTRICTED ACCESS</b></u></center></th></tr><tr><td><a href="<?php echo $core_url; ?>/backend/users.php">User Management</a></td><td><a href="<?php echo $core_url; ?>/backend/availability_search.php">Search Trainee Availability</a></td></tr><tr><td><a href="<?php echo $core_url; ?>/backend/types.php">Session Types</a></td><td><a href="#">Login History</a></td><?php } ?></tr>
    </table>
    <hr>
    <a href="<?php echo $core_url; ?>/backend/logout.php">Logout</a>
</center>