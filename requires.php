<?php
$servername = "localhost";
$username = "u274237833_vlzc";
$password = "v*4fwFp&J";
$dbname = "u274237833_vzlc";

$vatusaapi = "TvG7vUJXWrivMk2K";

$core_url = "https://txpg.net/vzlc";

$now = time();

$timezone = "UTC";

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

if (!isset($_COOKIE["GMT_bias"])) { ?>

            <script type="text/javascript">
                var Cookies = {};
                Cookies.create = function (name, value, days) {
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        var expires = "; expires=" + date.toGMTString();
                    }
                    else {
                        var expires = "";
                    }
                    document.cookie = name + "=" + value + expires + "; path=/";
                    this[name] = value;
                }

                var now = new Date();
                Cookies.create("GMT_bias",now.getTimezoneOffset(),1);
                window.location = "<?php echo $_SERVER["PHP_SELF"]; ?>";
            </script>

            <?php } else {$fct_clientbias = $_COOKIE["GMT_bias"];}

$fct_clientbias = $_COOKIE["GMT_bias"];
$mult_bias = $fct_clientbias * 60;
?>
<body>
    <div class="center">
        <h2>Salt Lake City ARTCC</h2>
<hr>