<?php
include "functions.php";

$host = "localhost:3306";
$dbname = "colordb";
$username = "root";
$password = "pass";

try {
  $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

  if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $_code = 200;
  $jsonArray = array();
  $jsonArray["error"] = FALSE;

  switch ($method) {
    case 'GET':
      $query = $con->query("SELECT * FROM colors");
      break;
    case 'POST':
      $query = $con->prepare("INSERT INTO colors (name, hexcode) VALUES (?, ?)");
      $name = $_POST["name"];
      $hexcode = $_POST["hexcode"];
      $qParams = array($name, $hexcode);
      break;
   case 'DELETE':
      $query = $con->prepare("DELETE FROM colors WHERE name = ?");
      $name = $_GET['name'];
      $qParams = array($name);
      break;
   default:
      $_code = "405";
      SetHeader($_code);
      $jsonArray["status"] = HttpStatus($_code);
      $jsonArray["data"] = null;
      echo json_encode($jsonArray);
      die();
  }

  // run SQL statement
  if ($method == 'GET') {
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
  } elseif ($method == 'POST') {
    $result = $query->execute($qParams);
  } elseif ($method == 'DELETE') {
    $result = $query->execute($qParams);
  }

  // die if SQL statement failed
  if (!$result) {
    http_response_code(404);
    echo json_encode(array('Error', 'Operation Failed'));
  }

  if ($method == 'GET') {
    SetHeader($_code);
    $jsonArray["status"] = HttpStatus($_code);
    $jsonArray["data"] = $result;
    echo json_encode($jsonArray);
  } elseif ($method == 'POST') {
    $_code = "201";
    SetHeader($_code);
    $jsonArray["status"] = HttpStatus($_code);
    $jsonArray["data"] = null;
    echo json_encode($jsonArray);
  } else {
    $_code = "202";
    SetHeader($_code);
    $jsonArray["status"] = HttpStatus($_code);
    $jsonArray["data"] = null;
    echo json_encode($jsonArray);
  }
} catch (PDOException $e) {
  $_code = "400";
  SetHeader($_code);
  $jsonArray["status"] = "Error";
  $jsonArray["error"] = TRUE;
  $jsonArray["data"] = $e->getMessage();
  echo json_encode($jsonArray);
  die();
} catch (Exception $e) {
  $_code = "400";
  SetHeader($_code);
  $jsonArray["status"] = "Error";
  $jsonArray["error"] = TRUE;
  $jsonArray["data"] = $e->getMessage();
  echo json_encode($jsonArray);
  die();
}

$con = null;

?>
