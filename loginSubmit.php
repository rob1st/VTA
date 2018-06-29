<?php
require_once 'vendor/autoload.php';
require_once('SQLFunctions.php');
    
    /* Check if the user is already logged in */
    if (isset( $_SESSION['userID'] ))
        $message = 'User is already logged in';
    /* Check that username and password are populated */
    elseif (!isset($_POST['username'], $_POST['password']))
        $message = 'Please enter a valid username and password';
    /* Check username length */
    elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 4)
        $message = 'Incorrect Length for Username';
    /* Check password length */
    elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
        $message = 'Incorrect Length for Password';
    /* Check username for alpha numeric characters */
    elseif (ctype_alnum($_POST['username']) != true)
        $message = "Username must be alpha numeric";
    /* Check password for alpha numeric characters */
    elseif (ctype_alnum($_POST['password']) != true)
        $message = "Password must be alpha numeric";
    else $message = '';
    
    if ($message) {
        header('Location: login.php');
        $_SESSION['alert'] = $message;
        exit;
    }
    
    try {
        $link = connect();
        if (!$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS))
            throw new UnexpectedValueException('Please enter a valid username');
        
        if (!$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS))
            throw new UnexpectedValueException('Please enter a valid password');
        
        // $sql = "SELECT userID, password, role, secQ FROM users_enc WHERE Username = '".$username."'";
        $fields = [
            'userID',
            'username',
            'firstname',
            'lastname',
            'password',
            'role',
            'level',
            'secQ'
        ];
        
        $link->where('username', $username);
        
        if (!$result = $link->getOne('users_enc', $fields))
            throw new mysqli_sql_exception("User does not exist");

        $auth = password_verify($password, $result['password']);
            
        if ($auth) {
            // Set session variables
            session_start();
            
            $_SESSION['userID'] = $result['userID'];
            $_SESSION['username'] = $result['username'];
            $_SESSION['firstname'] = $result['firstname'];
            $_SESSION['lastname'] = $result['lastname'];
            $_SESSION['role'] = $result['role'];
            $_SESSION['level'] = $result['level'];
            $_SESSION['timeout'] = time();
            
            // $sql = "UPDATE users_enc SET lastLogin = NOW() WHERE username = '{$result['username']}'";
            $link->where('username', $result['username']);
            $link->update('users_enc', ['lastLogin' => 'NOW()']);
            // or die("Insert failed. " . mysqli_error($link));
                
            if (!$result['secQ']) {
                header('location: setSQ.php');
            } else {
                header('location: dashboard.php');
            }
        } else {
            throw new UnexpectedValueException("Failed to log in");
        }
    } catch (Exception $e) {
        echo "<pre style='color: darkBlue'>$e</pre>";
        exit;
    }
/*
include('filestart.php');
?>
<main role="main">

      <header class="container page-header">
        <img src="vta_logo.png">
        <h1 class="page-title">Silicon Valley Berryessa Extension</h1>
      </header>

        <div class="container">
            <h2 style="text-align:center"><?php echo $message; ?> </h2>
        </div>
        <!-- Example row of columns -->
        <!-- /container -->

    </main>
<?php 
include('fileend.php');
?>*/
