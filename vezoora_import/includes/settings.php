<?php
// SOAP Username and Password Details
define("SITE_FOLDER","vezoora.com"); // Fill this field if the magento is installed under one folder else leave empty
define("WS_MAGENTO_SOAP_USER", 'vezoora'); // SOAP Username
define("WS_MAGENTO_SOAP_PASS", 'admin@123'); // SOAP Password
define("MAIL_FROM", 'info@oxinfotech.com'); // E Mail ID
define("MAIL_TO", 'balaji@oxinfotech.com'); // E MAIL Id
define("SEND_MAIL", '1');  //0-NOT SENDING, 1-SENDING MAILS
define("MAIL_SIGNATURE", "Thank you,\nSupport Team,"); // Mail Signature

/*Extra attribute as per Client Request for Product Import*/
$attributeCodeClientRequest=array('setting','style','metal_type','size','weight_range'); //Add more attribute, if Client requested add here and in csv.
$customoptions=array("option_title","option_values","option_values_price","is_option_required","store_id");

?>
