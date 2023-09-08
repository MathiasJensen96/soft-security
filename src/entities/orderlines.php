<?php
    class orderlines {
        public $productId;
        public $orderId;
        public $quantity;

        function __construct($productId, $orderId, $quantity) {
                $this->productId = $productId;
                $this->orderId = $orderId;
                $this->quantity = $quantity;
            }

        /**
         * Get the value of productId
         */ 
        public function getProductId()
        {
                return $this->productId;
        }

        /**
         * Set the value of productId
         *
         * @return  self
         */ 
        public function setProductId($productId)
        {
                $this->productId = $productId;

                return $this;
        }

        /**
         * Get the value of orderId
         */ 
        public function getOrderId()
        {
                return $this->orderId;
        }

        /**
         * Set the value of orderId
         *
         * @return  self
         */ 
        public function setOrderId($orderId)
        {
                $this->orderId = $orderId;

                return $this;
        }

        /**
         * Get the value of quantity
         */ 
        public function getQuantity()
        {
                return $this->quantity;
        }

        /**
         * Set the value of quantity
         *
         * @return  self
         */ 
        public function setQuantity($quantity)
        {
                $this->quantity = $quantity;

                return $this;
        }
        }
        ?>