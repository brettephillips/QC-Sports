<?php
    /**
     * Class that represents a single product and will
     * be used for object mapping purposes.
     */
    class Product {
        //Instance variables
        private $ProductID;
        private $ProductName;
        private $Description;
        private $Price;
        private $Quantity;
        private $ImageName;
        private $SalePrice;

        /**
         * Function that will get the product name
         * of the product.
         */
        public function getProductID() {
            return $this->ProductID;
        }

        /**
         * Function that will get the product name
         * of the product.
         */
        public function getProductName() {
            return $this->ProductName;
        }

        /**
         * Function that will get the description
         * of the product.
         */
        public function getDescription() {
            return $this->Description;
        }

        /**
         * Function that will get the price
         * of the product.
         */
        public function getPrice() {
            return $this->Price;
        }

        /**
         * Function that will get the quantity
         * of the product.
         */
        public function getQuantity() {
            return $this->Quantity;
        }

        /**
         * Function that will get the image name
         * of the product.
         */
        public function getImageName() {
            return $this->ImageName;
        }

        /**
         * Function that will get the sale price
         * of the product.
         */
        public function getSalePrice() {
            return $this->SalePrice;
        }
    }