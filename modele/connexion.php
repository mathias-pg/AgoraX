<?php
class Connexion {
  static private $hostname = 'localhost';
  static private $database = 'saes3-dteixei';
  static private $login = 'saes3-dteixei';
  static private $password = '5QrIJIIEG1bgVTmf';

  static private $tabUTF8 = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4");

  static private $pdo;

  static public function pdo() {return self::$pdo;}

  static public function connect() {
    $h = self::$hostname; $d = self::$database; $l = self::$login;
    $p = self::$password; $t = self::$tabUTF8;
    try{
      self::$pdo = new PDO("mysql:host=$h;dbname=$d",$l,$p,$t);
      self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "erreur de connexion : ".$e->getMessage()."<br>";
    }
  }
}
?>