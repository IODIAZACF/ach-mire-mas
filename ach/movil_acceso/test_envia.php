<?php
$x='
[tabla] => D_NOTIFICACIONES
[busca] => ID_D_NOTIFICACIONES
[xbusca] => 0013939
[campos] => *
[c_CONFIRMADO_CSS] => NO
[auth] => eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJJRF9NX1VTVUFSSU8iOiIwMDE2NDA0IiwiTk9NQlJFUyI6IlJPRFJJR08iLCJBUEVMTElET1MiOiJTSU1VTEFET1IiLCJMT0dJTiI6IlIiLCJDTEFWRSI6IjdGQzU2MjcwRTdBNzBGQTgxQTU5MzVCNzJFQUNCRTI5IiwiSURfTV9HUlVQT1MiOiJYWFhYR1JVUDAwMTEiLCJJRF9NX1VTVUFSSU9TIjoiMDAxMjUiLCJJRF9NX0FSRUFTIjoiIiwiRkVDSEEiOiIyMDE2LTEwLTE4IDEyOjAxOjMzIiwiQUNDSU9OIjoiVSIsIlVOSUNPIjoiOTAyNDk3MTYwMjk5OUE5MENDQTg5MjVFQTFEN0VEOEQiLCJCQVJDT0RFIjoiTVVTVUE5NTkzMTczNzAwMDAwMDAiLCJJRCI6IjY0MDQiLCJJRF9FTVBSRVNBIjoiMDAxIiwiSFVFTExBIjoiIiwiU0VTU0lPTl9JRCI6IiIsIkVTVEFUVVMiOiJBQ1QiLCJJUCI6IiIsIkNBTVBPMSI6IiIsIkNBTVBPMiI6IiIsIkNBTVBPMyI6IiIsIkNBTVBPNCI6IiIsIkNBTVBPNSI6IiIsIk5JVkVMIjoiMCIsIlJPVFVMTyI6IiIsIkZFQ0hBX1JFR0lTVFJPIjoiMjAxNi0xMC0xNyAxNzozMDozMyIsIkNPRElHTzEiOiIiLCJDT0RJR08yIjoiIiwiQ09ESUdPMyI6IiIsIklEX01fTklWRUxFUyI6IjAwMTEiLCJOT01CUkVfQ09NUExFVE8iOiJTSU1VTEFET1IgUk9EUklHTyIsIklEX01fQ0xJRU5URVMiOiIiLCJJRF9NX0RFUEFSVEFNRU5UT1MiOiIiLCJGUkFTRSI6IiIsIkZPUk1VTEEiOiIiLCJGRUNIQV9OQUMiOiIxOTgwLTAxLTAxIDAwOjAwOjAwIiwiQ09ORElDSU9OX1NFU0lPTiI6IiIsIlVJRCI6IjA3MTJGRDA4LTQ1RkUtNEQ1MS05Njk4LUI0QkM1Qzc2REI3NCIsIkNPUlJFTyI6InJvZHJpZ28ubmF2YXJyb0BpbnZlbnRhLm1lIiwiVkVSU0lPTiI6IiIsIk9TIjoiIiwiTU9ERUxPIjoiIn0.pvo3V5f66J4NzPHGIN6hz4wcMI67TCa3YfrKU52TDCQ
[db] => agenda
[uid] => 0712FD08-45FE-4D51-9698-B4BC5C76DB74
[version] => 1.0
[os] => iOS
[modelo] => Simulator
';
echo "<pre>";
//echo $x;
$xquery = explode("\n", $x);
for($i=0;$i<sizeof($xquery);$i++)
{
    if(trim($xquery[$i])!='')
    {
	    $xp = explode('] =>', $xquery[$i]);
	    $tmp['campo'] =  str_replace('[', '',$xp[0]);
	    $tmp['valor'] =  trim($xp[1]);
	    $param[]=$tmp;
    }
}

$bar = each($param);
//print_r($bar);
 echo '<form action ="../herramientas/utiles/actualizar_registro_json.php" method="POST">' . "\n";
 for($i=0;$i<sizeof($param);$i++)
 {
     echo $param[$i]['campo']. '<input type="text" name="'. $param[$i]['campo'] .'" value="'. $param[$i]['valor'] .'">' . "\n";
 }
 echo '<input type="submit" value="Send">';
 echo '</form>'. "\n";

?>
