<?php
echo "<html><head><title>Привет</title><meta http-equiv='Content-Type' content='text/html; charset=utf-8'></head><body><table>";
$dir    = '/var/www/parsers';
$files = scandir($dir);
foreach($files as $file){
	if(strpos($file,'.xml') !== false){
		echo '<tr><td><a href="/parsers/'.$file.'">Открыть '.$file.'</a></td><td>Дата обновления ';
		$xml = file_get_contents($dir.'/'.$file);
		$arr_xml = explode("creation-date", $xml);
		$arr_xml[1] = str_replace("<", "", $arr_xml[1]);
		$arr_xml[1] = str_replace(">", "", $arr_xml[1]);
		$arr_xml[1] = str_replace("/", "", $arr_xml[1]);
		echo $arr_xml[1];
		echo '</td><td>';
		if((strpos($xml,'warning') !== false) or (strpos($xml,'notice') !== false)){
			echo 'Есть ошибки :(';
		}else{
			echo 'Нет ошибок';
		}
		echo '</td><td>';
		$offers = explode("offer type", $xml);
		echo 'Объявлений: '.count($offers);

		echo '</td></tr>';
	}
}
echo "</table>";
echo "<br><br>CRON содержит следующее:<br>";
$data_array = @file("/var/spool/cron/crontabs/root");
foreach($data_array as $k=>$v)
{
    if(strpos($v,'#') === false){ 
		$v = str_replace("/var/www/parsers/", "", $v);
		$v = str_replace(".php", "", $v);
		$v = str_replace(" php ", " ", $v);
		echo $v;
		echo "<br>";
	}

}


echo "</body></html>";
?>