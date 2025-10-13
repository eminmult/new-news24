<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>XML Sitemap Index - OLAY.az</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style type="text/css">
          body {
            font-family: Consolas, Monaco, "Courier New", monospace;
            font-size: 13px;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
          }

          .header {
            background: #fff;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-left: 3px solid #4285f4;
          }

          .header h1 {
            font-size: 16px;
            font-weight: normal;
            margin: 0 0 5px 0;
            color: #333;
          }

          .header p {
            font-size: 12px;
            color: #666;
            margin: 0;
          }

          .content {
            background: #fff;
            padding: 20px;
          }

          table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
          }

          th {
            text-align: left;
            padding: 8px 12px;
            background: #fafafa;
            border-bottom: 2px solid #e0e0e0;
            font-weight: 600;
            color: #555;
            font-size: 11px;
            text-transform: uppercase;
          }

          td {
            padding: 8px 12px;
            border-bottom: 1px solid #f0f0f0;
          }

          tr:hover {
            background: #fafafa;
          }

          .url {
            color: #1a73e8;
            text-decoration: none;
            word-break: break-all;
          }

          .url:hover {
            text-decoration: underline;
          }

          .lastmod {
            color: #666;
          }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>XML Sitemap Index</h1>
          <p>This sitemap index contains <xsl:value-of select="count(sitemap:sitemapindex/sitemap:sitemap)"/> sitemaps</p>
        </div>

        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Sitemap</th>
                <th>Last Modified</th>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="sitemap:sitemapindex/sitemap:sitemap">
                <tr>
                  <td>
                    <a class="url" target="_blank">
                      <xsl:attribute name="href">
                        <xsl:value-of select="sitemap:loc"/>
                      </xsl:attribute>
                      <xsl:value-of select="sitemap:loc"/>
                    </a>
                  </td>
                  <td class="lastmod">
                    <xsl:value-of select="sitemap:lastmod"/>
                  </td>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
