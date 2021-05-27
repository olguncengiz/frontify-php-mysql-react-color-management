<?php
include "functions.php";

$host = "localhost:3306";
$dbname = "colordb";
$username = "root";
$password = "pass";

try {
  // Connection string
  $con = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // Get request method
  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

  if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }

  // Default response values
  $_code = 200;
  $jsonArray = array();
  $jsonArray["error"] = FALSE;
  $jsonArray["data"] = null;

  switch ($method) {
    case 'GET':
      // List colors endpoint
      $query = $con->query("SELECT * FROM colors");
      $result = $query->fetchAll(PDO::FETCH_ASSOC);
      $jsonArray["data"] = $result;
      break;
    case 'POST':
      // Create color endpoint
      $query = $con->prepare("INSERT INTO colors (name, hexcode) VALUES (?, ?)");
      $name = $_POST["name"];
      $hexcode = $_POST["hexcode"];
      $qParams = array($name, $hexcode);
      $result = $query->execute($qParams);
      if (!$result) {
        $_code = "400";
        $jsonArray["error"] = TRUE;
        break;
      }
      $_code = "201";
      break;
   case 'DELETE':
      // Remove color endpoint
      $query = $con->prepare("DELETE FROM colors WHERE name = ?");
      $name = $_GET['name'];
      $qParams = array($name);
      $result = $query->execute($qParams);
      if (!$result) {
        $_code = "400";
        $jsonArray["error"] = TRUE;
        break;
      }
      $_code = "202";
      break;
   default:
      // Method not allowed
      $_code = "405";
      break;
  }
} catch (Exception $e) {
  // Return exception message
  $_code = "400";
  $jsonArray["error"] = TRUE;
  $jsonArray["data"] = $e->getMessage();
}

// Set header and add payload to response
SetHeader($_code);
$jsonArray["status"] = HttpStatus($_code);
echo json_encode($jsonArray);

$con = null;

?>
