package do_crawl;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import edu.uci.ics.crawler4j.crawler.CrawlConfig;
import edu.uci.ics.crawler4j.crawler.CrawlController;
import edu.uci.ics.crawler4j.fetcher.PageFetcher;
import edu.uci.ics.crawler4j.robotstxt.RobotstxtConfig;
import edu.uci.ics.crawler4j.robotstxt.RobotstxtServer;
/**
 * @author Yasser Ganjisaffar
 */
public class ImageCrawlController {
  private static final Logger logger = LoggerFactory.getLogger(ImageCrawlController.class);

  public static void main(String[] args) throws Exception {
/*
	  if (args.length < 3) {
      logger.info("Needed parameters: ");
      logger.info("\t rootFolder (it will contain intermediate crawl data)");
      logger.info("\t numberOfCralwers (number of concurrent threads)");
      logger.info("\t storageFolder (a folder for storing downloaded images)");
      return;
    }
*/
    String rootFolder = "/data/crawl/root";
    int numberOfCrawlers = 7;
    String storageFolder = "/data/crawl/root";

    CrawlConfig config = new CrawlConfig();

    config.setCrawlStorageFolder(rootFolder);

    /*
     * Since images are binary content, we need to set this parameter to
     * true to make sure they are included in the crawl.
     */
    config.setIncludeBinaryContentInCrawling(true);

    String[] crawlDomains = {"http://uci.edu/"};

    PageFetcher pageFetcher = new PageFetcher(config);
    RobotstxtConfig robotstxtConfig = new RobotstxtConfig();
    RobotstxtServer robotstxtServer = new RobotstxtServer(robotstxtConfig, pageFetcher);
    CrawlController controller = new CrawlController(config, pageFetcher, robotstxtServer);
    for (String domain : crawlDomains) {
      controller.addSeed(domain);
    }

    ImageCrawler.configure(crawlDomains, storageFolder);

    controller.start(ImageCrawler.class, numberOfCrawlers);
  }
}