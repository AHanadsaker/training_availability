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
    $bye_user = $_GET["cid"];

    $sql = "DELETE FROM accounts WHERE username = $bye_user";

    if ($conn->query($sql) === true) {
        header("Location: users.php");
    } else {
        echo "This user could not be deleted. Contact the WM for assistance.";
    }
    $conn->close();
} else {
    echo "You are not permitted to access this page. Contact a Senior Staff member for assistance.";
} ?>