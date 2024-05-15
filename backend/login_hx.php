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
) { ?>

<center>
    <h2>Backend Login History (last 20)</h2>
    <table id="customers">
        <tr><th>Username</th><Th>Timestamp</Th><th>Status</th></tr>
        <?php
        $sql = "SELECT * FROM logins ORDER by timestamp DESC LIMIT 20";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>".$row['username']."</td><td>".$row['timestamp']."</td><td>".$row['status']."</td></tr>";
            }
        }
        $conn->close();
        ?>
    </table>
<?php } else {echo "You are not permitted to access this page. Contact a member of Senior Staff for assistance.";}

include('footer.php');
?>