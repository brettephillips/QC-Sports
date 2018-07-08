<?php
    //Name and start the session
    session_name("userSession");
    //Do not start the session if there is one already running
    if(!isset($_SESSION)) {
        session_start();
    }

    /**
     * Class that contains reusable code that will be used
     * globally on all pages.
     */
    class MyUtils {
        /**
         * Static function that will create the start of an
         * html file.
         */
        static function htmlStart($title, $style) {
            $bigString = <<<END
<!doctype html>\n
<html lang="en">\n
<head>\n
<meta charset="utf-8">\n
<meta name="viewport" content="width=device-width, initial-scale=1.0">\n
<title>$title</title>\n
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous" />\n
<link rel="stylesheet" href="css/$style" />\n
</head>\n
<body>\n
END;
            return $bigString;
        }

        /**
         * Static function that will create the navbar
         */
        static function navbar() {
            $bigString = "<nav class='navbar fixed-top navbar-expand-lg navbar-dark bg-dark'>\n
                            <a class='navbar-brand' href='index.php'>QC Sports</a>\n
                            <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>\n
                                <span class='navbar-toggler-icon'></span>\n
                            </button>\n
                            <div class='collapse navbar-collapse' id='navbarNav'>\n
                                <ul class='navbar-nav'>\n
                                    <li class='nav-item'>\n
                                        <a class='nav-link' href='index.php'>Home</a>\n
                                    </li>\n
                                    <li class='nav-item'>\n
                                        <a class='nav-link' href='cart.php'>Cart</a>\n
                                    </li>\n
                                    <li class='nav-item'>\n
                                        <a class='nav-link' href='admin.php'>Admin</a>\n
                                    </li>\n
                                </ul>\n
                                <ul class='navbar-nav ml-auto'>\n
                                    <li class='nav-item'>\n";
            //If the user is logged in, then we need to display a logout button
            if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == "true") {
                $bigString .= "<form action='login.php' method='post'>\n
                                    <button class='btn btn-outline-primary' name='logout' value='logout' type='submit'>Logout</button>\n
                                </form>\n</li>\n</ul>\n</div>\n</nav>";
            } else {
                //Else, we can display a login button
                $bigString .= "<form action='login.php'>\n
                                    <button class='btn btn-outline-primary' name='login' value='login' type='submit'>Login</button>\n
                                </form>\n</li>\n</ul>\n</div>\n</nav>";
            }

            return $bigString;
        }

        /**
         * Static function that will create the end of an
         * html file.
         */
        static function htmlEnd() {
            $smallString ="<script src='https://code.jquery.com/jquery-3.2.1.slim.min.js' integrity='sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN' crossorigin='anonymous'></script>\n
                           <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' integrity='sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl' crossorigin='anonymous'></script>\n
                           </body>\n
                           </html>";
            
            return $smallString;
        }
        
        /**
         * Function that will sanitize all of the input.
         */
        static function sanitizeData($input) {
            $input = trim($input);
            $input = stripslashes($input);
            $input = strip_tags($input);
            $input = htmlentities($input);
            $input = htmlspecialchars($input);

            return $input;
        }
    }