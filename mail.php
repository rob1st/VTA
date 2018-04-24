<?php
// Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;

// Instantiate the client.
$mgClient = new Mailgun('key-8fb4a39d8d60fc5a39f4889673e8c900');
$domain = "sandbox6520986c6f6b42cca015e9181bfe0a53.mailgun.org";

// Make the call to the client.
$result = $mgClient->sendMessage("$domain",
          array('from'    => 'Mailgun Sandbox <postmaster@sandbox6520986c6f6b42cca015e9181bfe0a53.mailgun.org>',
                'to'      => 'Colin King-Bailey <colin.king-bailey@vta.org>',
                'subject' => 'Hello Colin King-Bailey',
                'text'    => 'Congratulations Colin King-Bailey, you just sent an email with Mailgun!  You are truly awesome! '));

// You can see a record of this email in your logs: https://app.mailgun.com/app/logs .

// You can send up to 300 emails/day from this sandbox server.
// Next, you should add your own domain so you can send 10,000 emails/month for free.
/*
curl -s --user 'api:key-8fb4a39d8d60fc5a39f4889673e8c900' \
    https://api.mailgun.net/v3/sandbox6520986c6f6b42cca015e9181bfe0a53.mailgun.org/messages \
        -F from='Mailgun Sandbox <postmaster@sandbox6520986c6f6b42cca015e9181bfe0a53.mailgun.org>' \
        -F to='Colin King-Bailey <ckingbailey@gmail.com>' \
        -F subject='Hello Colin King-Bailey' \
        -F text='Congratulations Colin King-Bailey, you just sent an email with Mailgun!  You are truly awesome!'
*/
?>