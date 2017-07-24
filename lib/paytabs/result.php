<?php
require_once 'paytabs.php';

$pt = new paytabs("merchantemail@merchantwebsite.com", "FQ3hbgKHZHRUct0TDvQn7auo2VjJABBfrExCUhjUZbPbFQ4ilVFs59R85bRGMtVd8sQ3K");
$result = $pt->verify_payment($_POST['payment_reference']);
echo "<center><h1>" . $result->result . "</h1></center>";

?>