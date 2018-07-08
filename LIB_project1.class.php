<?php
    //Name and start the session
    session_name("userSession");
    //Do not start the session if there is one already running
    if(!isset($_SESSION)) {
        session_start();
    }

    //Include the needed files
    include "DB.class.php";

    /**
     * Class that will contain reusable code that is meant to
     * display information to the screen.
     */
    class Lib {
        //Instance variables
        private $db;

        /**
         * Empty default constructor
         */
        function __construct() {
        }

        /**
         * Function that will display the login form
         * for the user to fill out.
         */
        function displayLoginForm() {
            $bigString = "<div class='container-fluid'>
                            <h1>Login</h1>\n
                            <form class='login' action='login.php' method='post'>\n
                                <div class='form-group'>\n
                                    <label for='usernameInput'>Username</label>\n
                                    <input type='text' name='username' class='form-control' id='usernameInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='passwordInput'>Password</label>\n
                                    <input type='password' name='password' class='form-control' id='passwordInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <button type='submit' name='login' value='login' id='loginButton' class='btn btn-outline-dark btn-block'>Login</button>\n
                                </div>\n
                            </form>\n
                          </div>";
            
            return $bigString;
        }

        /**
         * Function that will return whether a user
         * has been found.
         */
        function validateUser($username, $password) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->getUser($username, $password);
            //Close the DB
            $this->db = null;

            //Check to see if the count is 1
            //It should be since usernames and passwords are unique values
            if(count($data) == 1) {
                //Get the first index and assign it to user
                $user = $data[0];

                return $user;
            } else {
                //The count was not 1, so we know the user
                //does not exist
                return null;
            }
        }

        /**
         * Function that will display all of the items
         * that are on sale.
         */
        function displaySaleItems() {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->getAllSaleProducts(0.00);
            //Begin the html code (divs)
            $bigString = "<div class='container-fluid'>\n
                            <h1>Sale Items</h1>\n
                            <div class='card-deck'>\n";
            
            //Loop through the data array and create a bootstrap card for each object
            foreach($data as $product) {
                $bigString .= "<div class='card'>\n
                                    <img class='card-img-top' src='images/{$product->getImageName()}' alt='images/{$product->getImageName()}'>\n
                                    <div class='card-body'>\n
                                        <h5 class='card-title'>{$product->getProductName()}</h5>\n
                                        <p class='card-text'>{$product->getDescription()}</p>\n
                                    </div>\n
                                    <div class='card-footer'>\n
                                        <div class='row'>\n
                                            <div class='col-4'>\n
                                                <p class='card-text text-success'><small>\${$product->getSalePrice()}</small></p>\n
                                            </div>\n
                                            <div class='col-4'>\n
                                                <p class='card-text text-center text-danger'><small>\${$product->getPrice()}</small></p>\n
                                            </div>\n
                                            <div class='col-4'>\n
                                                <p class='card-text text-right'><small>{$product->getQuantity()} left</small></p>\n
                                            </div>\n
                                        </div>\n";
                
                //Check to see if the user is logged in
                //If they are, then they can add items to their cart
                if(isset($_SESSION['loggedIn'])) {
                    $bigString .= "<form action='index.php' method='post'>\n
                                        <input type='hidden' name='item' value='{$product->getProductID()}' />\n";
                    if($product->getQuantity() == 0) {
                        $bigString .=  "<button type='submit' name='AddToCart' value='Empty' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                        </form>\n</div>\n</div>";
                    } else {
                        $bigString .=  "<button type='submit' name='AddToCart' value='AddToCart' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                        </form>\n</div>\n</div>";
                    }
                } else {
                    //If not, then they have to sign in before adding items to their cart
                    $bigString .= "<form action='login.php'>\n
                                        <button type='submit' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                   </form>\n</div>\n</div>";
                }
            }

            //End the html code (divs)
            $bigString .= "</div></div>";
            //Close the DB
            $this->db = null;

            return $bigString;
        }

        /**
         * Function that will display all of the items
         * that are in the catalog.
         */
        function displayCatalogItems($pageStart) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->getAllCatalogProducts(0.00, $pageStart);
            //Begin the html code (divs)
            $bigString = "<div class='container-fluid'>\n
                            <h1>Catalog Items</h1>\n";
            
            //Loop through the data array and create a bootstrap card for each object
            foreach($data as $product) {
                $bigString .= "<div class='card'>\n
                                    <div class='card-body'>\n
                                        <div class='row'>\n
                                            <div class='col-6'>\n
                                                <img class='img-fluid' src='images/{$product->getImageName()}' height='200' width='200' alt='images/{$product->getImageName()}'>\n
                                            </div>\n
                                            <div class='col-6'>\n
                                                <h5 class='card-title'>{$product->getProductName()}</h5>\n
                                                <p class='card-text'>{$product->getDescription()}</p>\n
                                                <div class='row'>\n
                                                    <div class='col-6'>\n
                                                        <p class='card-text'><small>\${$product->getPrice()}</small></p>\n
                                                    </div>\n
                                                    <div class='col-6'>\n
                                                        <p class='card-text text-right'><small>{$product->getQuantity()} left</small></p>\n
                                                    </div>\n
                                                </div>\n";
                
                //Check to see if the user is logged in
                //If they are, then they can add items to their cart
                if(isset($_SESSION['loggedIn'])) {
                    $bigString .= "<form action='index.php' method='post'>\n
                                        <input type='hidden' name='item' value='{$product->getProductID()}' />\n";
                    //Check to see if the quantity of the product is zero
                    //If it is, then give it a value of Empty
                    if($product->getQuantity() == 0) {
                        $bigString .=  "<button type='submit' name='AddToCart' value='Empty' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                        </form>\n</div>\n</div>\n</div>\n</div>";
                    } else {
                        //Else give it a name of AddToCart
                        $bigString .=  "<button type='submit' name='AddToCart' value='AddToCart' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                        </form>\n</div>\n</div>\n</div>\n</div>";
                    }
                } else {
                    //If not, then they have to sign in before adding items to their cart
                    $bigString .= "<form action='login.php'>\n
                                        <button type='submit' class='btn btn-outline-secondary btn-block'>Add To Cart</button>\n
                                   </form>\n</div>\n</div>\n</div>\n</div>";
                }
            }

            //Close the DB
            $this->db = null;

            return $bigString;
        }

        /**
         * Function that will add the item to the store
         * as well as display a success or error message to the admin.
         */
        function displayNewItemMessage($productName, $description, $price, $quantity, $salePrice, $imageName) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->addNewProduct($productName, $description, $price, $quantity, $salePrice, $imageName);
            //Close the DB
            $this->db = null;
            //Display a message to the admin, so they know if it was successful
            return $this->getUpdatedAddedMessage($data);
        }

        /**
         * Function that will update the item in the store
         * as well as display a success or error message to the admin.
         */
        function displayUpdatedItemMessage($productName, $description, $price, $quantity, $salePrice, $imageName, $productID) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->updateProduct($productName, $description, $price, $quantity, $salePrice, $imageName, $productID);
            //Close the DB
            $this->db = null;
            //Display a message to the admin, so they know if it was successful
            return $this->getUpdatedAddedMessage($data);
        }

        /**
         * Function that will get the success or error
         * message when the admin adds or updates an item.
         */
        function getUpdatedAddedMessage($data) {
            //If data is null, then there are to many items on sale
            if(isset($data)) {
                return "<div class='alert alert-success alert-dismissible fade show' role='alert'>\n
                            You have successfully added/updated an item in the store!\n
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                                <span aria-hidden='true'>&times;</span>\n
                            </button>\n
                        </div>";
            } else {
                return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>\n
                            There was an error adding the new item to the store. You can only have a maximum of 5 items on sale and a minimum of 3 items on sale.\n
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                                <span aria-hidden='true'>&times;</span>\n
                            </button>\n
                        </div>";
            }
        }

        /**
         * Function that will add the item to the users cart
         * as well as display a success or error message to the user.
         */
        function displayItemAddedMessage($productID) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->addItemToCart($_SESSION['userID'], $productID);
            //Close the DB
            $this->db = null;
            
            //If data is null, then there was an error somewhere
            if($data != null) {
                return "<div class='alert alert-success alert-dismissible fade show' role='alert'>\n
                            You have successfully added the item to your cart! <a href='cart.php' class='alert-link'>Go To Cart</a>\n
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                                <span aria-hidden='true'>&times;</span>\n
                            </button>\n
                        </div>";
            } else {
                return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>\n
                            There was an error adding the item to your cart!\n
                            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                                <span aria-hidden='true'>&times;</span>\n
                            </button>\n
                        </div>";
            }
        }

        /**
         * Function that will display a message to the user
         * letting them no that the item is not in stock.
         */
        function displayItemEmptyMessage() {
            return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>\n
                        Sorry, but this item is currently not in stock. Check back again soon!\n
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                            <span aria-hidden='true'>&times;</span>\n
                        </button>\n
                    </div>";
        }

        /**
         * Function that will display a message to the admin
         * letting them no that the form was invalid.
         */
        function displayFormError() {
            return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>\n
                        Please make sure you enter a value for every field.\n
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                            <span aria-hidden='true'>&times;</span>\n
                        </button>\n
                    </div>";
        }
        
        /**
         * Function that will display all of the items
         * that are in the users cart.
         */
        function displayCartItems() {
            //Local variable for the total cost in the cart
            $totalCost = 0.00;
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->getUserCartProducts($_SESSION['userID']);

            //Check to see if data is null
            //It will be null if the user has nothing in their cart
            if($data == null) {
                //return to get out of the method
                return "<div class='container-fluid'>\n<h1>Cart Items</h1><p id='emptyCart'>Your cart is currently empty</p><h1>Total Cost: $0.00</h1></div>\n";
            }

            //Begin the html code (divs)
            $bigString = "<div class='container-fluid'>\n
                            <div class='row'>\n
                                <div class='col-6'>\n
                                    <h1>Cart Items</h1>\n
                                </div>\n
                                <div class='col-6'>\n
                                    <form action='cart.php' method='post' id='emptyForm'>\n
                                        <button type='submit' name='EmptyCart' value='EmptyCart' class='btn btn-outline-dark float-right'>Empty Cart</button>\n
                                    </form>\n
                                </div>\n
                            </div>\n";
            
            //Loop through the data array and create a bootstrap card for each object
            foreach($data as $cartItem) {
                $bigString .= "<div class='card'>\n
                                    <div class='card-body'>\n
                                        <div class='row'>\n
                                            <div class='col-12'>\n
                                                <h5 class='card-title'>{$cartItem->getProductName()}</h5>\n
                                                <p class='card-text'>{$cartItem->getDescription()}</p>\n";
                            
                //Check to see if the sale price doesnt equal 0
                //If it does not, then the item is on sale
                if($cartItem->getSalePrice() != 0){
                    //Add the sale price to the total cart amount
                    $totalCost += $cartItem->getSalePrice();
                    $bigString .= "<p class='card-text'><small>Quantity: 1</small></p>\n
                                   <p class='card-text'><small>Price: \${$cartItem->getSalePrice()}</small></p>\n           
                                   </div>\n</div>\n</div>\n</div>\n";
                } else {
                    //Else, the item is not on sale
                    //Add the original price to the total cart amount
                    $totalCost += $cartItem->getPrice();
                    $bigString .= "<p class='card-text'><small>Quantity: 1</small></p>\n
                                   <p class='card-text'><small>Price: \${$cartItem->getPrice()}</small></p>\n
                                   </div>\n</div>\n</div>\n</div>\n";
                }
                                                
            }

            $bigString .= "<h1>Total Cost: $$totalCost</h1></div>";

            //Close the DB
            $this->db = null;

            return $bigString;
        }

        /**
         * Function that will delete all of the cart items.
         */
        function deleteCartItems() {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->deleteUserCartProducts($_SESSION['userID']);
            //Close the DB
            $this->db = null;

            //Make sure some records were affected to ensure it emptied successfully
            if($data > 0){
                return "<div class='alert alert-success alert-dismissible fade show' role='alert'>\n
                        You have successfully emptied the items in your cart. <a href='index.php' class='alert-link'>Continue Shopping!</a>\n
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                            <span aria-hidden='true'>&times;</span>\n
                        </button>\n
                    </div>";
            } else {
                //If no records were affected, then there was an error
                return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>\n
                        There was an error when emptying your cart, please try again.\n
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>\n
                            <span aria-hidden='true'>&times;</span>\n
                        </button>\n
                    </div>";
            }
        }

        /**
         * Function that will print out a message to the user
         * if they try and access the admin page.
         */
        function displayNotAdminMessage() {
            return "<div class='container-fluid'>\n
                        <h1>Admin</h1>\n
                        <p id='notAdmin'>You do not have access to this page</p>\n
                    </div>";
        }

        /**
         * Function that will display the admin forms
         * for the admin to fill out.
         */
        function displayAdminForms($productID) {
            //Call the methods to get the admin forms
            $forms = $this->displayEditItemForm($productID);
            $forms .= $this->displayAddItemForm();

            return $forms;
        }

        /**
         * Function that will return the html for the
         * add item form.
         */
        function displayAddItemForm(){
            $bigString = "<form class='admin' action='admin.php' method='post' enctype='multipart/form-data'>\n
                                <h3>Add Item</h3>\n
                                <div class='form-group'>\n
                                    <label for='productNameInput'>Product Name</label>\n
                                    <input type='text' name='productName' class='form-control' id='productNameInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='descriptionInput'>Description</label>\n
                                    <textarea name='description' class='form-control' id='descriptionInput' cols='60' rows='3'> </textarea>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='priceInput'>Price</label>\n
                                    <input type='text' name='price' class='form-control' id='priceInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='quantityInput'>Quantity</label>\n
                                    <input type='text' name='quantity' class='form-control' id='quantityInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='salePriceInput'>Sale Price</label>\n
                                    <input type='text' name='salePrice' class='form-control' id='salePriceInput' value='0.00'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='imageInput'>Image</label>\n
                                    <input type='file' name='image' class='form-control' id='imageInput'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <button type='submit' name='AddItem' value='AddItem' class='btn btn-outline-dark btn-block'>Add New Item</button>\n
                                    <button type='reset' class='btn btn-outline-dark btn-block'>Reset Item</button>\n
                                </div>\n
                            </form>\n        
                        </div>";

            return $bigString;
        }

        /**
         * Function that will return the html for the
         * edit/update item form.
         */
        function displayEditItemForm($productID) {
            //Instantiate the DB class
            $this->db = new DB();

            //Query the database
            $data = $this->db->getAllProducts();
            
            $bigString = "<div class='container-fluid'>\n
                            <h1>Admin</h1>
                            <form class='admin' action='admin.php' method='post' enctype='multipart/form-data'>\n
                                <h3>Edit Item</h3>\n
                                <div class='form-group'>\n
                                    <label for='itemInput'>Choose an item to edit:</label>\n
                                    <select class='form-control' id='itemInput' name='productID'>\n";

            //Loop through all of the products making them options in a select box
            foreach($data as $product) {
                $bigString .= "<option value='{$product->getProductID()}'>{$product->getProductName()} - {$product->getDescription()}</option>\n";
            }
             
            $bigString .= " </select>\n</div>\n
                        <div class='form-group'>\n
                            <button type='submit' name='EditItem' value='EditItem' class='btn btn-outline-dark btn-block'>Edit Item</button>\n
                        </div>\n";

            //If the product id is not null, then there is an item to edit
            if($productID != null) {
                //Query the database
                $data = $this->db->getSpecificProduct($productID);
                
                //Check to see if the count is 1
                //It should be since we only asked for a single item
                if(count($data) == 1) {
                    //Get the first index and assign it to product
                    $product = $data[0];
                }
                
                //Make the html to display the item that the user wants to edit
                $bigString .= "<h3 id='updateHeader'>Update Item</h3>\n
                                <div class='form-group'>\n
                                    <label for='productNameInput'>Product Name</label>\n
                                    <input type='text' name='productName' class='form-control' id='productNameInput' value='{$product->getProductName()}'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='descriptionInput'>Description</label>\n
                                    <textarea type='text' name='description' class='form-control' id='descriptionInput' cols='60' row='3'>{$product->getDescription()}</textarea>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='priceInput'>Price</label>\n
                                    <input type='text' name='price' class='form-control' id='priceInput' value='{$product->getPrice()}'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='quantityInput'>Quantity</label>\n
                                    <input type='text' name='quantity' class='form-control' id='quantityInput' value='{$product->getQuantity()}'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='salePriceInput'>Sale Price</label>\n
                                    <input type='text' name='salePrice' class='form-control' id='salePriceInput' value='{$product->getSalePrice()}'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <label for='imageInput'>Image</label>\n
                                    <input type='file' name='image' class='form-control' id='imageInput' value='{$product->getImageName()}'>\n
                                </div>\n
                                <div class='form-group'>\n
                                    <button type='submit' name='UpdateItem' value='{$product->getProductID()}' class='btn btn-outline-dark btn-block'>Update Item</button>\n
                                    <button type='reset' class='btn btn-outline-dark btn-block'>Reset Item</button>\n
                                </div>\n
                            </form>\n";
            } else{
                $bigString .= "</form>\n";
            }

            //Close the DB
            $this->db = null;

            return $bigString;
        }

        /**
         * Function that will create the pagination
         * for the catalog.
         */
        function pagination($pageStart) {
            //Instantiate the DB class
            $this->db = new DB();
            //Query the database
            $data = $this->db->getAllCatalogProducts(0.00, $pageStart);
            //Close the DB
            $this->db = null;

            //Set the total number of pages we need
            //The amount of catalog items divided by 5 items per page
            $totalPages = ceil(count($data) / 5);

            $bigString = "<nav aria-label='Page navigation'>
            <ul class='pagination justify-content-center'>";

            //Set the link for each page
            for($i = 1; $i <= $totalPages; $i++) {
                $bigString .= "<li class='page-item'><a class='page-link' href='index.php?page=$i'>$i</a></li>";
            }

            return $bigString .= "</ul></nav></div>";
        }
    }