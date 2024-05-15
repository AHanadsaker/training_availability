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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_pw = test_input($_POST["current_pw"]);
        $current_hash = password_hash($current_pw, PASSWORD_DEFAULT);
    $new_pw = test_input($_POST["new_pw"]);
        $new_hash = password_hash($new_pw, PASSWORD_DEFAULT);
    $conf_pw = test_input($_POST["conf_pw"]);
    
$sql = "SELECT * FROM accounts WHERE username = '$user'";
$result = $conn->query($sql);
  while($row = $result->fetch_assoc()) {
      $db_pw = $row['password'];
  }
  
    if (password_verify($current_pw, $db_pw)) {
        if($new_pw === $conf_pw) {
            $sql = "UPDATE accounts SET password = '$new_hash', pw_flag = '0' WHERE username='$user'";
            if ($conn->query($sql) === TRUE) {
                $sql = "INSERT INTO logins (`username`, `status`) VALUES ('$user', 'Updated Password')";
                    if ($conn->query($sql) === TRUE) {
                echo "<font color=green>Your password was successfully updated. You will now be logged out.</font>";
                header("Refresh: 2; URL=".$core_url."/backend/logout.php");
            } } else {
                echo "<font color=red>Your password could not be reset.</font>";
            } } else {
                echo "<font color=red>Your passwords did not match. Please go back and try, again.</font>";
            } } else {
                echo "<font color=red>Your current password does not match. Please try, again.</font><br><br>";
                echo $current_hash;
                echo "<Br><Br>";
                echo $db_pw;
            } } else {
$conn->close();
?>
<center>
    <h3>Reset Password</h3>
    <table id="customers" width="75%">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <tr><td>Current Password:</td><td><input type="password" name="current_pw" required></td></tr>
        <tr><Th colspan="20"><hr></Th></tr>
        <tr><td>New Password:</td><td><input type="password" name="new_pw" required></td></tr>
        <tr><td>Confirm New Password:</td><td><input type="password" name="conf_pw" required></td></tr>
        <tr><td colspan="20"><center><input type="submit" name="submit" value="Reset Password"></center></td></tr>
        </form>
    </table>
<?php }
    include('footer.php');
?>