<?php
require_once "SQLFunctions.php";

if(empty($_SESSION['userID'])) {
    $navHeading = 'Login now';
    $navItems = [
      'Home' => 'dashboard.php',
      'Help' => 'help.php'
    ];
} else {
    /*copy the session $userID to a local variable*/
    $userID = $_SESSION['userID'];
    $role = $_SESSION['role'];
    $navHeading = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
    $navItems = [
      'Home' => 'dashboard.php',
      'Help' => 'help.php',
      'Deficiencies' => empty($_SESSION['bdPermit']) 
        ? 'defs.php'
        : [
            'Project Deficiencies' => 'defs.php',
            'BART Deficiencies' => 'defs.php?view=BART'
          ],
      'Safety Certs' => 'ViewSC.php'
    ];

    if ($_SESSION['inspector']) {
      $navItems['Daily Report'] = 'idr.php';
    }

    if ($role >= 40) {
        $roleT = 'Super Admin';
    } elseif ($role >= 30) {
        $roleT = 'Administrator';
    } elseif ($role >= 20) {
        $roleT = 'User';
    } elseif ($role >= 10) {
        $roleT = 'Viewer';
    } else {
        $roleT = '';
    }
}
?>
<nav class="navbar navbar-expand-md navbar-dark navbar-vta-blue">
  <span class="navbar-brand navbar-heading">
    <a
      href="<?php echo empty($userID) ? 'login.php' : 'userAccount.php'; ?>"
      class='navbar-link navbar-brand-link'><?php echo $navHeading; ?></a>
  </span>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbar">
    <ul class="navbar-nav mr-auto">
      <?php
        // conditionally render nav items
        foreach ($navItems as $text => $element) {
          $classList = 'nav-link';
          $disableLink = ' disabled';
          if (is_string($element)) {
            if (strpos($_SERVER['PHP_SELF'], $element) !== false) $classList .= $disableLink;
            echo "
              <li class='nav-item'>
                <a href='$element' class='$classList'>$text</a>
              </li>";
          } elseif (is_array($element)) {
            $navLink = "
              <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='defsDropdown' role='button' data-toggle='dropdown' aria-expanded='false'>
                  Deficiencies
                </a>
                <div class='dropdown-menu' aria-labelledby='defsDropdown'>%s</div></li>";
              $subNavList = '';
              $itemClasslist = 'dropdown-item';
            foreach ($element as $text => $href) {
              if (strpos($_SERVER['PHP_SELF'], $href) !== false) $itemClasslist .= $disableLink;
              $subNavList .= "<a href='{$href}' class='{$itemClasslist}'>{$text}</a>";
            }
            printf($navLink, $subNavList);
          }
        }

        if($navHeading !== 'Login now') {
          echo "
            <li class='nav-item'>
              <a class='nav-link' href='logout.php'>Logout</a>
            </li>";
        }
      ?>
    </ul>
  </div>
</nav>
