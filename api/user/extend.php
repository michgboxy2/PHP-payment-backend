<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/User.php';

  //Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  //Instantiate User register object
  $user = new User($db);

    //GET email
  // $user->email = isset($_GET['email']) ? $_GET['email'] : die();

  $data = json_decode(file_get_contents("php://input"));

  if(empty($data->email)){
    echo "Email is required";
        die();
  }

  $user->email = $data->email;

  //User post query
  $result = $user->checKiFuserExist();

  $num = $result->rowCount();

  if($num > 0){
    //checks if user exist and update if true.
    $time = strtotime("today");
    $final = date("Y-m-d", strtotime("+1 month", $time));
      
    $user->account_status = 1;
    $user->expired_at = $final;

    if($user->update()) {
        echo json_encode(array('message' => 'user account activated'));
      } else {
        echo json_encode(array('message' => 'user account activation fail'));
    };

  } else {
      //create account cos user does not exist
      if(empty($data->firstName) || empty($data->lastName)){
        echo "All fields must be filled";
        die();
    }
  
    //validate email
    if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
      print_r($emailErr);
      die();
    }
  
     //validate name input
     $validateFirstName = $data->firstName;
      if (!preg_match("/^[a-zA-Z ]*$/",$validateFirstName)) {
          $firstNameErr = "Only letters and white space allowed for first name";
          print_r($firstNameErr);
          die();
      }
  
      if (!preg_match("/^[a-zA-Z ]*$/",$data->lastName)) {
          $lastNameErr = "Only letters and white space allowed for last name";
          print_r($lastNameErr);
          die();
      }

      $user->lastName = $data->lastName;
      $user->email = $data->email;
      $user->firstName = $data->firstName;
      
      //create user
      if($user->create()) {
            echo json_encode(array('message' => 'User created'));
      } else {
            echo json_encode(array('message' => 'User not created'));
      }

  }

