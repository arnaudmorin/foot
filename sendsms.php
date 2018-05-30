<?php
require_once ("Emerginov.php");
require_once ("passwords.php");

echo "hello worldié";

if (isset($_GET['numero'])){ 
	$Emerginov = new Emerginov($api_login, $api_password);
	
    $ret = $Emerginov->SendSMS($_GET['numero'],$_GET['text']);
    echo "<pre>";
	print_r($ret);
    echo "</pre>";
}

?>
<form method='get'>
<input type="text" name="numero" value="+33" />
<input type="text" name="text" value="" />
<input type="submit" value="envoi!" />
</form>
