<?php
if (isset($_GET['old']) && $_GET['old'] === 'true') {
      session_start();
      define ("INDEX_START", 1);
    $guest_allowed = true;
    $path2add = 0;
    include "include/baseTheme.php";
    include "modules/auth/auth.inc.php";
    //$homePage is used by baseTheme.php to parse correctly the breadcrumb
    $homePage = true;
    $tool_content = "";

    // first check
    // check if we can connect to database. If not then eclass is most likely not installed
    if (isset($mysqlServer) and isset($mysqlUser) and isset($mysqlPassword)) {
      $db = mysql_connect($mysqlServer, $mysqlUser, $mysqlPassword);
      if (mysql_version()) mysql_query("SET NAMES utf8");
    }
    if (!$db) {
      include "include/not_installed.php";
    }

    // unset system that records visitor only once by course for statistics
    include('include/action.php');
    if (isset($dbname)) {
            mysql_select_db($dbname);
            $action = new action();
            $action->record('MODULE_ID_UNITS', 'exit');
    }
    unset($dbname);

    // second check
    // can we select a database? if not then there is some sort of a problem
    if (isset($mysqlMainDb)) $selectResult = mysql_select_db($mysqlMainDb,$db);
    if (!isset($selectResult)) {
      include "include/not_installed.php";
    }

    //if platform admin allows usage of eclass personalised
    //create a session so that each user can activate it for himself.
    if (isset($persoIsActive)) {
      $_SESSION["perso_is_active"] = $persoIsActive;
    }

    // if we try to login... then authenticate user.
    $warning = '';
    if (isset($_SESSION['shib_uname'])) { // authenticate via shibboleth
      include 'include/shib_login.php';
    } else { // normal authentication
      if (isset($_POST['uname'])) {
        $uname = escapeSimple(preg_replace('/ +/', ' ', trim($_POST['uname'])));
      } else {
        $uname = '';
      }
      
      $pass = isset($_POST['pass'])?$_POST['pass']:'';
      $submit = isset($_POST['submit'])?$_POST['submit']:'';
      $auth = get_auth_active_methods();
      $is_eclass_unique = is_eclass_unique();

      if(!empty($submit)) {
        unset($uid);
        $sqlLogin= "SELECT user_id, nom, username, password, prenom, statut, email, perso, lang
          FROM user WHERE username='".$uname."'";
        $result = mysql_query($sqlLogin);
        $check_passwords = array("pop3","imap","ldap","db");
        $warning = "";
        $auth_allow = 0;
        $exists = 0;
                    if (!isset($_COOKIE) or count($_COOKIE) == 0) {
                            // Disallow login when cookies are disabled
                            $auth_allow = 5;
                    } elseif (empty($pass)) {
                            // Disallow login with empty password
          $auth_allow = 4;
        } else {
          while ($myrow = mysql_fetch_array($result)) {
            $exists = 1;
            if(!empty($auth)) {
              if(!in_array($myrow["password"],$check_passwords)) {
                // eclass login
                include "include/login.php"; 
              } else {
                // alternate methods login
                include "include/alt_login.php";
              }
            } else {
              $tool_content .= "<br>$langInvalidAuth<br>";
            }
          }
        }
        if(empty($exists) and !$auth_allow) {
          $auth_allow = 4;
        }
        if (!isset($uid)) {
          switch($auth_allow) {
            case 1 : $warning .= ""; 
              break;
            case 2 : $warning .= "<br /><font color='red'>".$langInvalidId ."</font><br />"; 
              break;
            case 3 : $warning .= "<br />".$langAccountInactive1." <a href='modules/auth/contactadmin.php?userid=".$user."'>".$langAccountInactive2."</a><br /><br />"; 
              break;
            case 4 : $warning .= "<br /><font color='red'>". $langInvalidId . "</font><br />"; 
              break;
            case 5 : $warning .= "<br /><font color='red'>". $langNoCookies . "</font><br />"; 
              break;
            default:
              break;
          }
        } else {
          $warning = '';
          $log='yes';
          $_SESSION['nom'] = $nom;
          $_SESSION['prenom'] = $prenom;
          $_SESSION['email'] = $email;
          $_SESSION['statut'] = $statut;
          $_SESSION['is_admin'] = $is_admin;
          $_SESSION['uid'] = $uid;
          mysql_query("INSERT INTO loginout (loginout.idLog, loginout.id_user, loginout.ip, loginout.when, loginout.action)
          VALUES ('', '$uid', '$_SERVER[REMOTE_ADDR]', NOW(), 'LOGIN')");
        }
      
        ##[BEGIN personalisation modification]############
        //if user has activated the personalised interface
        //register a control session for it
        if (isset($_SESSION['perso_is_active']) and (isset($userPerso))) {
          $_SESSION['user_perso_active'] = $userPerso;
        }
        ##[END personalisation modification]############
      }  // end of user authentication
    } 
      
    if (isset($_SESSION['uid'])) { 
      $uid = $_SESSION['uid'];
    } else { 
      unset($uid);
    }
    // if the user logged in include the correct language files
    // in case he has a different language set in his/her profile
    if (isset($language)) {
            // include_messages
            include("${webDir}modules/lang/$language/common.inc.php");
            $extra_messages = "${webDir}/config/$language.inc.php";
            if (file_exists($extra_messages)) {
                    include $extra_messages;
            } else {
                    $extra_messages = false;
            }
            include("${webDir}modules/lang/$language/messages.inc.php");
            if ($extra_messages) {
                    include $extra_messages;
            }

    }
    $nameTools = $langWelcomeToEclass;
      
    //----------------------------------------------------------------
    // if login succesful display courses lists
    // --------------------------------------------------------------
    if (isset($uid) AND !isset($logout)) {
      $nameTools = $langWelcomeToPortfolio;
      $require_help = true;
      $helpTopic="Portfolio";
      if (isset($_SESSION['user_perso_active']) and $_SESSION['user_perso_active'] == 'no') {
        if (!check_guest()){
          //if the user is not a guest, load classic view
          include "include/logged_in_content.php";
          draw($tool_content,1,null,null,null,null,$perso_tool_content);
        } else {
          //if the user is a guest send him straight to the corresponding lesson
          $guestSQL = db_query("SELECT code FROM cours_user, cours
                          WHERE cours.cours_id = cours_user.cours_id AND
                                                        user_id = $uid", $mysqlMainDb);
          if (mysql_num_rows($guestSQL) > 0) {
            $sql_row = mysql_fetch_row($guestSQL);
            $dbname=$sql_row[0];
            session_register("dbname");
            header("location:".$urlServer."courses/$dbname/index.php");
          } else { // if course has deleted stop guest account
            $warning = "<br><font color='red'>".$langInvalidGuestAccount."</font><br>";
            include "include/logged_out_content.php";
            draw($tool_content, 0,'index');
          }
        }
      } else {
        //load classic view
        include "include/classic.php";
        draw($tool_content, 1, 'index');
      }
    }	// end of if login

    // -------------------------------------------------------------------------------------
    // display login  page
    // -------------------------------------------------------------------------------------
    elseif ((isset($logout) && isset($uid)) OR (1==1)) {
      if (isset($logout) && isset($uid)) {
        mysql_query("INSERT INTO loginout (loginout.idLog, loginout.id_user,
          loginout.ip, loginout.when, loginout.action)
          VALUES ('', '$uid', '$REMOTE_ADDR', NOW(), 'LOGOUT')");
        unset($prenom);
        unset($nom);
        unset($statut);
        unset($_SESSION['uid']);
        session_destroy();
      }
      $require_help = true;
      $helpTopic="Init";
      include "include/logged_out_content.php";
      draw($tool_content, 0,'index');
    } // end of display
} else {
  ?>
  <head>
    <style>
      .center {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
  
      .image-container {
        display: flex;
        justify-content: center;
      }
  
      img {
        width: 400px;
        height: 400px;
        object-fit: cover;
        margin: 10px;
      }
  
      body {
        margin: 0;
        padding: 0;
        background: url('https://media.tenor.com/8-l7HBzVDJYAAAAC/robot-fail.gif') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        color: black;
        text-shadow: 2px 2px 0 white, -2px -2px 0 white, 2px -2px 0 white, -2px 2px 0 white;
      }
    </style>
  </head>
  <body>
    <div class="center">
      <h1><?= "Googling Seminar by Psarotaverna o takis" ?></h1>
      <iframe src="https://www.facebook.com/plugins/video.php?height=420&href=https%3A%2F%2Fwww.facebook.com%2F100064188126442%2Fvideos%2F2194316944103282%2F&show_text=false&width=560&t=0" width="560" height="420" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true" autoplay></iframe>
      <br>
      <h1><?= "Presented by:" ?></h1>
      <div class="image-container">
        <img src="https://debater.gr/wp-content/uploads/2022/05/%CE%B3%CE%B7%CE%BA%CE%BE%CE%B7%CE%B8%CE%BE%CE%B7%CE%B71.jpg">
        <img src="https://image2.fthis.gr/userfiles/articles/AAA_DECEMBER/MAMALAKIS_DIMITRIS.jpg?w=600&h=600&mode=crop&scale=both&quality=85&anchor=topcenter" width="500" height="500"> 
        <img src="https://img.uncyc.org/el/thumb/c/c7/%CE%91%CF%85%CF%84%CE%B1_%CE%BC%CE%B1%CF%81%CE%B5%CF%83%CE%BF%CF%85%CE%BD.gif/300px-%CE%91%CF%85%CF%84%CE%B1_%CE%BC%CE%B1%CF%81%CE%B5%CF%83%CE%BF%CF%85%CE%BD.gif">
      </div>
    </div>
    <h1>
            <a href="http://puppies.localhost:8001/index.php?old=true">Go back to the old site!</a>
    </h1>
  </body>
  <?php
}
?>
