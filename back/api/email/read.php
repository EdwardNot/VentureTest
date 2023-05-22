<?php

    //Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../models/Email.php';
    include_once '../../config/Database.php';

    $database = new Database();
    $db = $database->connect();

    $emails = new Email($db);

    $result = $emails->read();

    if (!$result){
        echo 'THERE IS NO RESULT';
        return;
    }

    $num = $result->rowCount();

    if ($num > 0){
        $emails_array = array();
        $emails_array['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $email_item = $full_email;

            // $email_item = array(
            //     // 'id' => $id,
            //     // 'full_email' => $full_email,
            //     // 'email_domain' => $email_domain,
            //     // 'email_body' => $email_body,
            // );

            array_push($emails_array['data'], $email_item);
        }

        echo json_encode($emails_array);
    } else {
        echo json_encode(
            array('message' => 'NO EMAILS FOUND')
        );
    }