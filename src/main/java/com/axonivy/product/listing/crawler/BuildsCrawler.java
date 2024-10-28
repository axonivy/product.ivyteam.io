package com.axonivy.product.listing.crawler;

import java.io.IOException;
import java.io.InputStream;
import java.nio.charset.StandardCharsets;
import java.util.HashSet;
import java.util.Set;

import org.jsoup.Jsoup;

public class BuildsCrawler {

  private final InputStream in;
  private final String baseUrl;

  public BuildsCrawler(InputStream in, String baseUrl) {
    this.in = in;
    this.baseUrl = baseUrl;
  }

  public Set<Build> get() {
    if (in == null) {
      return Set.of();
    }
    var builds = new HashSet<Build>();
    try {
      var doc = Jsoup.parse(in, StandardCharsets.UTF_8.name(), baseUrl);
      var aTags = doc.getElementsByTag("a");
      for (var aTag : aTags) {
        var title = aTag.attr("href");
        if (title.equals("job/master/")
            || (title.startsWith("job/release%252F") && count(title) == 2) && title.endsWith("/")) {
          var link = aTag.absUrl("href") + "lastSuccessfulBuild/";
          builds.add(new Build(link));
        }
      }
      return builds;
    } catch (IOException ex) {
      throw new RuntimeException(ex);
    }
  }

  private static long count(String string) {
    return string.chars().filter(ch -> ch == '/').count();
  }

  public record Build(String url) {
  }
}
