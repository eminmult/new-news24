<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"
                xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <title>XML Sitemap - OLAY.az</title>
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

          .priority {
            color: #0d652d;
          }

          .lastmod {
            color: #666;
          }

          .changefreq {
            color: #7c4dff;
          }

          .count {
            color: #999;
            font-size: 11px;
            margin-bottom: 10px;
          }
        </style>
      </head>
      <body>
        <div class="header">
          <h1>XML Sitemap</h1>
          <p>This is a sitemap for search engines. <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/> URLs</p>
        </div>

        <div class="content">
          <table>
            <thead>
              <tr>
                <th>URL</th>
                <xsl:if test="sitemap:urlset/sitemap:url/sitemap:lastmod">
                  <th>Last Modified</th>
                </xsl:if>
                <xsl:if test="sitemap:urlset/sitemap:url/sitemap:changefreq">
                  <th>Change Frequency</th>
                </xsl:if>
                <xsl:if test="sitemap:urlset/sitemap:url/sitemap:priority">
                  <th>Priority</th>
                </xsl:if>
              </tr>
            </thead>
            <tbody>
              <xsl:for-each select="sitemap:urlset/sitemap:url">
                <tr>
                  <td>
                    <a class="url" target="_blank">
                      <xsl:attribute name="href">
                        <xsl:value-of select="sitemap:loc"/>
                      </xsl:attribute>
                      <xsl:value-of select="sitemap:loc"/>
                    </a>
                  </td>
                  <xsl:if test="sitemap:lastmod">
                    <td class="lastmod">
                      <xsl:value-of select="sitemap:lastmod"/>
                    </td>
                  </xsl:if>
                  <xsl:if test="sitemap:changefreq">
                    <td class="changefreq">
                      <xsl:value-of select="sitemap:changefreq"/>
                    </td>
                  </xsl:if>
                  <xsl:if test="sitemap:priority">
                    <td class="priority">
                      <xsl:value-of select="sitemap:priority"/>
                    </td>
                  </xsl:if>
                </tr>
              </xsl:for-each>
            </tbody>
          </table>
        </div>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
