<?php
require "../style.php";
require "../requires.php";
?>
<h3>Backend Login</h3>
<center>
<table id="customers">
    <form method="post" action="<?php echo htmlspecialchars(
        "$core_url/backend/authenticate.php"
    ); ?>">
    <tr><td><b>VATSIM CID:</b></td><td><input type="text" name="username" maxlength="9" size="10" required></td></tr>
    <tr><td><b>Password:</b></td><td><input type="password" name="password" required></td></tr>
    <tr><td colspan="2"><center><input type="submit" name="submit" value="Submit"></center></td></tr>
</table>
</center>