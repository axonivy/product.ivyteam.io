package com.axonivy.product.listing.crawler;

import java.io.InputStream;
import java.net.URI;
import java.util.List;

import com.axonivy.product.listing.crawler.JobsCrawler.Job;

public class Crawler {

  private static final List<String> URLS = List.of("https://jenkins.ivyteam.io/job/core_product/",
      "https://jenkins.ivyteam.io/job/core-7_product/");

  public static List<Job> jobs() {
    return URLS.parallelStream()
        .flatMap(uri -> new BuildsCrawler(toInputStream(uri), uri).get().parallelStream())
        .flatMap(b -> new JobsCrawler(toInputStream(b.url()), b.url()).get().parallelStream())
        .sorted(new JobComparator()).toList();
  }

  private static InputStream toInputStream(String url) {
    try {
      return URI.create(url).toURL().openStream();
    } catch (Exception ex) {
      return null;
    }
  }
}
