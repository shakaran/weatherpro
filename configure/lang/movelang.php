<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);	
$arr		= file ('lang-en.txt');
$arr_nw		= file('nl.txt');
#echo '<pre>'; print_r($arr); exit;
$count = count ($arr);
if ($count < 10) {echo '<h3> error empty file </h3>'; exit;}
echo '<pre>';
$n_nw	= 0;
$string	= '';
for ($n1 = 0; $n1 < $count; $n1++) {
	$line	= $arr[$n1];
	if (substr($line,0,10) <> 'langlookup') {
		$string 	.= $line;
		continue;
	}
	list ($langlookup,$english,$translated) = explode ('|',$line);
	$translated 	= trim($arr_nw[$n_nw]);
	$string		.= $langlookup.'|'.$english.'|'.$translated.PHP_EOL;
	$n_nw++;
#echo $string;
}
file_put_contents ( 'lang-nl.txt ',$string);
echo 'finished';
