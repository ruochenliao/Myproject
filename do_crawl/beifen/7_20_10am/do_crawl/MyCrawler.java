package do_crawl;

import java.io.IOException;
import java.util.Set;
import java.util.regex.Pattern;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

import edu.uci.ics.crawler4j.crawler.Page;
import edu.uci.ics.crawler4j.crawler.WebCrawler;
import edu.uci.ics.crawler4j.parser.HtmlParseData;
import edu.uci.ics.crawler4j.url.WebURL;

public class MyCrawler extends WebCrawler {
	String json = "{";
	public static int index = 0;

	private final static Pattern FILTERS = Pattern
			.compile(".*(\\.(css|js|gif|jpg" + "|png|mp3|mp3|zip|gz))$");
	private static final Pattern imgPatterns = Pattern
			.compile(".*(\\.(bmp|gif|jpe?g|png|tiff?))$");

	/**
	 * This method receives two parameters. The first parameter is the page in
	 * which we have discovered this new url and the second parameter is the new
	 * url. You should implement this function to specify whether the given url
	 * should be crawled or not (based on your crawling logic). In this example,
	 * we are instructing the crawler to ignore urls that have css, js, git, ...
	 * extensions and to only accept urls that start with
	 * "http://www.ics.uci.edu/". In this case, we didn't need the referringPage
	 * parameter to make the decision.
	 */
	@Override
	public boolean shouldVisit(Page referringPage, WebURL url) {
		String href = url.getURL().toLowerCase();
		return !FILTERS.matcher(href).matches()
				&& href.startsWith("https://losangeles.craigslist.org/")
				&& (href.contains("/cto/") || href.contains("/ctd/"));
	}

	/**
	 * This function is called when a page is fetched and ready to be processed
	 * by your program.
	 */
	@Override
	public void visit(Page page) {
	   	 double random = Math.random() * 10 + 1;
	   	 try {
				Thread.sleep((long) (random*1000));
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}		
		parse_page(page);
	}
	public void parse_page(Page page){
        if (page.getParseData() instanceof HtmlParseData && index >0) {
            String url = page.getWebURL().getURL();
            System.out.println("No: " + index);
            System.out.println("URL: " + url);
            json +=  "\"" + index +"\":{";
            json += "\"URL\":\""+url+"\",";        	
            HtmlParseData htmlParseData = (HtmlParseData) page.getParseData();
            String html = htmlParseData.getHtml();
            org.jsoup.nodes.Document doc = Jsoup.parse(html);
            Set<WebURL> links = htmlParseData.getOutgoingUrls();
            String title = htmlParseData.getTitle();
            System.out.println("Title: " + title);
            json += "\"Title\":\"" + title+"\",";
            String price = doc.select(".price").text();
            System.out.println("price: "+price);
            json += "\"Price\":\"" + price + "\",";
            String time = doc.select("time").first().text();
            System.out.println("posted on: "+ time);
            json += "\"Posted on\":\""+ time +"\",";
            String map_url = doc.select("a[target=_blank]").attr("href");
            System.out.println("map: "+map_url);
            json += "\"map_url\":\""+ map_url +"\","; 
            //System.out.println(json);
            String[] import_info = new String[20];
            Elements info = doc.select(".attrgroup").select("span");
            for(int i =0;i< info.size();i++){
            	import_info[i] = info.get(i).text();
            	System.out.println("important infor: "+ import_info[i]);
            	json+= "\"import info"+ i+"\":\""+ import_info[i]+"\",";
            }
            Element content = doc.getElementById("postingbody");
            if( html.indexOf("<section id=\"postingbody\">")!=-1 ){
              	 
              	 Elements contact_urls = content.getElementsByTag("a");
              	 String c_url = "";
              	 for(Element contact_url: contact_urls){
              		 c_url = contact_url.attr("href");
              	 }
              	 if(c_url.length() >10 ){
   	            	 c_url = "https://losangeles.craigslist.org"+ c_url;
   	            	 //System.out.println("contact: "+ c_url);
   	            	 
   	            	 try {
   						org.jsoup.nodes.Document c_doc =  Jsoup.connect( c_url ).get();
   						String phone_num = c_doc.select(".posting_body").text();
   						System.out.println("contact information: "+phone_num);
   						json += "\"contact info\""+":\""+phone_num+"\",";
   					} catch (IOException e) {
   						e.printStackTrace();
   					}
              	 }
              }
              if( html.indexOf("replylink")!= -1 ){
   	             Element reply_link = doc.getElementById("replylink");
   	             String reply_url = reply_link.attr("href");
   	             reply_url = "https://losangeles.craigslist.org/"+reply_url;
   	             //System.out.println("reply link: "+reply_url);
   	             try {
   						org.jsoup.nodes.Document r_doc =  Jsoup.connect( reply_url ).get();
   						String email = r_doc.select(".mailapp").text();
   						System.out.println("Email: "+ email);
   						json += "\"Email\""+":\""+email+"\",";
   					} catch (IOException e) {
   						e.printStackTrace();
   					}
              }
              json+="\"description\":\""+content.text()+"\",";
              json+= "\"img\":[";
	          for( WebURL link: links ){
		           String img_url = link.getURL();
		           if ( !img_url.contains("50x50") ){
			            if( imgPatterns.matcher(img_url).matches() ){
			            	System.out.println("img: "+img_url);
			            	json += "{\"img_url\":\""+img_url+"\"},";
			            }
		           	 }
	          }
	          json = json.substring(0, json.length()-1);
	          json +="]},";
	          System.out.println(json);
        }
        index++;
    }
}