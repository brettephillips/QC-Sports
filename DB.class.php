<?php
    //include the object classes to map too
    include "Product.class.php";
    include "User.class.php";

    /**
     * Class that will handle all of the database logic.
     */
    class DB {
        //Instance variables
        private $database;

        /**
         * Default constructor that will initilize the PDO
         * object to connect to the database.
         */
        function __construct() {
            try {
                //Enter your DB info in the PDO params
                $this->database = new PDO("mysql:host=your_hostname;dbname=your_db;", "username", "pasword");
                $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will retrieve all of the products
         * utilizing object mapping from the Product class.
         */
        function getAllProducts() {
            try {
                $data = Array();
                $queryString = "SELECT ProductID, ProductName, Description, Price, Quantity, ImageName, SalePrice FROM products";

                //Prepare and execute the query string
                $stmt = $this->database->prepare($queryString);
                $stmt->execute();
                //Set the fetch mode, so it knows to use the Product class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
                //Loop through the fetched items and append them to the array
                while($product = $stmt->fetch()) {
                    $data[] = $product;
                }

                return $data;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will retrieve all of the products on sale by
         * utilizing object mapping from the Product class.
         */
        function getAllSaleProducts($salePrice) {
            try {
                $data = Array();
                $queryString = "SELECT ProductID, ProductName, Description, Price, Quantity, ImageName, SalePrice FROM products WHERE SalePrice > :salePrice";

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":salePrice"=>$salePrice));
                //Set the fetch mode, so it knows to use the Product class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
                //Loop through the fetched items and append them to the array
                while($product = $stmt->fetch()) {
                    $data[] = $product;
                }

                return $data;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will retrieve all of the products not on sale by
         * utilizing object mapping from the Product class.
         */
        function getAllCatalogProducts($salePrice, $pageStart) {
            try {
                $numOfItems = 5;
                $data = Array();

                //If pageStart is null, then do not inculde it in the query
                if(!isset($pageStart)) {
                    $queryString = "SELECT ProductID, ProductName, Description, Price, Quantity, ImageName, SalePrice FROM products WHERE SalePrice = :salePrice";
                } else {
                    $queryString = "SELECT ProductID, ProductName, Description, Price, Quantity, ImageName, SalePrice FROM products WHERE SalePrice = :salePrice LIMIT :numOfItems OFFSET :pageStart";
                }

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);

                //Make sure to add the correct params to the respective query
                if(!isset($pageStart)) {
                    $stmt->execute(array(":salePrice"=>$salePrice));   
                } else {
                    $stmt->bindParam(":salePrice", $salePrice, PDO::PARAM_STR);
                    $stmt->bindParam(":numOfItems", $numOfItems, PDO::PARAM_INT);
                    $stmt->bindParam(":pageStart", $pageStart, PDO::PARAM_INT);
                    $stmt->execute();
                }
                //Set the fetch mode, so it knows to use the Product class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
                //Loop through the fetched items and append them to the array
                while($product = $stmt->fetch()) {
                    $data[] = $product;
                }

                return $data;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will retrieve one product utilizing object mapping
         * from the Product class.
         */
        function getSpecificProduct($productID) {
            try {
                $data = Array();
                $queryString = "SELECT ProductID, ProductName, Description, Price, Quantity, ImageName, SalePrice FROM products WHERE ProductID = :productID";

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":productID"=>$productID));
                //Set the fetch mode, so it knows to use the Product class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
                //Loop through the fetched items and append them to the array
                while($product = $stmt->fetch()) {
                    $data[] = $product;
                }

                return $data;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will check to see if the user is in the database
         * by utilizing object mapping from the User class.
         */
        function getUser($username, $password) {
            try {
                $data = Array();
                $queryString = "SELECT UserID, Username, UserType FROM user WHERE Username = :username AND Password = :password";

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":username"=>$username, ":password"=>md5($password)));
                //Set the fetch mode, so it knows to use the User class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
                //Loop through the fetched items and append them to the array
                while($user = $stmt->fetch()) {
                    $data[] = $user;
                }

                return $data;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will insert a single product into
         * the database.
         */
        function addNewProduct($productName, $description, $price, $quantity, $salePrice, $imageName) {
            try {
                $queryString = "INSERT INTO products(ProductName, Description, Price, Quantity, ImageName, SalePrice) VALUES(:productName, :description, :price, :quantity, :imageName, :salePrice)";
                
                //Begin the transaction
                $this->database->beginTransaction();

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":productName"=>$productName, ":description"=>$description, ":price"=>$price, ":quantity"=>$quantity, ":imageName"=>$imageName, ":salePrice"=>$salePrice));
                
                //Get all the sale items to ensure we do not go over 5 items
                $data = $this->getAllSaleProducts(0.00);

                //If we go over 5 items, then rollback and return null
                if(count($data) > 5) {
                    $this->database->rollback();
                    return null;
                }

                //We have not reached our limit, so we can commit the change
                $this->database->commit();
                return $stmt->rowCount();
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will update a product from
         * the database.
         */
        function updateProduct($productName, $description, $price, $quantity, $salePrice, $imageName, $productID) {
            try {
                $queryString = "UPDATE products SET ProductName = :productName, Description = :description, Price = :price, Quantity = :quantity, ImageName = :imageName, SalePrice = :salePrice WHERE ProductID = :productID";
                $queryStringTwo = "UPDATE products SET ProductName = :productName, Description = :description, Price = :price, Quantity = :quantity, SalePrice = :salePrice WHERE ProductID = :productID";
                
                //Begin the transaction
                $this->database->beginTransaction();

                //Prepare, bind, and execute the query string using a parameterized query
                //If our imageName is empty.png, then the user didnt upload a new image
                if($imageName == "empty.png") {
                    //Lets use this script, so we keep the original image
                    $stmt = $this->database->prepare($queryStringTwo);
                    $stmt->execute(array(":productName"=>$productName, ":description"=>$description, ":price"=>$price, ":quantity"=>$quantity, ":salePrice"=>$salePrice, "productID"=>$productID)); 
                } else {
                    //Else, change the image
                    $stmt = $this->database->prepare($queryString);
                    $stmt->execute(array(":productName"=>$productName, ":description"=>$description, ":price"=>$price, ":quantity"=>$quantity, ":imageName"=>$imageName, ":salePrice"=>$salePrice, "productID"=>$productID));
                }
                
                //Get all the sale items to ensure we do not go over 5 items
                $data = $this->getAllSaleProducts(0.00);

                //If we go over 5 items or under 3 items, then rollback and return null
                if(count($data) > 5 || count($data) < 3) {
                    $this->database->rollback();
                    return null;
                }

                //We have not reached our limit, so we can commit the change
                $this->database->commit();
                return $stmt->rowCount();
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will add a product to the
         * user's cart
         */
        function addItemToCart($userID, $productID) {
            try{
                $queryString = "INSERT INTO cart(ProductID_fk, UserID_fk) VALUES(:productID, :userID)";
                $queryStringTwo = "SELECT Quantity FROM products WHERE ProductID = :productID";
                $queryStringThree = "UPDATE products SET Quantity = :quantity WHERE ProductID = :productID";

                //Begin the transaction
                $this->database->beginTransaction();
                
                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":productID"=>$productID, ":userID"=>$userID));
                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryStringTwo);
                $stmt->execute(array(":productID"=>$productID));
                //Fetch all of the data
                $quantity = $stmt->fetchAll();
                //Subtract the quantity amount
                $quantity = $quantity[0]['Quantity'] - 1;

                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryStringThree);
                $stmt->execute(array(":productID"=>$productID, ":quantity"=>$quantity));

                //Commit the data
                $this->database->commit();

                return $this->database->lastInsertId();
            } catch(PDOException $e) {
                //Something went wrong, so lets rollback
                $this->database->rollback();
                return null;
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will get all of the products
         * in the user's cart.
         */
        function getUserCartProducts($userID) {
            try{
                $queryString = "SELECT p.ProductName, p.Description, p.Quantity, p.Price, p.SalePrice FROM products p JOIN cart c ON p.productID = c.ProductID_fk JOIN user u ON c.UserID_fk = u.UserID WHERE u.UserID = :userID";
            
                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":userID"=>$userID));   
                
                //Set the fetch mode, so it knows to use the User class
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Product");
                //Loop through the fetched items and append them to the array
                while($user = $stmt->fetch()) {
                    $data[] = $user;
                }

                //Check to make sure we have data, then return it
                if(isset($data)) {
                    return $data;
                }

                //If there is no data, then we return null
                return null;
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }

        /**
         * Function that will delete the items in the
         * user's cart.
         */
        function deleteUserCartProducts($userID) {
            try{
                $queryString = "DELETE FROM cart WHERE UserID_fk = :userID";
            
                //Prepare, bind, and execute the query string using a parameterized query
                $stmt = $this->database->prepare($queryString);
                $stmt->execute(array(":userID"=>$userID));   
                
                //Return the number of affected rows
                return $stmt->rowCount();
            } catch(PDOException $e) {
                echo $e->getMessage();
                die();
            }
        }
    }