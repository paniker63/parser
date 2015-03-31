<?php
ob_start();

$my_url = 'http://auto-nn.com/avto-v-nalichii/';
$html = file_get_contents($my_url);
$dom = new DOMDocument();//div[@id='rowtitle']//table[@class='tabl']/tbody/tr/td
@$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

//$my_xpath_query = '//tbody/tr[1]/td[1], "http://auto-nn.com/avto-v-nalichii/"';
$my_xpath_query = "//strong/a/@href";
$result_rows = $xpath->query($my_xpath_query);

$now=date("Y-m-d H:i:s");


echo '<?xml version="1.0" encoding="utf-8"?>
<auto-catalog>
<creation-date>'.$now.' GMT+4</creation-date>
<host>http://auto-nn.com/</host>
<offers>';

foreach($result_rows as $result) {
	$html = file_get_contents( 'http://auto-nn.com/'.$result->textContent);
	
	$dom2 = new DOMDocument();
	@$dom2->loadHTML($html);
	$xpath2 = new DOMXPath($dom2);
	
	$my_xpath_query2 = "//tbody/tr";
	$result_rows2 = $xpath2->query($my_xpath_query2);

	//Get model
	$xpath3 = new DOMXPath($dom2);
	$my_xpath_query3 = "//hgroup[@class='span4']/h1";
	$result_rows3 = $xpath2->query($my_xpath_query3);

	$title=$result_rows3->item(0)->textContent;
	$title=trim($title);
	$titlearr = explode(' ',  $title);
	//$titlearr[0] -- marka
	$title = trim(str_replace($titlearr[0], "",  $title));

	//$model ok
	$my_xpath_query10 = "//section[@class='span9']/p";
	$result_rows10 = $xpath2->query($my_xpath_query10);
		//echo $result2->textContent;
		$xyz=explode("(", $result_rows2->item(1)->childNodes->item(2)->textContent);
		$xyz2=$xyz[0]*1000;
	echo '<offer type="private">
	  <url>'.$result->textContent.'</url>
	  <date>'.$now.' GMT+4</date>
	  <mark>'.$titlearr[0].'</mark>
	  <model>'.$title.'</model>
	  <year>'.$result_rows2->item(0)->childNodes->item(2)->textContent.'</year>
		<run-metric>км</run-metric>
		<run>'.preg_replace("/[^0-9]/", '', $result_rows2->item(4)->childNodes->item(2)->textContent).'</run>
		<additional-info>
			  '.$result_rows10->item(0)->textContent.'
		</additional-info>
		<state>Хорошее</state>
		<gear-type>'.($result_rows2->item(3)->childNodes->item(2)->textContent).'</gear-type>
		<displacement>'.$xyz2.'</displacement>
		<transmission>'.($result_rows2->item(2)->childNodes->item(2)->textContent).'</transmission>
		<steering-wheel>левый</steering-wheel>';

	$my_xpath_query5 = "//a[@class='fancybox']/@href";
	$result_rows5 = $xpath2->query($my_xpath_query5);
	
	foreach($result_rows5 as $result5) {
		//var_dump($result2);
		
		echo '<image>'.($result5->textContent).'</image>';
	}
	$my_xpath_query9 = "//div[@class='price-block']/div[@class='price']";
	$result_rows9 = $xpath2->query($my_xpath_query9);
	$a=preg_replace("/[^0-9]/", '', $result_rows9->item(0)->textContent);
	if (strlen($a)>7){
		$a = substr($a, strlen($a)/2);
	}

	echo '<price>'.$a.'</price>
    <currency-type>руб</currency-type>
  <seller>Auto-NN</seller>
    <seller-phone>+7-961-631-5603</seller-phone>
  <seller-city>Нижний Новгород</seller-city>
</offer>';

}
echo '</offers>
</auto-catalog>';
$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('/var/www/parsers/auto-nn.xml', $htmlStr);
?>