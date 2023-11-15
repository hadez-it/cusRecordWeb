<?php
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username']))
include "db_conn.php";

  date_default_timezone_set("Asia/Yangon");
  $cusName = $phNo = $city = $productType = $pModel = $pSN = $warranty = $cusError = $solution =  $radioRecordCheck = "";
  $recordDate = date("Y/m/d");
  if (isset($_POST['pModel'])){

    function validate($data){
        $data = trim($data);
  	   $data = stripslashes($data);
  	   $data = htmlspecialchars($data);
  	   return $data;
  	}

  	$tName = $_SESSION['username'];
    if (empty($_POST["cusName"])) {
      $cusName = "";
    } else {
      $cusName = validate($_POST["cusName"]);
    }

    if (empty($_POST["phNo"])) {
      $phNo = "";
    } else {
      $phNo = validate($_POST["phNo"]);
    }

    if (empty($_POST["city"])) {
      $city = "";
    } else {
      $city = validate($_POST["city"]);
    }

    if (empty($_POST["productType"])) {
      $productType = "";

    } else {
      $productType = validate($_POST["productType"]);
    }

    if (empty($_POST["pSN"])) {
      $pSN = "";
    } else {
      $pSN = validate($_POST["pSN"]);
    }

    if (empty($_POST["warranty"])) {
      $warranty = "";
    } else {
      $warranty = validate($_POST["warranty"]);
    }

    if (empty($_POST["cusError"])) {
      $cusError = "";
    } else {
      $cusError = validate($_POST["cusError"]);
    }

    if (empty($_POST["solution"])) {
      $solution = "";
    } else {
      $solution = validate($_POST["solution"]);
    }

    if (empty($_POST["radioRecordCheck"])) {
      $radioRecordCheck = "";
    } else {
      $radioRecordCheck = validate($_POST["radioRecordCheck"]);
    }
    if (empty($_POST["pModel"])) {
      $pModel = "";
        $_SESSION['status'] = "Model name required.";
      header("Location: home.php?");
    } else {
      $pModel = validate($_POST["pModel"]);
      $sql = "INSERT INTO records(Name,Phone,City,ProductType,Warranty,ModelName,Serialnumber,Error,Solution,TechName,recordDate,AsUrg) VALUES('$cusName','$phNo','$city','$productType','$warranty','$pModel','$pSN','$cusError','$solution','$tName','$recordDate','$radioRecordCheck')";

      if ($conn->query($sql) === TRUE) {
        $alertOk ='<script type="text/JavaScript">
            alert("GeeksForGeeks");
            </script>';

        $_SESSION['status'] = "Data inserted successfully";
        header("Location: home.php?");
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
    }

  }else {
    header("Location: home.php?");
  }


?>
