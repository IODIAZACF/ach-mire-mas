<?php
while (list($key, $val) = each($_POST)) {

	$_POST[$key] = str_replace("\\", "\\\\", $val);
}
?>