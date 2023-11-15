<?php
session_start();

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

 ?>

 <script>
 function urgAssCheck() {
    if (document.getElementById('Urg').checked) {
        document.getElementById('inputError').style.display = '';
        document.getElementById('warranty').style.display = '';
    } else {
        document.getElementById('inputError').style.display = 'none';
        document.getElementById('warranty').style.display = 'none';
    }
}
</script>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="style2.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Record Form</title>

</head>

<body>
  <h1><?php echo $_SESSION['username']; ?></h1>

  <?php
    if (isset($_SESSION['status'])) {
      echo $_SESSION['status'];
    }
   ?>
   <a href="logout.php">Logout</a>
  <div class="wrapper">
    <form action="addrecord.php" method="post">
      <div class="form">
        <div class="inputfield">
          <div class="radio_select">
            <input type="radio" id="Urg" name="radioRecordCheck" value="Urgent" checked onclick="urgAssCheck()">
            <label for="urgent">Urgent</label><br><br>
            <input type="radio" id="Asb" name="radioRecordCheck" value="Assembly" onclick="urgAssCheck()">
            <label for="assembly">Assembly</label>
          </div>
        </div>
        <div class="inputfield">
          <label>Name</label>
          <input type="text" class="input" name="cusName">
        </div>
        <div class="inputfield">
          <label>Phone Number</label>
          <input type="text" class="input" name="phNo">
        </div>
        <div class="inputfield">
          <label>City</label>
          <input type="text" class="input" name="city">
        </div>
        <div class="inputfield">
          <label>Type</label>
          <div class="custom_select">
            <select name="productType">
              <option value="Mobile">Mobile</option>
              <option value="Laptop">Laptop</option>
              <option value="PC">PC</option>
            </select>
          </div>
        </div>
        <div class="inputfield">
          <label>Model</label>
          <input type="text" class="input" name="pModel">
        </div>
        <div class="inputfield">
          <label>SerialNumber</label>
          <input type="text" class="input" name="pSN">
        </div>
        <div class="inputfield" id="warranty">
          <label>Warranty</label>
          <div class="custom_select">
            <select name="warranty">
              <option value="Exp">Expired</option>
              <option value="Within">Within</option>
              <option value="Ext">External</option>
            </select>
          </div>
        </div>
        <div class="inputfield" id="inputError">
          <label>Error</label>
          <input type="text" class="input" name="cusError">
        </div>
        <div class="inputfield">
          <label>Solution</label>
          <input type="text" class="input" name="solution">
        </div>
        <div class="inputfield">
          <input type="submit" value="Add" class="btn">
        </div>
      </div>



    </form>

  </div>




</body>

</html>
<?php
}else{
     header("Location: index.php");
     exit();
}
 ?>
