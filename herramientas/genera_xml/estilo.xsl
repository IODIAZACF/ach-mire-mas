<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
  <!-- iterate all child nodes -->
  <xsl:for-each select="*">
   <xsl:value-of select="NOMBRES"/><p/><xsl:value-of select="."/>
  </xsl:for-each>
</xsl:template>

</xsl:stylesheet>
