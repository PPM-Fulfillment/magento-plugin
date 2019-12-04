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
$ composer require ppm/magento-fulfillment
```

Do this from the application root in your server shell. The continue by
[following the directions in this
article](https://devdocs.magento.com/extensions/install/)

Finally, you can install with a manual file upload:

1. Download the extension from GitHub
2. Create a directory (in your Magento installation) of `app/code/Ppm/Fulfillment`
3. Copy contents of this repository to that directory
4. Run `php bin/magento setup:upgrade` in the installation root
5. Run `php bin/magento setup:di:compile`
6. Verify module status: `$ bin/magento module:status`
7. Clear the cache: `$ bin/magento cache:clean`

## Configuration

To use this plugin, you will need:

+ An active account with PPM Fulfillment
+ An active third-party API integration and API key
+ The PPM API URL (a default is provided)
+ Your "Owner Code" (which you'll get from PPM)
+ An active Magento store

### Step 1: Set up the plugin

First, on the side menu, select **Stores** > **All Stores**.

![](/readme-assets/01-stores-menu.png)

From there, you'll want to select the correct **Web Site**. Each row corresponds
to a store in your site. If you have multiple stores, you will need to repeat
this process for each store using a different PPM Third Party Integration API
Key each time.

![](/readme-assets/02-stores-view.png)

This will open up your site configuration. The three PPM-specific fields are:

+ PPM API Key (provided by PPM)
+ PPM Owner Code (provided by PPM)
+ PPM API URL (you'll almost certainly want to leave the default value of
  `https://portal.ppmfulfillment.com/api/External/ThirdPartyOrders`)

All three of these fields must be filled in before orders can be submitted to
PPM automatically.

![](/readme-assets/03-store-config.png)

### Step 2: Configure Products

Assuming you have the plugin set up correctly, you next need to configure
individual products. For each product you want fulfilled by PPM, you will need
to:

1. Indicate that the product is fulfilled by PPM
2. Provide a PPM product ID (provided by PPM)

![](/readme-assets/04-product-configuration.png)

We make the assumption that you will have some products fulfilled by PPM and
some products fulfilled through other channels (whatever those might be). Thus,
we ask that you explicitly indicate individual products to be fulfilled by PPM.

When a customer places an order, if that order has any PPM-fulfilled products
(identified by both having a "true" value for `Fulfilled by PPM?` and a PPM
Product SKU), those products (and their quantities) are sent along to PPM as the
final step of the order process. Any non-PPM-fulfilled products in an order will
*not* be sent to PPM.
