<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}
?>

<?php
require "../requires.php";
include "../style.php";
$trainer_cid = $_SESSION["name"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $session_name = generateRandomString();
    $session_id = test_input($_POST["session_id"]);
    $start_time = test_input($_POST["start_time"]);
    $start_unix = strtotime($start_time);
    $start_utc = $start_unix + $mult_bias;
    $start_email = date("m/d/Y H:i T", $start_utc);
    $end_time = test_input($_POST["end_time"]);
    $end_unix = strtotime($end_time);
    $end_utc = $end_unix + $mult_bias;
    $end_email = date("m/d/Y H:i T", $end_utc);

    $sql = "SELECT * FROM availability WHERE id = $session_id";
    $conn = new mysqli($servername, $username, $password, $dbname);
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            $student_cid = $row["cid"];
            $avail_sutc = $row["start"];
            $avail_eutc = $row["end"];

            if ($start_utc < $avail_sutc) {
                echo "Your start time cannot be before the availability.";
            } else {
                if ($end_utc > $avail_eutc) {
                    echo "Your end time cannot be after the availability.";
                } else {
                    if ($end_utc < $start_utc) {
                        echo "Your end time cannot be before the start time.";
                    } else {
                        $sql = "INSERT INTO sessions (id, student_cid, trainer_cid, start, end, status) VALUES ('$session_name', '$student_cid', '$trainer_cid', '$start_utc', '$end_utc', '1')";

                        if ($conn->query($sql) === true) {
                            $student_data = file_get_contents(
                                "https://api.vatusa.net/user/" .
                                    $student_cid .
                                    "?apikey=" .
                                    $vatusaapi .
                                    ""
                            ); //data read from json file
                            $student = json_decode($student_data); //decode a data
                            $student_fname = $student->data->fname;
                            $student_email = $student->data->email;

                            $trainer_data = file_get_contents(
                                "https://api.vatusa.net/user/" .
                                    $trainer_cid .
                                    "?apikey=" .
                                    $vatusaapi .
                                    ""
                            ); //data read from json file
                            $trainer = json_decode($trainer_data); //decode a data
                            $trainer_fname = $trainer->data->fname;
                            $trainer_lname = $trainer->data->lname;
                            $trainer_email = $trainer->data->email;

                            $to = $student_email;
                            $subject = "[vZLC] Session Scheduled";

                            $message =
                                "
<html>
<b>Greetings $student_fname,</b><br><br>
A Training Session has been scheduled for you based on your availability. Please arrive to the training session 5 minutes early. Your training will be conducted in the Salt Lake City ARTCC Discord with " .
                                $trainer_fname .
                                " " .
                                $trainer_lname .
                                ".<br><br>

<b>Start Date/Time (Zulu):</b> " .
                                $start_email .
                                "<br>
<b>End Date/Time (Zulu):</b> " .
                                $end_email .
                                "
<br><br>
If you are unable to keep this session, please cancel by clicking <a target=\"_blank\" href=\"https://txpg.net/vzlc/cancel.php?id=" .
                                $session_name .
                                "\">here</a>.
<br><br>
We look forward to seeing you for your training!
";

                            // Always set content-type when sending HTML email
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .=
                                "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .=
                                "From: vZLC <noreply@zlcartcc.org>" . "\r\n";
                            $headers .= "Bcc: $trainer_email" . "\r\n";

                            mail($to, $subject, $message, $headers);

                            echo "<center>This session has been scheduled and an email has been successfully sent to the student.</center>";
                        } else {
                            echo "<center>This session could not be scheduled.</center>";
                        }
                        echo "<center><a href=\"" .
                            $core_url .
                            "/backend\">Return Home</a></center>";
                    }
                }
            }
        }
    }
}
$conn->close();

?>