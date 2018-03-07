<?php
include('session.php');
?>

<?php      
/* begin our session */
session_start();
?>

<html>
    <head>
      <title>Add New User</title>
      <link rel="stylesheet" href="styles.css" type="text/css"/>
    </head>
    <body>
      <?php include('filestart.php') ?>
        <h2>Add New User</h2>
        <form action="AddUserSubmit.php" method="post">
          <fieldset>
            <p>
              <label>First name</label>
              <input type="text" name="firstname" value="" maxlength="25" required/>
              <i>(4-25 characters)</i>
            </p>
            <p>
              <label>Last name</label>
              <input type="text" name="lastname" value="" maxlength="25" required/>
              <i>(4-25 characters)</i>
            </p>
            <p>
              <label>Email</label>
              <input type="text" name="Email" value="" maxlength="55" required/>
              <i>(4-55 characters)</i>
            </p>
            <p>
              <label>Username</label>
              <input type="text" name="username" value="" maxlength="20" required/>
              <i>(4-20 characters)</i>
            </p>
            <p>
              <label>Password</label>
              <input type="password" name="pwd" value="" maxlength="20" required/>
              <i>(4-20 characters)</i>
            </p>
            <p> 
              <input type="submit" value="Add User" />
            </p>
          </fieldset>
        </form>
        <?php include('fileend.php') ?>
    </body>
</html>