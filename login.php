<HTML>
    <HEAD>
        <TITLE>Login</TITLE>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
    </HEAD>
<BODY>
<?php include('filestart.php') ?>
    <h1>Login</h1>
<body>
  <form action="loginSubmit.php" method="post">
  <fieldset>
    <p style="color:black">
      <label>Username</label>
      <input type="text" name="Username" value="" maxlength="20" />
    </p>
    <p style="color:black">
      <label>Password</label>
      <input type="password"  name="Password" value="" maxlength="20" />
    </p>
    <p>
      <input type="submit" value="Login" />
    </p>
  </fieldset>
  </form>
<?php include 'fileend.php';?>
</body>
</html>