<?php
    class User {
        //Instance variables
        private $UserID;
        private $Username;
        private $Password;
        private $UserType;

        /**
         * Function that will get the product name
         * of the product.
         */
        public function getUserID() {
            return $this->UserID;
        }

        /**
         * Function that will get the product name
         * of the product.
         */
        public function getUsername() {
            return $this->Username;
        }

        /**
         * Function that will get the product name
         * of the product.
         */
        public function getUserType() {
            return $this->UserType;
        }
    }