# Axon Ivy Engine Listing

Service which grabs and displays the current Axon Ivy Engines builds from different jobs of Jenkins.

![Engine Listing Service](service.png)

## Development
  
Run `./dev.sh` to start the website in docker
  
... and later `docker compose down` to stop the containers.

## Execute tests

Tests can be executed when the dev environment is running by running `./test.sh`.

## VSCode Setup

- Install extension **PHP Intelphense** and follow the Quickstart guide
- Install extension **Twig**

## Update a php library

```
// Show outdated dependencies
docker compose exec web composer show --outdated

// Upgrade dependencies
docker compose exec web composer update --prefer-dist -a --with-all-dependencies
```

## Resources

- SlimFramework <http://www.slimframework.com>
