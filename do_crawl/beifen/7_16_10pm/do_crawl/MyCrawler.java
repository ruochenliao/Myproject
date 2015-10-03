package do_crawl;

import java.io.IOException;
import java.util.Random;
import java.util.Set;
import java.util.UUID;
import java.util.regex.Pattern;

import javax.swing.text.Document;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

import edu.uci.ics.crawler4j.crawler.Page;
import edu.uci.ics.crawler4j.crawler.WebCrawler;
import edu.uci.ics.crawler4j.fetcher.PageFetchResult;
import edu.uci.ics.crawler4j.fetcher.PageFetcher;
import edu.uci.ics.crawler4j.parser.BinaryParseData;
import edu.uci.ics.crawler4j.parser.HtmlParseData;
import edu.uci.ics.crawler4j.url.WebURL;

public class MyCrawler extends WebCrawler {
    private final static Pattern FILTERS = Pattern.compile(".*(\\.(css|js"
                                                           + "|mp3|mp3|zip|gz))$");
    private static final Pattern imgPatterns = Pattern.compile(".*(\\.(bmp|gif|jpe?g|png|tiff?))$");
    /**
     * This method receives two parameters. The first parameter is the page
     * in which we have discovered this new url and the second parameter is
     * the new url. You should implement this function to specify whether
     * the given url should be crawled or not (based on your crawling logic).
     * In this example, we are instructing the crawler to ignore urls that
     * have css, js, git, ... extensions and to only accept urls that start
     * with "http://www.ics.uci.edu/". In this case, we didn't need the
     * referringPage parameter to make the decision.
     */
     @Override
     public boolean shouldVisit(Page referringPage, WebURL url) {
         String href = url.getURL().toLowerCase();
         /*
         return !FILTERS.matcher(href).matches()
                && href.startsWith("https://losangeles.craigslist.org/");
         */
         if( href.startsWith("https://losangeles.craigslist.org/") ){
        	 if( FILTERS.matcher(href).matches() )
        		 return false;
        	 /*
        	 if( imgPatterns.matcher(href).matches() )
        		 return true;
        	 */
        	 return true;
         }
         return false;
     }

     /**
      * This function is called when a page is fetched and ready
      * to be processed by your program.
      */
     @Override
     public void visit(Page page) {
    	 //Random rand = new Random();
    	 double random = Math.random() * 10 + 1;
    	 try {
			Thread.sleep((long) (random*1000));
		} catch (InterruptedException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    	 parse_page(page);
         /*
    	 String url = page.getWebURL().getURL();
         System.out.println("URL: " + url);

         if (page.getParseData() instanceof HtmlParseData) {
             HtmlParseData htmlParseData = (HtmlParseData) page.getParseData();
             String text = htmlParseData.getText();
             String html = htmlParseData.getHtml();
             Set<WebURL> links = htmlParseData.getOutgoingUrls();

             System.out.println("Text length: " + text.length());
             System.out.println("Html length: " + html.length());
             System.out.println("Number of outgoing links: " + links.size());
         }
         */
    }
     public void parse_page(Page page){
    	 
         String url = page.getWebURL().getURL();
         System.out.println("URL: " + url); 
         if (page.getParseData() instanceof HtmlParseData) {
             HtmlParseData htmlParseData = (HtmlParseData) page.getParseData();
             String text = htmlParseData.getText();
             String html = htmlParseData.getHtml();
             Set<WebURL> links = htmlParseData.getOutgoingUrls();
             String title = htmlParseData.getTitle();
             //Img_Crawler img_crawl = new Img_Crawler();
             //img_crawl.shouldVisit(referringPage, url);
             
             for( WebURL link: links ){
            	 String img_url = link.getURL();
            	 if ( !img_url.contains("50x50") ){
            	 //PageFetchResult img_page = PageFetcher.fetchPage(link);
	            	 if( imgPatterns.matcher(img_url).matches() )
	            		 System.out.println("img: "+img_url);
            	 }
             }
             String description;
             org.jsoup.nodes.Document doc = Jsoup.parse(html);
             //String des_test = escapeHtml(html);
             //String description = text.substring(html.indexOf("<title>"), lastIndexOf("</title>") );
             if( html.indexOf("<section id=\"postingbody\">")!=-1 ){
            	 Element content = doc.getElementById("postingbody");
            	 Elements contact_urls = content.getElementsByTag("a");
            	 String c_url = "";
            	 for(Element contact_url: contact_urls){
            		 c_url = contact_url.attr("href");
            	 }
            	 if(c_url.length() >10 ){
	            	 c_url = "https://losangeles.craigslist.org/"+ c_url;
	            	 System.out.println("contact: "+ c_url);
	            	 try {
						org.jsoup.nodes.Document c_doc =  Jsoup.connect( c_url ).get();
						String phone_num = c_doc.select(".posting_body").text();
						//System.out.println("contact information: "+ c_doc);
						System.out.println("phone number: "+phone_num);
					} catch (IOException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
            	 }
             }
             if( html.indexOf("replylink")!= -1 ){
	             Element reply_link = doc.getElementById("replylink");
	             String reply_url = reply_link.attr("href");
	             reply_url = "https://losangeles.craigslist.org/"+reply_url;
	             System.out.println("reply link: "+reply_url);
	             try {
						org.jsoup.nodes.Document r_doc =  Jsoup.connect( reply_url ).get();
						//System.out.println(r_doc);
						String email = r_doc.select(".mailapp").text();
						System.out.println("email: "+ email);
					} catch (IOException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
             }
             
         }
     }

	private int lastIndexOf(String string) {
		// TODO Auto-generated method stub
		return 0;
	}
}