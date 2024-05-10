<?php
include "style.php";
require "requires.php";
?>
<?php #http://www.php.net/manual/en/timezones.php List of Time Zones

function showclienttime()
{
    if (!isset($_COOKIE["GMT_bias"])) {
    } else {
        $fct_clientbias = $_COOKIE["GMT_bias"];
    }
    $fct_servertimedata = gettimeofday();
    $fct_servertime = $fct_servertimedata["sec"];
    $fct_serverbias = $fct_servertimedata["minuteswest"];
    $fct_totalbias = $fct_serverbias - $fct_clientbias;
    $fct_totalbias = $fct_totalbias * 60;
    $fct_clienttimestamp = $fct_servertime + $fct_totalbias;
    $fct_time = time();
    $fct_year = strftime("%Y", $fct_clienttimestamp);
    $fct_month = strftime("%m", $fct_clienttimestamp);
    $fct_day = strftime("%d", $fct_clienttimestamp);
    $fct_hour = strftime("%H", $fct_clienttimestamp);
    $fct_minute = strftime("%M", $fct_clienttimestamp);
    echo $fct_month .
        "/" .
        $fct_day .
        "/" .
        $fct_year .
        " " .
        $fct_hour .
        ":" .
        $fct_minute;
} ?>
    <center>
    <h3>Post Training Availability</h3>
    <form method="post" action="<?php echo htmlspecialchars(
        "" . $core_url . "/submit.php"
    ); ?>">
        <table id="customers" width="75%">
            <tr><td><b>VATSIM CID:</b></td><td><input type="text" name="cid" maxlength="9" size="10" required></td></tr>
            <tr><td><b>Type:</b></td><td><select name="type" required>
                <?php
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM types ORDER BY id ASC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<option>" . $row["name"] . "</option>";
                    }
                }
                ?>
            </select></td></tr>
            <tr><td colspan="2"><center>Your system reports the current time as: <b><?php showclienttime(); ?></b></center></td></tr>
            <tr><td colspan="2"><center><b><i><font color=red>NOTICE:</b> Enter the following times as <b>your local</b> time, <b>not</b> zulu (UTC).</font></i></center></td></tr>
            <tr><td><b>Start:</b></td><td><input type="datetime-local" name="start" required></td></tr>
            <tr><td><b>End:</b></td><td><input type="datetime-local" name="end" required></td></tr>
            <tr><td colspan="20"><b>Before Submitting:</b><br>This is merely a <i>request</i>, not a guarantee of training.<br><br>You will receive an email (to the email you have on-file with <b><i>VATUSA</i></b>) if a training session is scheduled based on your availability.<br><br>If you cannot keep a training session that has been scheduled, it is <b><i>your</i></b> responsibility to cancel the training session using the link in the automated email or by notifying the Training Team.<br><br><center><b>I Understand</b> <input type="checkbox" name="acknowledge" required /></center></td></tr>
            <tr><td colspan="2"><center><input type="submit" name="submit" value="Submit"></center></td></tr>
        </table>
    </form>
    </center>
</div>
</body>