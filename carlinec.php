<?php
ob_start();
$my_url = 'http://carlinec.ru/prodaja?start=0&limit=500';
$html = file_get_contents($my_url);
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

//$my_xpath_query = '//a[contains(text(), "/prodaja/product/view/8/(\d+)"';
$my_xpath_query = "//div[@class='image_block']/a/@href";
$result_rows = $xpath->query($my_xpath_query);

$now=date("Y-m-d H:i:s");


echo '<?xml version="1.0" encoding="utf-8"?>
<auto-catalog>
<creation-date>'.$now.' GMT+4</creation-date>
<host>http://carlinec.ru/</host>
<offers>';

foreach($result_rows as $result) {
	$html = file_get_contents( 'http://carlinec.ru'.$result->textContent);
	$dom2 = new DOMDocument();
	@$dom2->loadHTML($html);
	$xpath2 = new DOMXPath($dom2);
	$my_xpath_query2 = "//span[@class='extra_fields_value']";
	$result_rows2 = $xpath2->query($my_xpath_query2);
	
	//Get model
	$xpath3 = new DOMXPath($dom2);
	$my_xpath_query3 = "//div[@class='produkts_title']";
	$result_rows3 = $xpath2->query($my_xpath_query3);
	
	$title=$result_rows3->item(0)->textContent;
	$title = str_replace( $result_rows2->item(0)->textContent, "", $title);
	$title = str_replace( ",", "", $title);
	$model = str_replace( $result_rows2->item(1)->textContent, "", $title);
	//$model ok
	$my_xpath_query10 = "//div[@class='jshop_prod_description']/p";
	$result_rows10 = $xpath2->query($my_xpath_query10);
		//echo $result2->textContent;
	echo '<offer type="private">
	  <url>http://carlinec.ru'.$result->textContent.'</url>
	  <date>'.$now.' GMT+4</date>
	  <mark>'.$result_rows2->item(0)->textContent.'</mark>
	  <model>'.$model.'</model>
	  <year>'.$result_rows2->item(1)->textContent.'</year>
		<run-metric>км</run-metric>
		<run>'.$result_rows2->item(2)->textContent.'</run>
		<additional-info>
			  '.$result_rows2->item(11)->textContent.' '.$result_rows10->item(0)->textContent.'
		</additional-info>
		<state>'.$result_rows2->item(10)->textContent.'</state>
		<color>'.$result_rows2->item(3)->textContent.'</color>
		<body-type>'.$result_rows2->item(7)->textContent.'</body-type>
		<engine-type>'.$result_rows2->item(4)->textContent.'</engine-type>
		<gear-type>'.$result_rows2->item(5)->textContent.'</gear-type>
		<displacement>'.$result_rows2->item(8)->textContent.'</displacement>
		<transmission>'.$result_rows2->item(9)->textContent.'</transmission>
		<steering-wheel>левый</steering-wheel>';

	$my_xpath_query5 = "//div[@class='image_middle']//img[starts-with(@id, 'main')]/@src";
	$result_rows5 = $xpath2->query($my_xpath_query5);
	
	foreach($result_rows5 as $result5) {
		//var_dump($result2);
		
		echo '<image>'.str_replace("IMG", "full_IMG", $result5->textContent).'</image>';
	}
	$my_xpath_query9 = "//div[@id='block_price']";
	$result_rows9 = $xpath2->query($my_xpath_query9);
	echo '<price>'.$result_rows9->item(0)->textContent.'</price>
    <currency-type>руб</currency-type>
  <seller>CarLineC</seller>
    <seller-phone>+78452714714</seller-phone>
  <seller-city>Саратов</seller-city>
</offer>';

}
echo '</offers>
</auto-catalog>';
	$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('/var/www/parsers/carlinec.xml', $htmlStr);
?>