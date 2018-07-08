<?php
    //Include the needed files
    include "MyUtils.class.php";
    include "LIB_project1.class.php";
    
    //Display the start of the html file and the navbar
    echo MyUtils::htmlStart("QC Sports - Home", "style.css");
    echo MyUtils::navbar();

    //Instantiate the Lib object
    $lib = new Lib();
    
    //Check to see if the user clicked the add to cart button
    //Check to see if the value of the add cart button is AddToCart
    if(isset($_POST['AddToCart']) && $_POST['AddToCart'] == "AddToCart") {
        //If so, then we can add the item to the cart and display the added item message
        echo $lib->displayItemAddedMessage($_POST['item']);
    } else if(isset($_POST['AddToCart']) && $_POST['AddToCart'] == "Empty") {
        //If it says empty, then the item is out of stock
        //Present an item out of stock message
        echo $lib->displayItemEmptyMessage();
    }

    //Validate the page number
    if(isset($_GET['page'])) {
        $pageNum = $_GET['page'];
    } else {
        $pageNum = 1;
    }
    
    //Determine what page to start on and how many records we want
    //on each page
    $pageStart = ($pageNum - 1) * 5;

    //Display the sale and catalog items and pagination
    echo $lib->displaySaleItems();
    echo $lib->displayCatalogItems($pageStart);
    echo $lib->pagination(null);

    //Display the end of the html file
    echo MyUtils::htmlEnd();
?>