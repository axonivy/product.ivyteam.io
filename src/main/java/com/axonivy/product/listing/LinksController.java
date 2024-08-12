package com.axonivy.product.listing;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RestController;

import com.axonivy.product.listing.crawler.Crawler;

@RestController
class LinksController {

  @GetMapping(value = { "links" }, produces = "text/html")
  String all() {
    var jobs = Crawler.jobs();
    var content = "";
    for (var job : jobs) {
      content += "<a href=\"";
      content += job.url();
      content += "\">";
      content += job.url();
      content += "</a>";
      content += "<br />";
    }
    return content;
  }
}
