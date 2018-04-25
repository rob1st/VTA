<?php
/*
install Composer
curl -sS https://getcomposer.org/installer | php

add Mailgun and Guzzle6 as a dependency (see https://github.com/mailgun/mailgun-php for more info)
php composer.phar require mailgun/mailgun-php php-http/guzzle6-adapter php-http/message
*/
// namespace declaration
use Mailgun\Mailgun;

// fn to send mail from default sandbox mailgun domain
function mailer($to, $subject, $content) {
    // include Autoloader (see https://documentation.mailgun.com/en/latest/libraries.html for install instructions)
    require 'vendor/autoload.php';
    require 'config.php';

    // instantiate the client
    $mgClient = new Mailgun($mailgunKey);
    $domain = $mailgunDomain;
    
    // call to mailgun client
    if ($result = $mgClient->sendMessage($domain, array(
        'from' => 'Excited New User <mailgun@sandbox6520986c6f6b42cca015e9181bfe0a53.mailgun.org>',
        'to' => $to,
        'subject' => $subject,
        'text' => $content
    ))) {
        echo '<pre>result from mg->sendMessage:';
        var_dump($result);
        echo '</pre>';
    }
    echo '<pre>mgClient obj:';
    var_dump($mgClient);
    echo "</pre>
    <pre>mgKey: $mailgunKey</pre>
    <pre>mgDomain: $domain</pre>";
}
?>