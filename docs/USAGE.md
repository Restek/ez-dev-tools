# Usage

## Database Development

### Overview
Importing and exporting databases should be easy. However when you throw in redis and solr it can add up. Typically when importing a database with eZPlatform you have to clear the redis cache (if you are using redis) and reindex solr (if you are using solr). 

The commands to do this using ezlaunchpad could be: `~/ez importdata && ~/ez sfrun cache:pool:clear cache.redis && ~/ez sfrun ezplatform:reindex`.

### Configuration

Make sure to configure the backup-manager based upon [backup-manager/symfony instructions ](https://github.com/backup-manager/symfony). 

It is recommended to place the configuration options inside the `app/config/config_dev.yml` file.

**NOTE:** By default, the first database and storage option is used when not specifically stated as an argument in the command.

### Commands

- Database Export:
  - Exports the database and compresses it. You can override an export file if exists. Optional databases through configuration.
  - `bin/console db:export <filename> (optional --database) (optional --delete)`
- Database Import:
  - Imports a database file as well as clear redis cache and reindex the solr index
  - `bin/console db:import <filename> (optional --database)` 

