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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = test_input($_POST["name"]);

    $sql1 = "INSERT INTO types (name) VALUES ('$new_name')";

    if ($conn->query($sql1) === true) {
        header("Location: types.php");
    } else {
        echo "There was an error creating this type.";
    }
} else {
     ?>
    
<center>
    <h2>New Training Session Type</h2>
<?php if (
    $role == "TA" ||
    $role == "ATM" ||
    $role == "DATM" ||
    $role == "WM" ||
    $role == "Super"
) { ?>
    <table id="customers">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <tr><td><b>Type Name:</b></td><td><input type="text" name="name" required></td></tr>
        <tr><td colspan="20"><center><input type="submit" name="submit" value="Submit"></center></td></tr>
    </table>
<?php } else {echo "You are not permitted to access this portion of the system. Contact a member of the Senior Staff for assistance.";}
}
?>
<hr>
<a href="<?php echo $core_url; ?>/backend/">Return Home</a>
</center>