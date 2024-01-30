<?php
    require 'Mysqladapter.php';
    require 'confg.php';

    class User extends Mysqladapter {
        private $_table = 'users';

        public function __construct() {
            global $config;
            parent::__construct($config);
        }

        public function getUsers() {
            $this->select($this->_table);
            return $this->fetchAll();
        }

        public function getUser($user_id) {
            $this->select($this->_table, 'id = ' . $user_id);
            return $this->fetch();
        }
        
        public function addUser($user_data) {
            return $this->insert($this->_table, $user_data);
        }

        public function updateUser($user_data, $user_id) {
            return $this->update($this->_table, $user_data, 'id = ' . $user_id);
        }

        public function deleteUser($user_id) {
            return $this->delete($this->_table, 'id = ' . $user_id);
        }

        public function searchUsers($keyword) {
            $this->select($this->_table, "name LIKE '%$keyword%' OR email LIKE '%$keyword%'");
            return $this->fetchAll();
        }
    }
?>