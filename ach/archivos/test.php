<html>

<head>
  <title></title>
</head>

<body>
<form name="FormName" action="procesar_upload.php" method="post" enctype="multipart/form-data">
  <input name="IDX" type="text" value="0011"><br>
  <input name="NOMBRES" type="text" value="CEDULA DE IDENTIDAD"><br>
  <input name="CARPETA" type="text" value="imagenes/expediente/001XX"><br>
  <input name="DB" type="text" value="clarbusiness"><br>
  <input type="file" name="archivo" size="40"><br>


  <input type="submit" value="Send">
</form>

</body>

</html>
<?
phpinfo();
?>