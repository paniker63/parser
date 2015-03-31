<?php
ob_start();

$my_url = 'http://www.avtoobmen52.ru/search_auto.php';
$html = file_get_contents($my_url);
$dom = new DOMDocument();
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

//$my_xpath_query = "http://www.avtoobmen52.ru/search_auto.php";
$my_xpath_query = "//td[@class='left']/a/@href";
$result_rows = $xpath->query($my_xpath_query);

$now=date("Y-m-d H:i:s");


echo '<?xml version="1.0" encoding="utf-8"?>
<auto-catalog>
<creation-date>'.$now.' GMT+4</creation-date>
<host>http://avtoobmen52.ru/</host>
<offers>';

foreach($result_rows as $result) {
	$html = file_get_contents( 'http://avtoobmen52.ru/'.$result->textContent);
	$dom2 = new DOMDocument();
	@$dom2->loadHTML($html);
	$xpath2 = new DOMXPath($dom2);
	
	$my_xpath_query2 = "//td";
	$result_rows2 = $xpath2->query($my_xpath_query2);
	
	
	$id=str_replace("/car.php?id=","", $result->textContent);
	
	//Get model
	$xpath3 = new DOMXPath($dom2);
	$my_xpath_query3 = "//div[@id='top_list']/h1";
	$result_rows3 = $xpath2->query($my_xpath_query3);
	
	$title=$result_rows3->item(0)->textContent;
	$title=trim($title);
	$title = trim(str_replace("Продажа", "",  $title));
	$titlearr = explode(' с пробегом',   $title);
	
	$titlearr = explode(' ', $titlearr[0]);
	//$titlearr[0] - marka
		
	//$model ok
	
	$my_xpath_query10 = "//div[@id='rowtitle']";
	$result_rows10 = $xpath2->query($my_xpath_query10);
	
		$xyz=explode(" ", $result_rows2->item(2)->textContent);
		$xyz2=$xyz[0]*1000;
		
		$sos=explode("(", $result_rows2->item(0)->textContent);
		$sos = trim($sos[0]);
		
	echo '<offer type="private">
	  <url>http://avtoobmen52.ru'.$result->textContent.'</url>
	  <date>'.$now.' GMT+4</date>
	  <mark>'.$titlearr[0].'</mark>
	  <model>'.$sos.'</model>
	  <year>'.$result_rows2->item(5)->textContent.'</year>
		<run-metric>км</run-metric>
		<run>'.preg_replace("/[^0-9]/", '', $result_rows2->item(6)->textContent).'</run>
		<additional-info>
			 '.$result_rows10->item(0)->textContent.'
		</additional-info>
		<state>Хорошее</state>
		<color>'.$result_rows2->item(1)->textContent.'</color>
		<gear-type>'.($result_rows2->item(7)->textContent).'</gear-type>
		<displacement>'.$xyz2.'</displacement>
		<transmission>'.($result_rows2->item(3)->textContent).'</transmission>
		<steering-wheel>левый</steering-wheel>';

	$my_xpath_query5 = "//a[@rel='lightbox[".$id."]']/@href";
	
	$result_rows5 = $xpath2->query($my_xpath_query5);
	
	foreach($result_rows5 as $result5) {
		//var_dump($result2);
		
		echo '<image>http://www.avtoobmen52.ru'.($result5->textContent).'</image>';
	}
	$my_xpath_query9 = "//td";
	$result_rows9 = $xpath2->query($my_xpath_query9);
	echo '<price>'.preg_replace("/[^0-9]/", '', $result_rows9->item(8)->textContent).'</price>
    <currency-type>руб.</currency-type>
  <seller>Avtoobmen52</seller>
    <seller-phone>8(831) 423-10-30</seller-phone>
  <seller-city>Нижний Новгород</seller-city>
</offer>';

}
echo '</offers>
</auto-catalog>';
	$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('/var/www/parsers/avtoobmen52.xml', $htmlStr);
?>