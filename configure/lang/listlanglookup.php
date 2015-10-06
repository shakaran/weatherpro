<?php ini_set('display_errors', 'On');   error_reporting(E_ALL);	
include 'questions_en.php';
#echo '<pre>'; print_r($LANGLOOKUP); exit;
$count = count ($LANGLOOKUP);
if ($count < 10) {echo '<h3> error empty file </h3>'; exit;}

$todo 	= 'list';
$n1	= 0;
$todo	= 'merge';
$new	= 'nl_look.txt';

if ($todo == 'list') {
	echo '<pre>';
	foreach ($LANGLOOKUP as $key => $value) {
		$value = str_replace ('<','&lt;',$value);
		$value = str_replace ("\n",' ',$value);
		echo '|'.$n1.'|'.$value.PHP_EOL;
		$n1++;
	}
} // eo list

if ($todo == 'merge') {
	$arr	= file ($new);
	$trans  = array();
	$count  = count ($arr);
	for ($n1 = 0; $n1 < $count; $n1++) {
		$line	= $arr[$n1];
		list ($none,$nr,$value) = explode ('|',$line.'|');
		$trans[$nr]	= $value;
	}
	$n1	= 0;	
	foreach ($LANGLOOKUP as $key => $value) {
		$LANGLOOKUP[$key]=$trans[$n1];
		$string	= substr('$LANGLOOKUP[\''.$key."']                                ",0,40)."= '".trim($trans[$n1])."';".PHP_EOL;
		echo $string;
		$n1++;
	}

	

#echo '<pre>'; print_r($LANGLOOKUP); 
	exit;
}
#



echo '<h3> invalid todo, program stopped</h3>'; exit;