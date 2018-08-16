<?php
    include('session.php');
    include('SQLFunctions.php');
    $table = 'users_enc';
    $q = $_POST["q"];
    $userRole = $_SESSION['role'];
    $title = "SVBX - Update User";
    $Loc = "SELECT Username, Role, firstname, lastname, Email, Company FROM $table WHERE UserID = $q";
    include('filestart.php');

    if($userRole < 30) {
        header('location: unauthorised.php');
        exit;
    }

    $link = f_sqlConnect();
?>
        <header class="container page-header">
            <h1 class="page-title">Update User Information</h1>
        </header>
<?php       if($stmt = $link->prepare($Loc)) {
            $stmt->execute();
            $stmt->bind_result($username, $targetRole, $firstname, $lastname, $email, $company);
            while ($stmt->fetch()) {
                echo "
                    <div class='container main-content'>";
                        if($userRole >= 30) {
                echo "
                        <form action='UpdateUserCommit.php' method='POST' onsubmit='' />
                            <input type='hidden' name='userID' value='".$q."'>
                            <table class='table'>
                                <tr class='usertr'>
                                    <th class='userth'>First name:</td>
                                    <td class='usertd'>
                                        <input type='text' name='firstname' maxlength='25' required value='$firstname'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Last name:</td>
                                    <td class='usertd'>
                                        <input type='text' name='lastname' maxlength='25' required value='$lastname'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Email Address:</td>
                                    <td class='usertd'>
                                        <input type='text' name='email' maxlength='55' required value='$email'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Username:</td>
                                    <td class='usertd'>
                                        <input type='text' name='username' maxlength='25' required value='$username'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Company:</td>
                                    <td class='usertd'>
                                        <input type='text' name='company' maxlength='25' required value='$company'/>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Password:</td>
                                    <td class='usertd'>
                                        <input type='password' name='password' maxlength='25' value=''/><br />
                                        <i>only complete if you want to change a users password</i>
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <th class='userth'>Confirm Password:</td>
                                    <td class='usertd'>
                                        <input type='password' name='conPwd' maxlength='25' value=''/><br />
                                        <i>confirm new password</i>
                                    </td>
                                </tr>";
                                if ($userRole >= 40) {
                                echo "
                                <tr class='usertr'>
                                    <th class='userth' rowspan='4'>Role:</td>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='40'";
                                        echo ( $targetRole === 40 ? ' checked' : '' );
                                        echo "/>Super Admin
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='30'";
                                        echo ( $targetRole === 30 ? ' checked' : '' );
                                        echo "/>Administrator
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='20'";
                                        echo ( $targetRole === 20 ? ' checked' : '' );
                                        echo "/>User
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='10'";
                                        echo ( $targetRole === 10 ? ' checked' : '' );
                                        echo "/>Read Only
                                    </td>
                                </tr>";
                                } elseif ($userRole >= 30) {
                                echo "
                                <tr class='usertr'>
                                    <th class='userth' rowspan='3'>Role:</td>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='30'";
                                        echo ( $targetRole === 30 ? ' checked' : '' );
                                        echo "/>Administrator
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='20'";
                                        echo ( $targetRole === 20 ? ' checked' : '' );
                                        echo "/>User
                                    </td>
                                </tr>
                                <tr class='usertr'>
                                    <td class='usertd'>
                                        <input type='radio' name='role' value='10'";
                                        echo ( $targetRole === 10 ? ' checked' : '' );
                                        echo "/>Read Only
                                    </td>
                                </tr>";
                                } else {
                                }
                                echo "
                                </table>
                                <input type='submit' value='submit' class='btn btn-primary btn-lg'/>
                                <input type='reset' value='reset' class='btn btn-primary btn-lg' />
                            </form>";
                        } else {
                            echo "<p style='text-align:center'>You do not have the authority to amend this user";
                            //echo "<br />ARole: ".$userRole;
                            //echo "<br />Role: ".$targetRole."</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo '<br>Unable to connect';
                    echo '<br />SQL: '.$Loc;
                    echo '<br />UserID: '.$q;
                    exit();
                }
        include('fileend.php') ?>
