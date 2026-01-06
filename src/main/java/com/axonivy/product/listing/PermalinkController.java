package com.axonivy.product.listing;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RestController;

import com.axonivy.product.listing.crawler.Crawler;

@RestController
class PermalinkController {

  @GetMapping(value = { "permalink/vscode-designer/{version}" })
  ResponseEntity<Void> all(@PathVariable String version) {
    var j = Crawler.jobs().stream()
        .filter(job -> job.artifact().equals("vscode-designer"))
        .filter(job -> job.version().startsWith(version))
        .findFirst()
        .orElse(null);

    if (j == null) {
      return ResponseEntity.status(HttpStatus.NOT_FOUND).build();
    }

    return ResponseEntity
        .status(HttpStatus.TEMPORARY_REDIRECT)
        .header("Location", j.url())
        .build();
  }
}
