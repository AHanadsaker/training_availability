<?php
require "requires.php";
require "style.php";
?>

<body>
    <div class="center">

<?php if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cid = test_input($_POST["cid"]);
    $type = test_input($_POST["type"]);

    $start_time = test_input($_POST["start"]);
    $start_unix = strtotime($start_time);
    $start_utc = $start_unix + $mult_bias;
    $end_time = test_input($_POST["end"]);
    $end_unix = strtotime($end_time);
    $end_utc = $end_unix + $mult_bias;

    if ($start_utc < $now) {
        echo "<center><font color=red><b>ERROR:</b> Your availability has <b><i>not</i></b> been posted.<br><br>The start date/time cannot be in the past.</font></center>";
    } else {
        if ($start_utc == $end_utc) {
            echo "<center><font color=red><b>ERROR:</b> Your availability has <b><i>not</i></b> been posted.<br><br>The start date/time cannot be the same as the end date/time.</font></center>";
        } else {
            if ($start_utc > $end_utc) {
                echo "<center><font color=red><b>ERROR:</b> Your availability has <b><i>not</i></b> been posted.<br><br>The end date/time cannot be before the start date/time.</font></center>";
            } else {
                $conn = new mysqli($servername, $username, $password, $dbname);

                $sql = "INSERT INTO availability (cid,type, start, end) VALUES ('$cid', '$type', '$start_utc', '$end_utc')";

                if ($conn->query($sql) === true) {
                    echo "Your availability entry has been successfully entered.";
                    echo "<hr>";
                    echo "<a href=\"" . $core_url . "\">Return Home</a>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
                $conn->close();
            }
        }
    }
}
?>