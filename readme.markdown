# PPM Magento Plugin

This plugin lets merchants with Magento online stores have orders automatically
be sent over to [PPM Fulfillment](http://home.ppmfulfillment.com/).

It is only for Magento users who also have accounts with PPM.

Here is how it works:

1. A customer places an order with one or more items.
2. At the time the order is placed, we see if any of those items are fulfilled
   by PPM
3. The items in that order fulfilled by PPM are added to a shipment (collection
   of packages) and sent to PPM
4. PPM posts back order updates (in the form of tracking numbers) as they come
   in

## Installation

The best way to install this plugin is through the Magento marketplace (PENDING)

You can install using composer as well:

```
$ composer require ppm/ppm-magento-module
```

Do this from the application root in your server shell. The continue by
[following the directions in this
article](https://devdocs.magento.com/extensions/install/)

Finally, you can install with a manual file upload:

1. Download the extension from GitHub
2. Create a directory (in your Magento installation) of `app/code/Ppm/`
3. Copy contents of this repository to that directory
4. Run `php bin/magento setup:upgrade` in the installation root
5. Run `php bin/magento setup:di:compile`
6. Verify module status: `$ bin/magento module:status`
7. Clear the cache: `$ bin/magento cache:clean`

## Configuration

To use this plugin, you will need:

+ An active account with PPM Fulfillment
+ An active third-party API integration and API key
+ Your "Owner Code" (which you'll get from PPM)
+ An active Magento store
