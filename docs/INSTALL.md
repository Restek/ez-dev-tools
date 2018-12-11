# Installation

## Composer
Add the following to your composer.json and run `php composer.phar update --dev restek/ez-dev-tools` to refresh dependencies:

```json
# composer.json

"require": {
    "restek/ez-dev-tools": "^1.0",
}
```

## Symfony
Make sure to register the bundle as well. Check `app/appKernel.php`.

```
public function registerBundles()
{
    case 'dev':
      --- other dev bundles ---
      $bundles[] = new Restek\EzPlatformDevToolsBundle\EzPlatformDevToolsBundle();
    );
}
```

## Test

Check that the bundle is installed correctly. 

If you run `php bin/console` you should see the following new commands in the list: 

```
db
  db:export
  db:import
```