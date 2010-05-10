<?php

$client = new SoapClient('http://184.73.176.25/magento/index.php/api/?wsdl', array("trace" => 1));
// If soap isn't default use this link instead
// http://youmagentohost/api/soap/?wsdl
 
// If somestuff requires api authentification,
// we should get session token
$session = $client->login('needle', '123456');
 
// $id = $client->call($session, 'coupongenerator.clonerule', array('2', '__NDL_RULE', 'NEEDLE7'));
$result = $client->call($session, 'coupongenerator.info', 2);

print_r($result);
// $result = $client->call($session, 'coupongenerator.delete', $id);
// If you don't need the session anymore
$client->endSession($session);


?>
