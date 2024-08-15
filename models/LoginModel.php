<?php

class LoginModel
{
    public $conn;

    public function __construct()
    {
       $this->conn = connect_DB();
    }
    public function getAllTaiKhoan()
    {
       try {
          $sql = "SELECT * FROM `tai_khoan`";
 
          $stmt = $this->conn->prepare($sql);
 
          $stmt->execute();
 
          return $stmt->fetchAll();
 
       } catch (Exception $e) {
          echo 'Lỗi' . $e->getMessage();
       }
    }
    public function getTaiKhoanByID($id)
    {
       try {
          $sql = "SELECT * FROM `tai_khoan` WHERE id = $id";
 
          $stmt = $this->conn->prepare($sql);
 
          $stmt->execute();
 
          return $stmt->fetch();
 
       } catch (Exception $e) {
          echo 'Lỗi' . $e->getMessage();
       }
    }
}

?>