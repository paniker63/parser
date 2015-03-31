<?php
ob_start();
$now1=date("d-m-Y");
$content = file_get_contents("http://crwl.ru/api/rest/v1/get_ads/?api_key=fa638b23fef16a26f2584fbd5d967da3&source=mail.ru&min_date=".$now1."&max_date=".$now1."&region=1");
$me = json_decode($content, true);


$now=date("Y-m-d H:i:s");

echo '<?xml version="1.0" encoding="utf-8"?>
<auto-catalog>
<creation-date>'.$now.' GMT+4</creation-date>
<host>mail.ru</host>
<offers>';

foreach($me as $in){

	echo '<offer type="private">
  <url>'.$in["url"].'</url>
  <date>'.$in["dt"].'</date>
  <mark>'.$in["marka"].'</mark>
  <model>'.$in["model"].'</model>
  <year>'.$in["year"].'</year>
    <run-metric>км</run-metric>
    <run>'.$in["run"].'</run>
    <additional-info>
          '.$in["info"].'  Объявление найдено на http://cars.mail.ru/
    </additional-info>
    <state>хорошее</state>
    <color>'.$in["color"].'</color>
    <body-type>'.$in["body"].'</body-type>
    './*<engine-type>Бензин карбюратор</engine-type>*/'
    <gear-type>'.$in["drive"].'</gear-type>
    <displacement>'.$in["enginevol"].'</displacement>
    <transmission>'.$in["transmission"].'</transmission>
    <steering-wheel>'.$in["wheel"].'</steering-wheel>';
	$photos=explode(',', $in["photo"], -1);
    foreach($photos as $inn){
		echo '<image>'.$inn.'</image>';
	}
	echo '<vin>'.$in["vin"].'</vin>
  <price>'.$in["price"].'</price>
    <currency-type>руб</currency-type>
  <seller>'.$in["fio"].'</seller>
    <seller-phone>'.$in["phone"].'</seller-phone>
  <seller-city>Москва</seller-city>
</offer>';
}
echo '</offers>
</auto-catalog>';
$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('/var/www/parsers/crwlr-mailru.xml', $htmlStr);
?>