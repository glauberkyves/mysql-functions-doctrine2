MySQL functions Doctrine2
====================

This library provides you MySQL functions for Doctrine2.

At the moment are supported

 - CONCAT_WS
 - GEO
 - DATE
 - YEAR
 - MONTH
 - DAY

Feel free to fork and add other functions.

## Installation

### Get the bundle

Add this in your composer.json

```json
{
	"require": {
		"glauberkyves/mysql-functions-doctrine2": "1.*"
	}
}
```

and then run

```sh
php composer.phar update
```
or 
```sh
composer update
```
if you installed composer globally.

### Add the classes to your configuration

```php
$config = new \Doctrine\ORM\Configuration();
$config->addCustomStringFunction('concat_ws', 'GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlRand');

$em = EntityManager::create($dbParams, $config);
```
You can of course pick just the functions you need.

### Use with Symfony2
If you install the library in a Symfony2 application, you can add this in your config.yml

```yaml
# app/config/config.yml
doctrine:
    orm:
        # ...
        entity_managers:
            default:
                # ...
                dql:
                    string_functions:
                        concat_ws: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlConcatWs
                        month: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlMonth
                    numeric_functions:
                        geo: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlGeo
                    datetime_functions:
                        date: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlDate
                        day: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlDay
                        year: GlauberKyves\MysqlDoctrineFunctions\DQL\MysqlYear
```

### Usage
You can now use the functions in your DQL Query

```php
$query = 'SELECT CONCAT_WS('string', 'string') FROM ...';
$em->createQuery($query);

$query = 'SELECT GEO(-15.5656, -47.5656, t.latitude, t.longitude) FROM tb_address t ...';
$em->createQuery($query);
```
