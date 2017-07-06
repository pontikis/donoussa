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

MVC comparison
--------------

Compare     | Advanced MVC Frameworks | Donoussa
----------- | ----------------------- | -------------
**Front controller** | YES Index.php is the single point of entry for all requests (using mod_rewrite) | YES Index.php or any other (using mod_rewrite)
**Number of files** | Many | 7
**Friendly URLs** | YES | YES (without restrictions)
**URL structure** | according standard MVC patterns, e.g. http://domain/controller/action/id | Any URL structure e.g. http://domain/any_url
**Code Directory structure** | Usually /models /views /controllers /config | Any directory structure (recommended /conf)
**Controllers** | Front controller will establish a "loader" object to “translate” the requested URL into an instance of the relevant controller class. Controllers, models and views are individual files organized in relevant folders of the same name. | Front controller will include the appropriate “model” and “view” using Dynamic Lookup Invocation from database (or memcached, json, xml etc)
**Views** | Views can be either stand-alone or use a template | Views are HTML files with embedded PHP
**Database abstraction layer** | YES | YES https://github.com/pontikis/dacapo (MySQLi, PostgreSQL)
**User/Roles functionality** | YES | YES https://github.com/pontikis/ithaca
**AJAX CSRF protection** | Usually | YES
**Class Autoloading** | YES | NO 
**Use a Registry object** | YES | NO 
**Multilanguage support** | Usually | YES (using gettext and php-intl)
**Manage assets (CSS, JS)** | Usually (using Composer https://getcomposer.org/) | Assets are managed internally. Minify and bundle option available (Javascript Minifier built in PHP https://github.com/tedivm/JShrink and CssMin minfier http://code.google.com/p/cssmin/). Force reload JS and CSS assets using pseudo query string.
**Prevent direct URL download** | ? |YES
**Integrated unit testing support** | Usually | NO

External classes
----------------

* Dacapo (Simple PHP database wrapper) https://github.com/pontikis/dacapo
* Javascript Minifier built in PHP v1.1.0 https://github.com/tedivm/JShrink (optional)
* CssMin minfier v3.0.1 http://code.google.com/p/cssmin/ (optional)

Documentation
-------------

See ``docs/doxygen/html`` for html documentation. 