[![Version](https://img.shields.io/badge/Version-2.0.0-blue.svg)](https://web-tolk.ru/en/dev/joomla-plugins/wt-add-products-info-to-joomla-script-options.html?utm_source=github) [![Status](https://img.shields.io/badge/Status-stable-green.svg)]() [![JoomlaVersion](https://img.shields.io/badge/Joomla-4.x-orange.svg)]() [![JoomlaVersion](https://img.shields.io/badge/Joomla-5.x-orange.svg)]() [![JoomShoppingVersion](https://img.shields.io/badge/JoomShopping-3.8.x-important.svg)]() [![JoomShoppingVersion](https://img.shields.io/badge/JoomShopping-5.x-important.svg)]() [![DocumentationRus](https://img.shields.io/badge/Documentation-rus-blue.svg)](https://web-tolk.ru/dev/joomla-plugins/wt-add-products-info-to-joomla-script-options.html?utm_source=github) [![DocumentationEng](https://img.shields.io/badge/Documentation-eng-blueviolet.svg)](https://web-tolk.ru/en/dev/joomla-plugins/wt-add-products-info-to-joomla-script-options.html?utm_source=github)
# WT Add products info to Joomla script options
Adds JoomShopping product info to Joomla script options. You can access it from javascript via `Joomla.getOptions('jshop_products_details').` Joomla 4 and Joomla 5 (and above) support. **Warning! Joomla 3 is no longer supported!**

# What is it and why?
## What is it?
To create feedback forms in the online store on Joomla JoomShopping, such as:
- quick order of goods
- ask a question about the product
- request for a discount on an ite
- wholesale price request
- And so on - for all Joomla feedback forms, where you need to specify information about the product.

This plugin is necessary for those who use the professional plugin feedback form - [Radical Form on JED](https://extensions.joomla.org/extension/radicalform/), [Radical Form on GitHub](https://github.com/Delo-Design/radicalform) - in their work. 
[Tutorial 1 (Russiain language)](https://web-tolk.ru/blog/razrabotka-form-obratnoj-svyazi-dlya-magazinov-na-joomla-3.html?utm_source=github)
[Tutorial 2 (Russiain language)](https://web-tolk.ru/blog/integratsiya-form-obratnoj-svyazi-i-bitriks24-na-sajte-joomla.html?utm_source=github)

# Why it?
In order to make a beautiful and informative lead magnet, a feedback form that specifies the specific product in question.
Webmasters often get data for forms directly from the HTML layout of the site. In the case of a design change, it is easy to damage the structure on which the JS script relies and the feedback form will stop receiving data about the product.
This data should not depend on the design of the site. In Joomla there is a great native way to transfer data from PHP to JavaScript using a JSON object and receive it.
# Getting data about JoomShopping products for the feedback form

`Joomla.getOptions('your_json_object_with_data_from_php_here');`

This way, you can get data from a JSON object. The product_id variable is read in advance from the button that the site visitor clicks on.
```
let jshop_products_details = Joomla.getOptions('jshop_products_details');
let product_name = jshop_products_details[product_id]['product_name'];
let product_price = jshop_products_details[product_id]['price'];
let product_image_url = jshop_products_details[product_id]['product_image_url'];
let product_ean = jshop_products_details[product_id]['ean'];
```

The plugin works in the product category, the list of products of the manufacturer, the single product view. The structure of the json array is the same everywhere - you don't have to write different code for different pages.
