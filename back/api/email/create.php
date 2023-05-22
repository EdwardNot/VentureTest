<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../models/Email.php';
    include_once '../../config/Database.php';
    include_once '../send-email.php';

    $database = new Database();
    $db = $database->connect();

    $emails = new Email($db);

    //Get raw data
    $data = json_decode(file_get_contents("php://input"));
    if (!$data){
        echo json_encode(array('error' => 'ERROR! data is empty'));
        return;
    }

    if (!$data->email){
        echo json_encode(array('error' => 'ERROR! email field is empty'));
        return;
    }

    $joke_json = file_get_contents("https://api.chucknorris.io/jokes/random");

    $json_data = json_decode($joke_json, true);
    $joke = $json_data['value'];

    $sender = new Sender();

    $html_code = file_get_contents('../../mail/index.html');

    $html_code = preg_replace("/SOME TEXT SAMPLE/i", $joke, $html_code);

    $sender->send_email($data->email, $html_code);
    echo json_encode(array('message' => 'test mail supposed to be send with joke: ' . $joke));
    // mail($data->email, 'test', 'test message');

    // printf('PRINT DATA FROM POST' . $data->email);

    //TODO need to check if email is already exist in database

    $emails->full_email = $data->email;
    $emails->email_body = preg_replace("/@.*$/i", '', $data->email);
    $emails->email_domain = preg_replace("/^.*@/i", '', $data->email);

    if ($emails->create()){
        echo json_encode(array('message' => 'email successfully created'));
    } else {
        echo json_encode(array('error' => 'error during creation'));
    }

