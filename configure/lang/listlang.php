<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);	
$arr		= file ('lang-nl.txt');
#echo '<pre>'; print_r($arr); exit;
$count = count ($arr);
if ($count < 10) {echo '<h3> error empty file </h3>'; exit;}
echo '<pre>';
for ($n1 = 0; $n1 < $count; $n1++) {
	$line	= $arr[$n1];
	if (substr($line,0,10) <> 'langlookup') {continue;}
	list ($langlookup,$english,$translated) = explode ('|',$line);
	echo trim($translated).PHP_EOL;
}
echo 'finished';
