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
    $new_cid = test_input($_POST["cid"]);
    $new_role = test_input($_POST["role"]);
    $random = generateRandomString();
    $new_password = password_hash($random, PASSWORD_DEFAULT);

    $sql = "INSERT INTO accounts (username, password, role) VALUES ('$new_cid', '$new_password', '$new_role')";

    if ($conn->query($sql) === true) {
        $data = file_get_contents(
            "https://api.vatusa.net/user/" .
                $new_cid .
                "?apikey=" .
                $vatusaapi .
                ""
        ); //data read from json file
        $users = json_decode($data); //decode a data
        $new_fname = $users->data->fname;
        $new_email = $users->data->email;

        $to = $new_email;
        $subject = "[vZLC] Training Availability System Credentials";

        $message =
            "
<html>
<b>Greetings $new_fname,</b><br><br>
You have been granted <b>$new_role</b> access to the Salt Lake City ARTCC Training Session Management System.<br><br>

You can access this system by clicking <a target=\"_blank\" href=\"" .
            $core_url .
            "/backend\">here</a>.<br><br>

Your username is <b>" .
            $new_cid .
            "</b> and your temporary password is <b>$random</b>";

        // Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        // More headers
        $headers .= "From: vZLC <noreply@zlcartcc.org>" . "\r\n";

        mail($to, $subject, $message, $headers);

        echo "This user has been created and an email with the login credentials have been sent.<hr><a href=\"" .
            $core_url .
            "/backend\">Return Home</a>";
    } else {
        echo "There was an error creating this user.";
    }
} else {
     ?>
    
<center>
    
<?php if (
    $role == "TA" ||
    $role == "ATM" ||
    $role == "DATM" ||
    $role == "WM" ||
    $role == "Super"
) { ?>
    <table id="customers">
        <tr><th colspan="20"><center>Create User</center></th></tr>
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <tr><td><b>VATSIM CID:</b></td><td><input type="text" name="cid" maxlength="9" size="10" required></td></tr>
        <tr><td><b>Role:</b></td><td><select name="role" required>
            <option>Trainer</option>
        </select></td></tr>
        <tr><td colspan="20">The new user will be <b>immediately</b> emailed their login credentials. <i>Verify their CID prior to submitting.</i></td></tr>
        <tr><td colspan="20"><center><input type="submit" name="submit" value="Submit"></center></td></tr>
    </table>
<?php } else {echo "You are not permitted to access this portion of the system. Contact a member of the Senior Staff for assistance.";}
}
$conn->close();
?>
</center>