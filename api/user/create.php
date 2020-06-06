<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/User.php';
  

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate user object
  $user = new User($db);

  //GET raw user data
  $data = json_decode(file_get_contents("php://input"));

  //check if all fields are filled
  if(empty($data->email) || empty($data->firstName) || empty($data->lastName)){
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