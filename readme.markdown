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

![](readme-assets/01-stores-menu.png)

From there, you'll want to select the correct **Web Site**. Each row corresponds
to a store in your site. If you have multiple stores, you will need to repeat
this process for each store using a different PPM Third Party Integration API
Key each time.

![](readme-assets/02-stores-view.png)

This will open up your site configuration. The three PPM-specific fields are:

+ PPM API Key (provided by PPM)
+ PPM Owner Code (provided by PPM)
+ PPM API URL (you'll almost certainly want to leave the default value of
  `https://portal.ppmfulfillment.com/api/External/ThirdPartyOrders`)

All three of these fields must be filled in before orders can be submitted to
PPM automatically.

![](readme-assets/03-store-config.png)

### Step 2: Configure Products

Assuming you have the plugin set up correctly, you next need to configure
individual products. For each product you want fulfilled by PPM, you will need
to:

1. Indicate that the product is fulfilled by PPM
2. Provide a PPM product ID (provided by PPM)

![](readme-assets/04-product-configuration.png)

We make the assumption that you will have some products fulfilled by PPM and
some products fulfilled through other channels (whatever those might be). Thus,
we ask that you explicitly indicate individual products to be fulfilled by PPM.

When a customer places an order, if that order has any PPM-fulfilled products
(identified by both having a "true" value for `Fulfilled by PPM?` and a PPM
Product SKU), those products (and their quantities) are sent along to PPM as the
final step of the order process. Any non-PPM-fulfilled products in an order will
*not* be sent to PPM.

### Step 3: PPM Fulfillment Request From Magento(automated)

When a new order is created, this extention will iterate through the catalog products included in the order and build a list of catalog products to be fulfilled by PPM. A catalog item must have a `PPM Product ID` value and `Fulfilled by PPM` must be set to `true` or this catalog product will not be included in the request to PPM.

At this point, the `Has PPM Shipments?` column on the orders grid view, should show `Yes`. Please note, this will show `Yes` even before Shipment records have been created by PPM.  This column is turned on by default with installation of this extension, but can be hidden from the view via the `columns` checklist.

![](readme-assets/05-orders-grid.png)

### Step 4: PPM Fulfillment Updates Magento Admin(automated)

Once PPM has fulfilled a Shipment with the requested contents, an update will be sent to Magento that does the following:

1. Create a new Shipment Record
2. Create a new Shipment Tracking Record
2. Create one new PPM Shipment Detail record detailing quantity, lot number, and serial number for each item included in the Shipment.

### Step 5: Shipment Information Review

Once a Shipment record has been created, it will appear under your Shipments grid. The column `Fulfilled by PPM?` will show `Yes` if a Shipment was created by PPM Fulfillment. This column is turned on by default with installation of this extension, but can be hidden from the view via the `columns` checklist.

![](readme-assets/06-shipments-grid.png)

The Shipments view page will provide information regarding `Lot Number`, `Serial Number`, and `Quantity` regarding a particular shipment.

![](readme-assets/07-shipments-view.png)
