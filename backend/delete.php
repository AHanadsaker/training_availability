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
        header("Location: availability.php");
    } else {
        echo "This session could not be deleted. Contact the WM for assistance.";
    }
    $conn->close();
} else {
    echo "You are not permitted to access this page. Contact a Senior Staff member for assistance.";
} ?>
