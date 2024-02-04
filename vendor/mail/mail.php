<?php 



use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';


function sendMail($usermail, $name, $password, $no=null){

    $mail = new PHPMailer(true);


    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'demo.name7613@gmail.com';              //SMTP username
    $mail->Password   = 'yyibryxyodjfsqyb';                     //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;  

    $mail->setFrom('demo.name7613@gmail.com', 'Simple Form');
    $mail->addAddress($usermail, 'Dear');                  //Add a recipient
    
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = "Hey you have created an account at simple-form";
    $mail->Body    = "dear user your username is '". $name . "' and password is '". $password . "'";


    $mail->send();
}

?>