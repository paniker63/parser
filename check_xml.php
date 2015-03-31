<?php ob_start();


echo 'check it';
$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('check.xml', $htmlStr); 
?>
