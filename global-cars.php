<?php
ob_start();
	
	$now=date("Y-m-d H:i:s");

	echo '<?xml version="1.0" encoding="utf-8"?>
	<auto-catalog>
	<creation-date>'.$now.' GMT+4</creation-date>
	<host>http://www.global-cars.ru/</host>
	<offers>';

for ($i = 1; $i <= 6; $i++) {
	$my_url = 'http://www.global-cars.ru/index.php/katalog';
	$html = file_get_contents($my_url);
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	
	$my_xpath_query = "//span[@class='catItemImage']/a/@href";
	$result_rows = $xpath->query($my_xpath_query);

	//echo $result_rows->item(0);

	foreach($result_rows as $result) {
		$html = file_get_contents( 'http://www.global-cars.ru'.$result->textContent);
		
		$dom2 = new DOMDocument();
		@$dom2->loadHTML($html);
		$xpath2 = new DOMXPath($dom2);
		
		$id=str_replace("index.php/katalog/item/","", $result->textContent);
		$id=str_replace("http://www.global-cars.ru","", $id);
		
		$my_xpath_query2 = "//span[@class='itemExtraFieldsValue']";
		$result_rows2 = $xpath2->query($my_xpath_query2);

		//Get model
		$xpath3 = new DOMXPath($dom2);
		$my_xpath_query3 = "//h2[@class='itemTitle']";
		$result_rows3 = $xpath2->query($my_xpath_query3);
		
		$title=$result_rows3->item(0)->textContent;
		$title=trim($title);

		$titlearr = explode(' ',  $title);
		//$titlearr[0] -- marka
		$title = trim(str_replace($titlearr[0], "",  $title));
			
		//$model ok
		
		//$my_xpath_query10 = "//h2[@class='itemTitle']";
		//$result_rows10 = $xpath2->query($my_xpath_query10);
		
		
			$xyz=$result_rows2->item(4)->textContent;
			$xyz=trim($xyz);
			$xyz = trim(str_replace(" ", "",  $xyz));
			$xyz=explode(" ", $result_rows2->item(4)->textContent);
			$xyz2=$xyz[0]*1000;
		
		
			$sos=$result_rows2->item(6)->textContent;
			$sos=trim($sos);
			$sos = trim(str_replace("КПП: ", "",  $sos));
			//$titlearr = explode(' с пробегом',   $sos);
		
			$qwe=$result_rows2->item(5)->textContent;
			$qwe=trim($qwe);
			$qwe = trim(str_replace("Мощность двигателя: ", "",  $qwe));
			//$titlearr = explode(' с пробегом',   $sos);
			
			//$sosik=$result_rows2->item(6)->textContent;
			//$sosik=trim($sosik);
			//$sosik = trim(str_replace("", "",  $sosik));
			//$titlearr = explode(' с пробегом',   $sos);
			
		echo '<offer type="private">
			<url>http://www.global-cars.ru'.$result->textContent.'</url>
			<date>'.$now.' GMT+4</date>
			<mark>'.$titlearr[0].'</mark>
			<model>'.$titlearr[1].'</model>
			<year>'.$result_rows2->item(2)->textContent.'</year>
			<run-metric>км</run-metric>
			<run>'.preg_replace("/[^0-9]/", '', $result_rows2->item(3)->textContent).'</run>
			
			<additional-info>
				 '.$qwe, "л.с.".'
			</additional-info>
			<state>Хорошее</state>
			<color>'.$result_rows2->item(7)->textContent.'</color>	
			<displacement>'.$xyz2.'</displacement>
			<transmission>'.$sos.'</transmission>
			<steering-wheel>левый</steering-wheel>';

		$my_xpath_query5 = "//a[@class='fancybox']/@href";
		
		$result_rows5 = $xpath2->query($my_xpath_query5);
		
		foreach($result_rows5 as $result5) {
			//var_dump($result2);
			
			echo '<image>http://www.global-cars.ru'.($result5->textContent).'</image>';
		}
		$my_xpath_query9 = "//div[@class='itemIntroText']/p/span";
		$result_rows9 = $xpath2->query($my_xpath_query9);
		echo '<price>'.preg_replace("/[^0-9]/", '', $result_rows9->item(0)->textContent).'</price>
		<currency-type>руб.</currency-type>
	  <seller>Global-cars</seller>
		<seller-phone>8 (831) 277 79 55</seller-phone>
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
file_put_contents('/var/www/parsers/global-cars.xml', $htmlStr);
?>