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
    <h2>User Management</h2>
    <table id="customers">
        <tr><Th>VATSIM CID</Th><th>Name</th><th>Role</th><th>Options</th></tr>
        <tr><td colspan="20"><center><a href="<?php echo $core_url; ?>/backend/newuser.php">Create User</a></center></td></tr>
        <?php
        
        $sql = "SELECT * FROM accounts ORDER BY username ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $username = $row["username"];
                $data = file_get_contents(
                    "https://api.vatusa.net/user/" .
                        $username .
                        "?apikey=" .
                        $vatusaapi .
                        ""
                ); //data read from json file
                $users = json_decode($data); //decode a data
                echo "<tr><td>" . $row["username"] . "</td><td>";
                echo $users->data->fname . " " . $users->data->lname;
                echo "</td><td>" . $row["role"] . "</td><td>";
                if ($row["role"] != "Super") {
                    echo "<a title=\"Reset Password\" href=\"#\"><i class=\"fa-solid fa-pencil\"></i></a> <a title=\"Delete User\" href=\"" .
                        $core_url .
                        "/backend/deleteuser.php?cid=" .
                        $row["username"] .
                        "\"><i class=\"fa-solid fa-trash\"></i></a>";
                }
                echo "</td></tr>";
            }
        }
        $conn->close();
        ?>
    </table>
    <?php include('footer.php') ?>
</center>
<?php } else {echo "You are not permitted to access this page. Contact a Senior Staff member for assistance.";} ?>