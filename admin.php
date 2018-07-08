<?php
    //Include the needed files
    include "MyUtils.class.php";
    include "LIB_project1.class.php";

    //Display the start of the html file and the navbar
    echo MyUtils::htmlStart("QC Sports - Admin", "style.css");
    echo MyUtils::navbar();
    
    //Check to see if the user is logged in
    if(isset($_SESSION['loggedIn'])) {
        //Instantiate the Lib object
        $lib = new Lib();

        //Check to see the type of user they are
        //if they are an admin, then display the form
        if(isset($_SESSION['userType']) && $_SESSION['userType'] == 'admin') {
            if(isset($_POST['AddItem']) || isset($_POST['UpdateItem'])) {
                //Sanitize the data before calling the query
                $productName = MyUtils::sanitizeData($_POST['productName']);
                $description = MyUtils::sanitizeData($_POST['description']);
                $price = MyUtils::sanitizeData($_POST['price']);
                $quantity = MyUtils::sanitizeData($_POST['quantity']);
                $salePrice = MyUtils::sanitizeData($_POST['salePrice']);
                
                //If image is not set, then set it to empty.png
                if(isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
                    $imageName = MyUtils::sanitizeData($_FILES['image']['name']);
                } else {
                    $imageName = "empty.png";                    
                }

                //Check all the values to make sure they are filled out
                //If not, then display an invalid error otherwise add the product
                if((isset($productName) && $productName != '') && (isset($description) && $description != '') && (isset($price) && $price != '') && (isset($quantity) && $quantity != '') && (isset($salePrice) && $salePrice != '')) {
                    //Check to see if we are adding an item, so we know what 
                    //method to use
                    if(isset($_POST['AddItem'])) {
                        echo $lib->displayNewItemMessage($productName, $description, $price, $quantity, $salePrice, $imageName);
                    } else {
                        echo $lib->displayUpdatedItemMessage($productName, $description, $price, $quantity, $salePrice, $imageName, $_POST['UpdateItem']);
                    }

                    //Upload the file if we have an image
                    if(isset($_FILES['image']) && $_FILES['image']['error'] != 4) {
                        $filename = basename($_FILES['image']['name']);
                        $newname = "images/" . $filename;

                        if (move_uploaded_file($_FILES['image']['tmp_name'],$newname)) {
                            chmod($newname,0644);
                        }
                    }
                } else {
                    echo $lib->displayFormError();
                }
            }
            //Check to see if the user clicked the edit item button
            if(isset($_POST['EditItem'])) {
                //If they did, then pass in the productID
                echo $lib->displayAdminForms($_POST['productID']);
            } else {
                //If they did not, then pass null since there is no object to edit
                echo $lib->displayAdminForms(null);
            }
        } else {
            //If they are not an admin, then display a message and dont let them access the page
            echo $lib->displayNotAdminMessage();
        }
    } else {
        //If they are not logged in, then redirect them to the login page
        header("Location: login.php");
    }

    //Display the end of the html file
    echo MyUtils::htmlEnd();
?>