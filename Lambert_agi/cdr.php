#!/usr/bin/php -q
<?php
include '/var/lib/asterisk/agi-bin/phpagi.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
require '/usr/share/php/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/usr/share/php/vendor/phpmailer/phpmailer/src/SMTP.php';
require '/usr/share/php/vendor/phpmailer/phpmailer/src/Exception.php';

//Load Composer's autoloader
require '/usr/share/php/Composer/autoload.php';
//$firstname=$_POST['firstname'];
//$email=$_POST['email'];
//$subject=$_POST['subject'];

$agi=new AGI();

$IDNumber = $argv[1];

$dbhost="localhost";
$dbusername="root";
$dbpass="root@123";
$dbname="LambertBanking";
$datenow=date('d-m-Y H:i:s');
$con = mysqli_connect($dbhost,$dbusername,$dbpass,$dbname);
//Check connection
//if (!$con) {
  //die("Connection failed: " . mysqli_connect_error());
//}
//echo "Connected successfully";

mysqli_select_db($con,$dbname);

$result=mysqli_query($con, "SELECT PersonID, Email FROM Banking WHERE PersonID='$IDNumber'");
if ($result->num_rows > 0) {
 while($row = $result->fetch_assoc()) {
         echo "Email: " . $row["Email"];
         $email= $row["Email"];

}
} else {
    echo "0 results";

}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'tech-admin@roamtech.com';                     //SMTP username
    $mail->Password   = 'tech-admin2022!';                               //SMTP password
    $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
    $mail->Port       = 587;
  //Recipients
    $mail->setFrom('tech-admin@roamtech.com', 'Tester');
    $mail->addAddress($email,'test');     //Add a recipient
   // $mail->addAddress('ellen@example.com');               //Name is optional
   // $mail->addReplyTo('info@example.com', 'Information');
  //  $mail->addCC('cc@example.com');
  //  $mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>

