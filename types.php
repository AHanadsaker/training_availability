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
$fct_clientbias = $_COOKIE["GMT_bias"];
$mult_bias = $fct_clientbias * 60;

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
    <h2>Training Session Types</h2>
    <table id="customers">
        <tr><th>Name</th><th>Options</th></tr>
        <tr><td colspan="20"><center><i>If these options need to be re-ordered, let Alex know which order they need to be in.</i></center></td></tr>
        <tr><td colspan="20"><center><a href="<?php echo $core_url; ?>/backend/newtype.php">New Session Type</a></center></td></tr>
        <?php
        $sql = "SELECT * FROM types ORDER by id ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" .
                    $row["name"] .
                    "</td><td><a href=\"" .
                    $core_url .
                    "/backend/deletetype.php?id=" .
                    $row["id"] .
                    "\"><i class=\"fa-solid fa-trash\"></i></a></td></tr>";
            }
        }
        $conn->close();
        ?>
    </table>
<?php } else {echo "You are not permitted to access this page. Contact a member of Senior Staff for assistance.";}
$conn->close();
?>
<hr>
<a href="<?php echo $core_url; ?>/backend">Return Home</a>