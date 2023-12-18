<?
$startTime ='2020-01-21T16:43:58.802238083Z';


date_default_timezone_set('UTC'); 
echo strtotime($startTime)."\n"; // Proof: 

print_r(getdate(strtotime($startTime))); // IMPORTANT: It's possible that you will want to // restre the previous system timezone value here. // It would need to have been saved with // date_default_timezone_get().




?>