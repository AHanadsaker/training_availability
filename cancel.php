<?php
require "requires.php";
include "style.php";

$conn = mysqli_connect($servername, $username, $password, $dbname);
?>
<body>
    <div class="center">
<?php
$sql = "UPDATE sessions SET status='2' WHERE id ='" . mysqli_real_escape_string($conn, $_GET['id']) ."'";
if(mysqli_query($conn, $sql)) {
    
    $sql = "SELECT * FROM sessions WHERE id ='" . mysqli_real_escape_string($conn, $_GET['id']) ."'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "test";
        $student_cid = $row["student_cid"];
        $start_unix = $row["start"];
        $end_unix = $row["end"];
        $start_email = date("m/d/Y H:i T", $start_unix);
        $end_email = date("m/d/Y H:i T", $end_unix);
    }

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

    $to = $student_email;
    $subject = "[vZLC] Session Canceled";

    $message =
        "
<html>
<b>Greetings $student_fname,</b><br><br>
The following training session has been canceled and will <b><i>not</i></b> occur.<br><br>

<b>Start Date/Time (Zulu):</b> " .
        $start_email .
        "<br>
<b>End Date/Time (Zulu):</b> " .
        $end_email .
        "
<br><br>
If you have any questions, please reach out to the Training Administrator.
";

    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // More headers
    $headers .= "From: vZLC <noreply@zlcartcc.org>" . "\r\n";

    mail($to, $subject, $message, $headers);
    echo "This session has been canceled.";
} } else {
    echo "This session could not be canceled. Please contact the Training Administrator.";
}
mysqli_close($conn);

?>