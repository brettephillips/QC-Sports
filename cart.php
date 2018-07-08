<?php
    //Name and start the session
    session_name("userSession");
    //Do not start the session if there is one already running
    if(!isset($_SESSION)) {
        session_start();
    }

    //Include the needed files
    include "MyUtils.class.php";
    include "LIB_project1.class.php";
    
    //Display the start of the html file and the navbar
    echo MyUtils::htmlStart("QC Sports - Cart", "style.css");
    echo MyUtils::navbar();

    //Check to see if the user is logged in
    if(isset($_SESSION['loggedIn'])) {
        //Instantiate the Lib object
        $lib = new Lib();

        //Check to see if the empty cart button was clicked
        if(isset($_POST['EmptyCart'])) {
            //Empty the cart and display message
            echo $lib->deleteCartItems();
        }
        //Display the cart items
        echo $lib->displayCartItems();
    } else {
        //If they are not logged in, then redirect them to the login page
        header("Location: login.php");
    }

    //Display the end of the html file
    echo MyUtils::htmlEnd();
?>