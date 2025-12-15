<?php
// Email server configuration
//define basic email settings for the project
define('MAIL_FROM_ADDRESS', 'reply@example.com');
define('MAIL_FROM_NAME', 'agro_market');
//simple function to send plain text emails
function send_email($to, $subject, $message)
{
    $from = MAIL_FROM_NAME.'<'.MAIL_FROM_ADDRESS.'>';
    $headers = 'From: '.$from. "\r\n".
    'Reply-To: '.MAIL_FROM_ADDRESS. "\r\n" .
    'X-Mailer:PHP/'.phpversion();

    return mail($to, $subject, $message, $headers);
}    

?>
