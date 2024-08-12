package com.axonivy.product.listing;

import java.util.stream.Collectors;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

import com.axonivy.product.listing.crawler.Crawler;

@RestController
class LinksController {

  @GetMapping(value = { "links" }, produces = "text/html")
  String all() {
    var jobs = Crawler.jobs().stream()
            .map(job -> job.url())
            .collect(Collectors.joining("<br />"));
    return jobs;
  }
}
