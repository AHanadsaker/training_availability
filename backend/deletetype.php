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

$conn = mysqli_connect($servername, $username, $password, $dbname);

$sql = "SELECT * from accounts WHERE username = $user";
$result = mysqli_query($conn, $sql);

while($row = mysqli_fetch_assoc($result)) {
    $firstname = $row["first_name"];
    $lastname = $row["last_name"];
    $role = $row["role"];
}

if (
    $role == "TA" ||
    $role == "ATM" ||
    $role == "DATM" ||
    $role == "WM" ||
    $role == "Super"
) {

    $sql = "DELETE FROM types WHERE id ='" . mysqli_real_escape_string($conn, $_GET['id']) ."'";

    if(mysqli_query($conn, $sql)) {
        header("Location: types.php");
    } else {
        echo "This type could not be deleted. Contact the WM for assistance.";
    }
    mysqli_close($conn);
} else {
    echo "You are not permitted to access this page. Contact a Senior Staff member for assistance.";
} ?>
