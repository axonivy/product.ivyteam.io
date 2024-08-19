import {
  AppLayout,
  Grid,
  GridColumn,
  Icon,
  TextField,
} from "@vaadin/react-components";
import Job from "Frontend/generated/com/axonivy/product/listing/crawler/JobsCrawler/Job";
import { Crawler } from "Frontend/generated/endpoints";
import { useEffect, useState } from "react";

const linkRenderer = (job: Job) => <a href={job.url}>{job.text}</a>;

const ProductView = () => {
  const [items, setItems] = useState<Job[]>([]);
  const [filteredItems, setFilteredItems] = useState<Job[]>([]);
  useEffect(() => {
    Crawler.jobs().then((jobs) => {
      if (jobs) {
        const newJob = jobs.filter((job): job is Job => !!job);
        setItems(newJob);
        setFilteredItems(newJob);
      }
    });
  }, []);

  return (
    <>
      <h1
        slot="navbar"
        style={{ margin: 5, fontSize: "var(--lumo-font-size-l)" }}
      >
        Axon Ivy Product Listing
      </h1>
      <TextField
        placeholder="Search"
        style={{ width: "100%" }}
        onValueChanged={(e) => {
          const searchTerm = (e.detail.value || "").trim().toLowerCase();
          setFilteredItems(
            items.filter(
              ({ version, artifact, os, platform, text }) =>
                !searchTerm ||
                version?.toLowerCase().includes(searchTerm) ||
                artifact?.toLowerCase().includes(searchTerm) ||
                os?.toLowerCase().includes(searchTerm) ||
                platform?.toLowerCase().includes(searchTerm) ||
                text?.toLowerCase().includes(searchTerm)
            )
          );
        }}
      >
        <Icon slot="prefix" icon="vaadin:search"></Icon>
      </TextField>
      <Grid style={{ height: "100%" }} items={filteredItems}>
        <GridColumn header="Version" width="10%" path="version" />
        <GridColumn header="Articaft" width="10%" path="artifact" />
        <GridColumn header="OS" width="10%" path="os" />
        <GridColumn header="Platform" width="10%" path="platform" />
        <GridColumn header="Link" width="60%">
          {({ item }) => linkRenderer(item)}
        </GridColumn>
      </Grid>
    </>
  );
};

export default function MainLayout() {
  return (
    <AppLayout>
      <ProductView></ProductView>
    </AppLayout>
  );
}
