Step 1 : Installing Solr
1. solr-7.2.1 was downloaded from the website as described in the homework description.
2. After navigating to solr folder, solr was started using bin/solr start.
3. bin/solr create –c homework – A homework core was created.
4. HTML files were retrieved from the Google Drive folder for NBC News. UrlToHtml_NBCNews.csv file was also used for it.
5. The command bin/solr stop –all was used to stop the solr.

Step 2: Solr setup
1. Solr was setup on port 8983.
2. http://localhost:8983/solr/ - Solr admin was accessed using this link.
3. HTML files were crawled and indexing of HTML pages was done using bin/post –c homework /home/piyush/Desktop/NBC_News/crawl_data
4. The string element with name “df”, was uncommented so that the default query field is “_text_”.

Step 3: Web page indexing
1. Jsoup jar was downloaded and a Java file was written to find the edgelist for the webpages.

Step 4: PageRank Ranking Algorithm Setup
1. Networkx was downloaded and installed, thus edgelist file was read and thus external_pageRankfile.txt was created.
2. Thus the external_pageRankfile.txt file was copied to the data folder.
3. In the initial condition of page rank algorithm in pagerank.py file alpha =0.5, personalization=None, max_iter=30, tol=1e-06, nstart=None, weight='weight', dangling=None). These parameters were set as these ones.

Step 5: PHP Code
1. PHP code was written for making an option to compare word search with radio buttons for page rank and Solr Lucene algorithms.
2. Moreover, I used XAMPP and it was downloaded and thus I put all files namely search_updated.php, external_pageRankfile.txt, solr-php-client and UrltoHtml_NBCNews.csv in it.

Step 6:
1. Fields were added for PageRank in managed-schema.
<fieldType name="external" keyField="id" defVal="0" class="solr.ExternalFileField"/> <field name="pageRankFile" type="external" stored="false" indexed="false"/>
2. Similarly, listeners were defined for query in solrconfig.xml
<listener event="newSearcher" class="org.apache.solr.schema.ExternalFileFieldReloader"/>
<listener event="firstSearcher" class="org.apache.solr.schema.ExternalFileFieldReloader"/>
3. Solr was reloaded and thus queries were run for the search algorithms.
4. In php code, I did it for my core "homework" and used bootstrap for UI purposes.
