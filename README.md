Donoussa
========

Donoussa is a minimalistic PHP MVC framework, simple and easy to use. It combines FLAT PHP code writing freedom with basic MVC features. It bears the name of the small Greek island [Donoussa](http://en.wikipedia.org/wiki/Donoussa)

It could be useful in small/medium-size projects. PHP 5.3.0 or newer is recommended. Tested with PHP 7

Copyright Christos Pontikis http://www.pontikis.net

License [MIT](https://raw.github.com/pontikis/donoussa/master/MIT_LICENSE)

At a glance
-------------
* copy index.dist.php to /index.php (front controller)
* copy .htaccess.dist to /.htaccess (mod_rewrite required)
* copy settings.dist.php to conf/settings.php and configure
* copy init.dist.php to conf/init.php (configure if needed)
* add donoussa tables in database (page_properties, page_dependencies, page_url), see schema_mysql.sql
* start coding

MVC
---

Compare | Advanced MVC Frameworks | Donoussa
------- | ----------------------- | --------
**Front controller** | YES Index.php is the single point of entry for all requests (using mod_rewrite) | YES Index.php or any other (using mod_rewrite)
**Number of files** | Many | 7
**Friendly URLs** | YES | YES (without restrictions)


Documentation
-------------
Coming soon
