<?php

// настройки
$site_name = 'http://www.viralnova.com/';
//$db_user = 'root';
//$db_pass = '123456';
//$db_name = 'db_name';
//$db_table_name = 'table';
 
// Заголовки
$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.12) Gecko/20050919 Firefox/1.0.7" ;
$header[] = "Accept: text/html;q=0.9, text/plain;q=0.8, image/png, */*;q=0.5" ;
$header[] = "Accept_charset: windows-1251, utf-8, utf-16;q=0.6, *;q=0.1";
$header[] = "Accept_encoding: identity";
$header[] = "Accept_language: en-us,en;q=0.5";
$header[] = "Connection: close";
$header[] = "Cache-Control: no-store, no-cache, must-revalidate";
$header[] = "Keep_alive: 300";
$header[] = "Expires: Thu, 01 Jan 1970 00:00:01 GMT";
 
// curl
$ch = curl_init($site_name.'/photos/');
@curl_setopt ( $ch , CURLOPT_RETURNTRANSFER , 1 );
@curl_setopt ( $ch , CURLOPT_USERAGENT , $agent );
@curl_setopt ( $ch , CURLOPT_HTTPHEADER , $header );
@curl_setopt($ch, CURLOPT_HEADER, 0);
$result = @curl_exec($ch);
curl_close($ch);
 
// вырезаем урлы
preg_match_all('/<a href=\"(\/photos\/[0-9A-Za-z@-]+\/[0-9A-Z@-]+\/)\" title=\".+\"><img.+src=\"(http:\/\/farm3\.static\.flickr\.com\/[0-9]+\/[0-9]+_[a-zA-Z0-9]+_t\.jpg)\"/',$result,$array);
 
?>
<h3>Ссылки на фото:</h3>
<table cellspacing="4">
<?php
 
// выводим фоты и урлы на них
for ($i=0; $i<count($array[1]); $i++)
{
 echo "<tr><td><img src=\"{$array[2][$i]}\"></td>
   <td valign=\"top\"><a href=\"{$site_name}{$array[1][$i]}\">{$site_name}{$array[1][$i]}</a></td></tr>";
}
?>
</table>
<?php
$link = mysql_connect('localhost', $db_user, $db_pass) or die(mysql_error());
mysql_query("CREATE DATABASE IF NOT EXISTS $db_name",$link) or die(mysql_error());
mysql_select_db($db_name);
mysql_query("CREATE TABLE IF NOT EXISTS `$db_table_name` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(500) NOT NULL ,
  `image` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;",$link) or die(mysql_error());
 
for ($i=0; $i<count($array[1]); $i++)
{
 $res = mysql_query("SELECT `{$db_table_name}`.`id` FROM `$db_table_name` WHERE `{$db_table_name}`.`url` = \"{$array[1][$i]}\"",$link) or die(mysql_error());
 // проверяем существует ли урл на фотографию в БД
 if (mysql_num_fields($res)==1)
 {
  $values .= ",(\"{$site_name}{$array[1][$i]}\", \"{$array[2][$i]}\")\n";
  preg_match('/[0-9]+_[a-zA-Z0-9]+_t\.jpg/',$array[2][$i],$image);
  copy($array[2][$i],'photos/'.$image[0]);
 }
}
$values = substr($values,1,strlen($values));
 
// заносим фотографии в БД
$query = "INSERT INTO `$db_table_name` (`url`, `image`) VALUES $values ;";
mysql_query($query,$link) or die(mysql_error());
?>