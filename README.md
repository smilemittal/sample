## Deployment Conventions

### Stage Server

To deploy your branch to server please follow following steps:

- cd to project root directory
- `php vendor/bin/envoy run deploy --branch=my-feature-branch` you can use `--branch` option to specify your deployment branch.

## Custom Seeder Implementation
#### Goal:
Run a seeder file only once (per instance), same as how laravel migration works.

#### Components:

##### Custom Seeder File Generator
`php artisan xme:custom_seeder` (handled by custom console command named `CreateCustomSeederFile.php`)

##### Custom Seeder File Runner

`php artisan db:seed` (Same as how seeding works normally, handled by `DatabaseSeeder.php` file)