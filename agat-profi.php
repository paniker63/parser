<?php

ob_start();

$now=date("Y-m-d H:i:s");


echo '<?xml version="1.0" encoding="utf-8"?>
<auto-catalog>
<creation-date>'.$now.' GMT+4</creation-date>
<host>http://agat-profi.ru/</host>
<offers>';
$dom = new DOMDocument();

for ($i = 1; $i <= 8; $i++) {

	$my_url = 'http://agat-profi.ru/ajax/ajax.php?ajax=Y&act=catalog_list&PAGEN_1='.$i.'&arrFilter[PROPERTY_REGION][]=50';

	$html = file_get_contents($my_url);
	
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);

	$my_xpath_query = "//div[@class='headline']/a/@href";
	$result_rows = $xpath->query($my_xpath_query);

		foreach($result_rows as $result) {
			
			$options = array(
			'http'=>array(
			'method'=>"GET",
			'header'=>"Accept-language: en\r\n" .
					  "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
					  "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
			)
			);

			$context = stream_context_create($options);
			
			$html = file_get_contents( 'http://agat-profi.ru'.$result->textContent, false, $context);
			$dom2 = new DOMDocument();
			@$dom2->loadHTML($html);
			
			
			
			$xpath2 = new DOMXPath($dom2);
			$my_xpath_query2 = "//div[@id='tab-description']/ul/li/strong";
			$result_rows2 = $xpath2->query($my_xpath_query2);
			
			$id=str_replace("car.php?id=","", $result->textContent);
			
			//Get model
			$xpath3 = new DOMXPath($dom2);
			$my_xpath_query3 = "//div[@class='column-left']/h1";
			$result_rows3 = $xpath2->query($my_xpath_query3);
			
			$title=$result_rows3->item(0)->textContent;
			$title=trim($title);
			$titlearr = explode(' ',  $title);
			//$titlearr[0] -- marka
			$title = trim(str_replace($titlearr[0], "",  $title));
			//$model ok
			//$my_xpath_query10 = "//**";
			//$result_rows10 = $xpath2->query($my_xpath_query10);
				//echo $result2->textContent;
			
			$xyz=explode(" ", $result_rows2->item(6)->textContent);
			$xyz2=$xyz[0]*1000;	
			
			echo '<offer type="private">
			  <url>http://agat-profi.ru'.$result->textContent.'</url>
			  <date>'.$now.' GMT+4</date>
			  <mark>'.$titlearr[0].'</mark>
			  <model>'.$title.'</model>
			  <year>'.$result_rows2->item(0)->textContent.'</year>
				<run-metric>км</run-metric>
				<run>'.preg_replace("/[^0-9]/", '', $result_rows2->item(9)->textContent).'</run>
				<additional-info>
					 
				</additional-info>
				<state>Хорошее</state>
				<color>'.$result_rows2->item(1)->textContent.'</color>
				<body-type>'.$result_rows2->item(3)->textContent.'</body-type>
				<engine-type>'.$result_rows2->item(4)->textContent.'</engine-type>
				<gear-type>'.$result_rows2->item(2)->textContent.'</gear-type>
				<displacement>'.$xyz2.'</displacement>
				<transmission>'.$result_rows2->item(2)->textContent.'</transmission>
				<steering-wheel>левый</steering-wheel>';

			$my_xpath_query5 = "//input[@type='hidden' and starts-with(@id, 'max')]/@value";
			$result_rows5 = $xpath2->query($my_xpath_query5);
			
			foreach($result_rows5 as $result5) {
				//var_dump($result2);
				
				echo '<image>http://agat-profi.ru'.str_replace("IMG", "full_IMG", $result5->textContent).'</image>';
			}
			$my_xpath_query9 = "//div[@class='price-section']/span[@class='price']";
			$result_rows9 = $xpath2->query($my_xpath_query9);
			echo '<price>'.preg_replace("/[^0-9]/", '', $result_rows9->item(0)->textContent).'</price>
			<currency-type>руб</currency-type>
		  <seller>Agatgroup</seller>
			<seller-phone>+7 (831) 2-999-302</seller-phone>
		  <seller-city>Нижний Новгород</seller-city>
		</offer>';

		}

}
echo '</offers>
</auto-catalog>';
$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents('/var/www/parsers/agat-profi.xml', $htmlStr);
?>