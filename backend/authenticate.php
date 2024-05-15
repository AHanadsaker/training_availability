<?php
session_start();
// Try and connect using the info above.
require "../requires.php";
$con = mysqli_connect($servername, $username, $password, $dbname);
if (mysqli_connect_errno()) {
    // If there is an error with the connection, stop the script and display the error.
    exit("Failed to connect to MySQL: " . mysqli_connect_error());
}

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST["username"], $_POST["password"])) {
    // Could not get the data that should have been sent.
    exit("Please fill both the username and password fields!");
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if (
    $stmt = $con->prepare(
        "SELECT id, password FROM accounts WHERE username = ?"
    )
) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the username is a string so we use "s"
    $stmt->bind_param("s", $_POST["username"]);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST["password"], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $login_name = $_POST["username"];
            $sql =
                "INSERT INTO logins (`username`, `status`) VALUES (" .
                $login_name .
                ", 'Success')";
            if ($con->query($sql) === true) {
                $_SESSION["loggedin"] = true;
                $_SESSION["name"] = $_POST["username"];
                $_SESSION["id"] = $id;
                
                $sql1 = "SELECT * FROM accounts WHERE username=$login_name";
                    $result1 = mysqli_query($con, $sql1);
                    while($row = mysqli_fetch_assoc($result1)) {
                        $pw_flag = $row['pw_flag'];
                    }
                if($pw_flag === '1') {
                    header("Location: resetpw.php");
                } else {
                header("Location: index.php");
            } } else {
                echo "There was an error logging you in.";
            }
        } else {
            $login_name = $_POST["username"];
            $sql =
                "INSERT INTO logins (`username`, `status`) VALUES (" .
                $login_name .
                ", 'Bad Password')";
            if ($con->query($sql) === true) {
            }
            // Incorrect password
            echo "Incorrect username and/or password!";
        }
    } else {
        $login_name = $_POST["username"];
        $sql =
            "INSERT INTO logins (`username`, `status`) VALUES (" .
            $login_name .
            ", 'Bad Username')";
        if ($con->query($sql) === true) {
        }
        // Incorrect username
        echo "Incorrect username and/or password!";
    }
    $stmt->close();
}
$con->close();
?>