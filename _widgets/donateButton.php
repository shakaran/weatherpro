<?php  #example script - use your own paypal code
if ( (isset ($SITE['donateButton'])) && ($SITE['donateButton']) ) {
	if ($lang == 'nl') {
echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="P6GCE5BALRWL8" />
<input type="image" src="https://www.paypalobjects.com/nl_NL/BE/i/btn/btn_donateCC_LG.gif"  name="submit" alt="PayPal, de veilige en complete manier van online betalen." />
<img  src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="" />
</form>'.PHP_EOL;
	} elseif ($lang == 'de') {
echo '
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" />
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="LMSLPPLQ9G8QW" />
<input type="image" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen - mit PayPal." />
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>'.PHP_EOL;
	} elseif ($lang == 'fr') {
echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="S4RYZWQRAWTEE">
<input type="image" src="https://www.paypalobjects.com/fr_FR/BE/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>'.PHP_EOL;
	} else {
echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YNAZH4RRXY6KJ">
<input type="image" src="https://www.paypalobjects.com/en_US/BE/i/btn/btn_donateCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>'.PHP_EOL;
	}
}
?>