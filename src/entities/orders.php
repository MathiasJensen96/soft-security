<?php
    class orders {
        public $id;
        public $status;
        public $date;
        public $user_email;

        function __construct($id, $status, $date, $user_email) {
                $this->id = $id;
                $this->status = $status;
                $this->date = $date;
                $this->user_email = $user_email;
            }

        /**
         * Get the value of id
         */ 
        public function getId()
        {
                return $this->id;
        }

        /**
         * Set the value of id
         *
         * @return  self
         */ 
        public function setId($id)
        {
                $this->id = $id;

                return $this;
        }

        /**
         * Get the value of status
         */ 
        public function getStatus()
        {
                return $this->status;
        }

        /**
         * Set the value of status
         *
         * @return  self
         */ 
        public function setStatus($status)
        {
                $this->status = $status;

                return $this;
        }

        /**
         * Get the value of date
         */ 
        public function getDate()
        {
                return $this->date;
        }

        /**
         * Set the value of date
         *
         * @return  self
         */ 
        public function setDate($date)
        {
                $this->date = $date;

                return $this;
        }

        /**
         * Get the value of user_email
         */ 
        public function getUser_email()
        {
                return $this->user_email;
        }

        /**
         * Set the value of user_email
         *
         * @return  self
         */ 
        public function setUser_email($user_email)
        {
                $this->user_email = $user_email;

                return $this;
        }
    }
?>