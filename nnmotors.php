<?php

//ob_start();
	
	$now=date("Y-m-d H:i:s");

	echo '<?xml version="1.0" encoding="utf-8"?>
	<auto-catalog>
	<creation-date>'.$now.' GMT+4</creation-date>
	<host>http://www.nnmotors.ru/</host>
	<offers>';

for ($i = 1; $i <= 6; $i++) {
	$my_url = 'http://www.nnmotors.ru/main/trade-in/?PAGEN_2='.$i.'';
	$html = file_get_contents($my_url);
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	$xpath = new DOMXPath($dom);
	
	
	$my_xpath_query = "//div[@class='trade_car']/div[@class='trade_name']";
	$result_rows = $xpath->query($my_xpath_query);

	//echo $result_rows->item(0);

	foreach($result_rows as $result) {
		$html = file_get_contents( $result->textContent);
		$dom2 = new DOMDocument();
		@$dom2->loadHTML($html);
		$xpath2 = new DOMXPath($dom2);
		
		//$id=str_replace("/index.php?newsid=","", $result->textContent);
		//$id=str_replace("http://www.nnmotors.ru","", $id);
		
		$my_xpath_query2 = "//div[@id='news-id-".$id."']/span";
		$result_rows2 = $xpath2->query($my_xpath_query2);

		//Get model
		$xpath3 = new DOMXPath($dom2);
		$my_xpath_query3 = "//div[@class='trade_car']/div[@class='trade_name']";
		$result_rows3 = $xpath2->query($my_xpath_query3);
		
		$title=$result_rows3->item(0)->textContent;
		//$title=trim($title);

		$titlearr = explode(' ',  $title);
		//$titlearr[0] -- marka
		$title = trim(str_replace($titlearr[1], "",  $title));
			
		//$model ok
		
		//$my_xpath_query10 = "//div[@id='rowtitle']";
		//$result_rows10 = $xpath2->query($my_xpath_query10);
		
		
			$xyz=$result_rows2->item(6)->textContent;
			$xyz=trim($xyz);
			$xyz = trim(str_replace("Рабочий объем: ", "",  $xyz));
			//$xyz=explode("Мощность двигателя: ", $result_rows2->item(6)->textContent);
			$xyz2=$xyz[0]*1000;
		
		
			$sos=$result_rows2->item(3)->textContent;
			$sos=trim($sos);
			$sos = trim(str_replace("КПП: ", "",  $sos));
			//$titlearr = explode(' с пробегом',   $sos);
		
			//$qwe=$result_rows2->item(7)->textContent;
			//$qwe=trim($qwe);
			//$qwe = trim(str_replace(" ",  $qwe));
			//$titlearr = explode(' с пробегом',   $sos);
			
			$sosik=$result_rows2->item(4)->textContent;
			$sosik=trim($sosik);
			$sosik = trim(str_replace("Привод: ", "",  $sosik));
			//$titlearr = explode(' с пробегом',   $sos);
			
		echo '<offer type="private">
			<url>http://www.nnmotors.ru'.$result->textContent.'</url>
			<date>'.$now.' GMT+4</date>
			<mark>'.$titlearr[0].'</mark>
			<model>'.$titlearr[1].'</model>
			<year>'.preg_replace("/[^0-9]/", '', $result_rows2->item(1)->textContent).'</year>
			<run-metric>км</run-metric>
			<run>'.preg_replace("/[^0-9]/", '', $result_rows2->item(2)->textContent).'</run>
			
			<additional-info>
				
			</additional-info>
			<state>Хорошее</state>
			<color>Серый</color>
			<gear-type>'.$sosik.'</gear-type>
			<displacement>'.$xyz2.'</displacement>
			<transmission>'.$sos.'</transmission>
			<steering-wheel>левый</steering-wheel>';

		$my_xpath_query5 = "//a[@class='lightbox']/@href";
		
		$result_rows5 = $xpath2->query($my_xpath_query5);
		
		foreach($result_rows5 as $result5) {
			//var_dump($result2);
			
			echo '<image>http://www.nnmotors.ru'.($result5->textContent).'</image>';
		}
		$my_xpath_query9 = "//div[@class='price1']";
		$result_rows9 = $xpath2->query($my_xpath_query9);
		echo '<price>'.preg_replace("/[^0-9]/", '', $result_rows9->item(0)->textContent).'</price>
		<currency-type>руб.</currency-type>
	  <seller>NNmotors</seller>
		<seller-phone>42 44 333</seller-phone>
	  <seller-city>Нижний Новгород</seller-city>
	</offer>';

	}
}
//echo '</offers>
//</auto-catalog>';
//	$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
//ob_end_clean(); 
// Write final string to file
//file_put_contents('nnmotors.xml', $htmlStr);
?>