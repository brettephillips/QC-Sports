<?php
    //Name and start the session
    session_name("userSession");
    //Do not start the session if there is one already running
    if(!isset($_SESSION)) {
        session_start();
    }

    //Include needed files
    include "MyUtils.class.php";
    include "LIB_project1.class.php";

    //instantiate the Lib class
    $lib = new Lib();

    //Check to see if the loggedIn session variable is set
    if(isset($_SESSION['loggedIn'])) {
        //Check to see if the user is trying to logout
        if(isset($_POST['logout'])) {
            //If so, destroy their sessions
            session_unset();
            session_destroy();
        } else {
            //Redirect to the home page
            header("Location: index.php");
        }
    } else {
        //Check to see if the user is trying to login
        if(isset($_POST['login'])) {
            //Sanitize the data before calling the query
            $username = MyUtils::sanitizeData($_POST['username']);
            $password = MyUtils::sanitizeData($_POST['password']);

            //Validate the user
            $user = $lib->validateUser($username, $password);

            //If we have a user, then set their sessions
            if($user != null) {
                $_SESSION['loggedIn'] = "true";
                $_SESSION['userID'] = $user->getUserID();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['userType'] = $user->getUserType();

                //Redirect to the home page
                header("Location: index.php");
            }
        }
    }

    //Display the start of the html file and the navbar
    echo MyUtils::htmlStart("QC Sports - Login", "style.css");
    echo MyUtils::navbar();
    
    //Display the login form
    echo $lib->displayLoginForm();
    
    //Display the end of the html file
    echo MyUtils::htmlEnd();
?>