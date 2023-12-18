<?
  $campos = $_GET['campos'];

  if ($campos) $campos = explode(",", $campos);

?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="/">
  <html>
  <style>
.nowrap
{
  font-size: 12px;
  white-space: nowrap;
  background-color: #EEEEEE;
}

.nowrap2
{
  font-size: 12px;
  white-space: nowrap;
  background-color: #DDDDDD;
}

.celltit
{
  font-size: 12px;
  color: #FFFFFF;
  background-color: #000000;
}

  </style>
  <body>
    <table border="0" cellspacing="3" cellpadding="5">
    <tr>
<?


  for ($i=0;$i<count($campos);$i++)
  {
    echo '<td class="celltit">'.$campos[$i].'</td>';
  }
?>
    </tr>
    <xsl:for-each select="tabla/registro">
    <tr>
<?

  for ($i=0;$i<count($campos);$i++)
  {

    if (1&$n++) $estilo='nowrap'; else $estilo='nowrap2';
    echo '<td class="'.$estilo.'"><xsl:value-of select="'.$campos[$i].'"/><br/></td>';
  }
?>
    </tr>
    </xsl:for-each>
    </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet>