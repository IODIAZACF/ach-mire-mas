<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:template match="/">
  <html>
  <body>
    <h2>My CD Collection</h2>
    <table border="1">
    <xsl:for-each select="tabla/registro">
    <tr>
       <TD><xsl:value-of select="NOMBRES "/></TD>
       <TD><xsl:value-of select="CODIGO1 "/></TD>
    </tr>
    </xsl:for-each>
    </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet>
