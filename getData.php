<?php
  include 'database.php';
  
  // Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    // keep track post values
    $id = $_POST['id'];
    
    $myObj = (object)array();
    
    //........................................ 
    $pdo = Database::connect();
    // This table is used to store DHT11 sensor data updated by ESP32. 
    // To store data, this table is operated with the "UPDATE" command, so this table contains only one row.
    $sql = 'SELECT * FROM esp32_table WHERE id="' . $id . '"';
    foreach ($pdo->query($sql) as $row) {
      $date = date_create($row['date']);
      $dateFormat = date_format($date,"d-m-Y");
      $myObj->id = $row['id'];
      $myObj->BodyTemperature = $row['BodyTemperature'];
      $myObj->BPM = $row['BPM'];
      $myObj->Spo = $row['Spo'];
      $myObj->humidity = $row['humidity'];
      $myObj->temperature = $row['temperature'];
      $myObj->status_ = $row['status_'];
      $myObj->ls_time = $row['time'];
      $myObj->ls_date = $dateFormat;
      
      $myJSON = json_encode($myObj);
      
      echo $myJSON;
    }
    Database::disconnect();

  }
  
?>