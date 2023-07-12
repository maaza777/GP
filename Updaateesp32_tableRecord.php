<?php
  require 'database.php';
  
  // Condition to check that POST value is not empty.
  if (!empty($_POST)) {
    //keep track POST values
    $id = $_POST['id'];
    $BPM = $_POST['BPM'];
    $Spo = $_POST['Spo'];
    $BodyTemperature = $_POST['BodyTemperature'];
    $temperature = $_POST['temperature'];
    $humidity = $_POST['humidity'];
    $status_ = $_POST['status_'];

    
    
    // Get the time and date.
    date_default_timezone_set("Africa/Cairo"); 
    $tm = date("H:i:s");
    $dt = date("Y-m-d");
   
    
    // Updating the data in the table.
    $pdo = Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // This table is used to store sensors data updated by ESP32. 
    // This table is operated with the "UPDATE" command, so this table will only contain one row.

    $sql = "UPDATE esp32_table SET BPM = ?, Spo = ?, BodyTemperature = ?, temperature = ?, humidity = ?, status_ = ?, time = ?, date = ? WHERE id = ?";
    $q = $pdo->prepare($sql);
    $q->execute(array($BPM,$Spo,37,$temperature,$humidity,$status_,$tm,$dt,$id));
    Database::disconnect();
    
    //Entering data into a table.
    $id_key;
    $board = $_POST['id'];
    $found_empty = false;
    
    $pdo = Database::connect();
    
    //Process to check if "id" is already in use.
    while ($found_empty == false) {
      $id_key = generate_string_id(10);
      // This table is used to store and record DHT11 sensor data updated by ESP32. 
      // This table is operated with the "INSERT" command, so this table will contain many rows.
      // Before saving and recording data in this table, the "id" will be checked first, to ensure that the "id" that has been created has not been used in the table.
      $sql = 'SELECT * FROM esp32_table_record WHERE id="' . $id_key . '"';
      $q = $pdo->prepare($sql);
      $q->execute();
      
      if (!$data = $q->fetch()) {
        $found_empty = true;
      }
    }
    
    // The process of entering data into a table.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // This table is used to store and record DHT11 sensor data updated by ESP32. 
    // This table is operated with the "INSERT" command, so this table will contain many rows.
		$sql = "INSERT INTO esp32_table_record (id,board, BPM, Spo, BodyTemperature, temperature, humidity, status_,time,date) values(?,?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($id_key,$board,$BPM,$Spo,$BodyTemperature,$temperature,$humidity,$status_,$tm,$dt));

    
    Database::disconnect();
  }

  
  // Function to create "id" based on numbers and characters.
  function generate_string_id($strength = 16) {
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $input_length = strlen($permitted_chars);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
      $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
      $random_string .= $random_character;
    }
    return $random_string;
  }
  
?>