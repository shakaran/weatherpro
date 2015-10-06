<?php 
$scriptfolder	= './davconsole/';
$javascripts	= $scriptfolder.'javascripts/';
echo '
<link rel="stylesheet" type="text/css" href="'.$scriptfolder.'styleDavcon.css" />
<!--[if lte IE 8]><script type="text/javascript" src="'.$javascripts.'excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="javaScripts/jquery.js"></script>
<script type="text/javascript" src="'.$javascripts.'jquery.flot.js"></script>
<script type="text/javascript" src="'.$javascripts.'jscroller2-1.61.js"></script>
';
$EWNHEAD = $noDocready = true;
