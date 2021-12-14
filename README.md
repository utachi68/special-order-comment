# Advox SpecialOrderComment by Dũng Trịnh

This module allows you to add a predefined comment to the order

## Requirements
* Magento Community Edition 2.4.x with a default setup cronjob by running this command
~~~
php bin/magento cron:install
~~~

## Installation
~~~ xml
composer require utachi68/special-order-comment
php bin/magento module:enable Advox_SpecialOrderComment
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
~~~

## Configuration and Usage

* Open the Magento Admin panel and view the module configuration:
    * Stores > Configuration > Advox > SpecialOrderComment
    * In this section you can enable or disable this module, or change the default comment text.
    * Save config.
* Clear Magento cache.
* After customer place an order, the order ID will be added to the queue. A consumer run by cronjob will check order ID and add a comment to
the order
* If you dont have a cronjob setup, you can run the consummer by command
~~~
php bin/magento queue:consumers:start order_comment.add
~~~
