<?php include('filestart.php'); ?>

    <main role="main">

      <!-- Main jumbotron for a primary marketing message or call to action -->
      <img src="vta.jpg">
      <h1 class="display-3">Silicon Valley Berryessa Extension</h1>
      <p>This site if for use by personel working upon the SVBX project, if you are not working upon the SVBX project, but would like some information, please click on the 'Learn More' button below. </p>
      <a class="btn btn-primary btn-lg" href="http://www.vta.org/News-and-Media/Connect-with-VTA/Phase-I-of-BART-Silicon-Valley-Update#.WqbH0WrwZaQ" role="button">Learn more &raquo;</a>

      <div class="container">
        <form action="loginSubmit.php" method="post">
          <fieldset class="login">
            <div class="row">
              <div class="col-md-4">
                <h2>Username</h2>
                <input type="text" name="Username" value="" maxlength="20" />
            </div>
            <div class="col-md-4">
              <h2>Password</h2>
              <input type="password"  name="Password" value="" maxlength="20" /><br>
            </div>
            <input type="submit" name="submit" value="Login" class="btn btn-primary btn-lg"/>
            <a class="btn btn-primary btn-lg" href="ForgotPassword.php" role="button">Forgot Password</a>
            </div>
          </fieldset>
        </form> <!-- /container -->
      </div>

    </main>

    <?php include('fileend.php'); ?>
