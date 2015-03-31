<?php

//ob_start();
ini_set("max_execution_time", "1200"); //изменяем максимальное время выполнения скрипта до 1200 секунд	
	$now=date("Y-m-d H:i:s");

	echo '<?xml version="1.0" encoding="utf-8"?>
	<img-catalog>
	<creation-date>'.$now.' GMT+4</creation-date>

	<contents>';

for ($i = 1; $i <= 10; $i++) {
	
	$my_url = 'http://www.viralnova.com/page/'.$i.'/';
	$html = file_get_contents($my_url);
	
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	
	$xpath = new DOMXPath($dom);
	$my_xpath_query = "//h2[@class='entry-title']/a/@href";
	$result_rows = $xpath->query($my_xpath_query);

	foreach($result_rows as $result) {
	$current="";
		$html = file_get_contents( $result->textContent, false, $context);
		
		$dom2 = new DOMDocument();
		@$dom2->loadHTML($html);
		$xpath2 = new DOMXPath($dom2);
		
		$my_xpath_query2 = "//div[@class='wp-caption-text gallery-caption']";
		$result_rows2 = $xpath2->query($my_xpath_query2);
		//Get captions
		
		//Get title
		$xpath3 = new DOMXPath($dom2);
		$my_xpath_query3 = "//h2[@class='entry-title']";
		$result_rows3 = $xpath3->query($my_xpath_query3);
		
		$papka = explode("/", $result->textContent );
		$papka = $papka[3];
		
		
		$title=$result_rows3->item(0)->textContent;
		//$title=trim($title);
		$my_xpath_query9 = "//div[@class='format_text entry-content']/p";
		$result_rows9 = $xpath2->query($my_xpath_query9);

		$current .= '<content type="private">
			<url>'.$result_rows->item(1)->textContent.'</url>
			<date>'.$now.' GMT+4</date>
			<title>'.$title.'</title>
			<caption>'.$result_rows2->item(1)->textContent.'</caption>
			<caption1>'.$result_rows2->item(2)->textContent.'</caption1>
			<caption2>'.$result_rows2->item(3)->textContent.'</caption2>
			<caption3>'.$result_rows2->item(4)->textContent.'</caption3>
			<caption4>'.$result_rows2->item(5)->textContent.'</caption4>
			<caption5>'.$result_rows2->item(6)->textContent.'</caption5>
			<caption6>'.$result_rows2->item(7)->textContent.'</caption6>
			<caption7>'.$result_rows2->item(8)->textContent.'</caption7>
			<caption8>'.$result_rows2->item(9)->textContent.'</caption8>
			<caption9>'.$result_rows2->item(10)->textContent.'</caption9>
			<caption10>'.$result_rows2->item(11)->textContent.'</caption10>
			<caption11>'.$result_rows2->item(12)->textContent.'</caption11>
			<caption12>'.$result_rows2->item(13)->textContent.'</caption12>
			<caption13>'.$result_rows2->item(14)->textContent.'</caption13>
			<caption14>'.$result_rows2->item(15)->textContent.'</caption14>
			<caption15>'.$result_rows2->item(16)->textContent.'</caption15>
			<caption16>'.$result_rows2->item(17)->textContent.'</caption16>
			<caption17>'.$result_rows2->item(18)->textContent.'</caption17>
			
			
			
			<content>'.$result_rows9->item(0)->textContent.'</content>
			<content1>'.$result_rows9->item(1)->textContent.'</content1>
			<content2>'.$result_rows9->item(3)->textContent.'</content2>
			<content3>'.$result_rows9->item(4)->textContent.'</content3>
			<content4>'.$result_rows9->item(5)->textContent.'</content4>
			<content5>'.$result_rows9->item(6)->textContent.'</content5>
			<content6>'.$result_rows9->item(7)->textContent.'</content6>';
			
		//Get images
		$my_xpath_query5 = "//a/img[@class='attachment-full']/@src";
		$result_rows5 = $xpath2->query($my_xpath_query5);
		foreach($result_rows5 as $result5) {
			//var_dump($result2);
			$current .= '<image>'.($result5->textContent).'</image>';
		}
		$current .=	
			'<Source> Источник: '.$result->textContent.'</Source>

	</content>';

	mkdir("viral/".$papka, 0777);
	file_put_contents("viral/".$papka."/".$papka.".txt", $current);
	echo $current;

$ew=0;
foreach($result_rows5 as $result5) {
	$fileName = "viral/".$papka."/".$ew.".jpg";
	$current2 = file_get_contents($result5->textContent);
	file_put_contents( $fileName, $current2);
	$ew++;
}

		}
	}
echo '</contents>
</img-catalog>';
$htmlStr = ob_get_contents();
//Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
//Write final string to file
file_put_contents('viral/'.$papka.'/'.$er.'txt.', $htmlStr);
?>