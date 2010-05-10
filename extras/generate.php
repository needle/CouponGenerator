<?php
header ("Content-type: text/xml");
$client = new SoapClient('http://184.73.176.25/magento/index.php/api/?wsdl', array("trace" => 1));
$session = $client->login('needle', '123456');
try {
	$id = $client->call($session,
			'coupongenerator.clonerule',
			array($_REQUEST['id'],
				'__ONEUSE:'.$_REQUEST['name'],
				$_REQUEST['code'],
				$_REQUEST['expire']));
} catch (Exception $e) {
	echo '<response status="ERROR">' . $e->getMessage() . '</response>';
	return;
}
echo '<response status="OK"></response>';
//$result = $client->call($session, 'coupongenerator.info', $id);
//print_r($result);
// $result = $client->call($session, 'coupongenerator.delete', $id);
$client->endSession($session);
?>
