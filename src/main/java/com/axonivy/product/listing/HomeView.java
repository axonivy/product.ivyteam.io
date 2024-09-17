package com.axonivy.product.listing;

import com.axonivy.product.listing.crawler.Crawler;
import com.axonivy.product.listing.crawler.JobComparator;
import com.axonivy.product.listing.crawler.JobsCrawler.Job;
import com.vaadin.flow.component.applayout.AppLayout;
import com.vaadin.flow.component.applayout.DrawerToggle;
import com.vaadin.flow.component.grid.Grid;
import com.vaadin.flow.component.html.H1;
import com.vaadin.flow.component.icon.VaadinIcon;
import com.vaadin.flow.component.orderedlayout.Scroller;
import com.vaadin.flow.component.sidenav.SideNav;
import com.vaadin.flow.component.sidenav.SideNavItem;
import com.vaadin.flow.data.renderer.LitRenderer;
import com.vaadin.flow.router.Route;
import com.vaadin.flow.theme.lumo.LumoUtility;

@Route("")
public class HomeView extends AppLayout {

    public HomeView() {
        var toggle = new DrawerToggle();
        var title = new H1("Axon Ivy Product Listing");
        title.getStyle()
                .set("font-size", "var(--lumo-font-size-l)")
                .set("margin", "0");

        var nav = getSideNav();
        var scroller = new Scroller(nav);
        scroller.setClassName(LumoUtility.Padding.SMALL);
        addToDrawer(scroller);
        addToNavbar(toggle, title);

        var jobs = Crawler.jobs();

        var grid = new Grid<Job>();
        grid.setItems(jobs);
        grid
                .addColumn(Job::version)
                .setHeader("Version")
                .setWidth("10%")
                .setSortable(true)
                .setComparator(new JobComparator());
        grid
                .addColumn(Job::artifact)
                .setWidth("10%")
                .setHeader("Artifact")
                .setSortable(true);
        grid
                .addColumn(Job::os)
                .setHeader("OS")
                .setWidth("10%")
                .setSortable(true);
        grid
                .addColumn(Job::platform)
                .setHeader("Platform")
                .setWidth("10%")
                .setSortable(true);
        grid
                .addColumn(LitRenderer.<Job>of("""
                           <a href="${item.url}">${item.text}</a>
                        """)
                        .withProperty("url", p -> p.url())
                        .withProperty("text", p -> p.text()))
                .setHeader("Link")
                .setWidth("60%");
        grid.setHeightFull();
        setContent(grid);
    }

    private SideNav getSideNav() {
        var sideNav = new SideNav();
        sideNav.addItem(new SideNavItem("Dashboard", "/", VaadinIcon.DASHBOARD.create()));
        return sideNav;
    }
}