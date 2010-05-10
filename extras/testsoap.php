<?php

$client = new SoapClient('http://184.73.176.25/magento/index.php/api/?wsdl');
// If soap isn't default use this link instead
// http://youmagentohost/api/soap/?wsdl
 
// If somestuff requires api authentification,
// we should get session token
$session = $client->login('needle', '123456');
 
$result = $client->call($session, 'salesrule.list');
 
// If you don't need the session anymore
$client->endSession($session);

print $result;

?>