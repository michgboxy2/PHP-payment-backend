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

  //User post query
  $result = $user->read();

  //get row count
  $num = $result->rowCount();

  //check if user
  if($num > 0){
      $users_arr = array();
      $users_arr['data'] = array();

      while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);

          $user_list = array(
              'id' => $id,
              'firstName' => $firstName,
              'lastName' => $lastName,
              'email' => $email,
              'expired_at' => $expired_at,
              'account_status' => $account_status             
          );

          //Push to data
          array_push($users_arr['data'], $user_list);
      }
        echo json_encode($users_arr);
  } else {
      //No user
      echo json_encode(array('message' => 'No user found'));
  }