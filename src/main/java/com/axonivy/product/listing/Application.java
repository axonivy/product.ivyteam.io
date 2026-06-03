package com.axonivy.product.listing;

import java.time.Clock;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;

import com.vaadin.flow.component.page.AppShellConfigurator;
import com.vaadin.flow.component.page.ColorScheme;
import com.vaadin.flow.component.page.ColorScheme.Value;
import com.vaadin.flow.server.AppShellSettings;

/**
 * The entry point of the Spring Boot application.
 *
 * Use the @PWA annotation make the application installable on phones, tablets
 * and some desktop browsers.
 */
@SpringBootApplication
@ColorScheme(Value.LIGHT)
public class Application implements AppShellConfigurator {

  @Bean
  public Clock clock() {
    return Clock.systemUTC();
  }

  public static void main(String[] args) {
    SpringApplication.run(Application.class, args);
  }

  @Override
  public void configurePage(AppShellSettings settings) {
    settings.setPageTitle("Product Listing");
    settings.addFavIcon("icon", "icons/icon.png", "80x80");
  }
}
