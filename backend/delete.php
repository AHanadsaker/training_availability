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
    $bye_session = $_GET["id"];

    $sql = "DELETE FROM availability WHERE id = $bye_session";

    if ($conn->query($sql) === true) {
        header("Location: availability.php");
    } else {
        echo "This session could not be deleted. Contact the WM for assistance.";
    }
    $conn->close();
} else {
    echo "You are not permitted to access this page. Contact a Senior Staff member for assistance.";
} ?>