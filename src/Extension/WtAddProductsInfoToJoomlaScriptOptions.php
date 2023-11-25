<?php
/**
 * @package    WT Add products info to Joomla script options
 * @author     Sergey Tolkachyov info@web-tolk.ru https://web-tolk.ru
 * @copyright  Copyright (C) 2021 Sergey Tolkachyov. All rights reserved.
 * @license    GNU General Public License version 3 or later
 */

namespace WT\Plugin\Jshoppingproducts\WtAddProductsInfoToJoomlaScriptOptions\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Version;
use Joomla\Event\SubscriberInterface;

/*
*	Добавляем опции для скрипта форм обратной связи с данными товара
*	Пример-мануал https://web-tolk.ru/blog/integratsiya-form-obratnoj-svyazi-i-bitriks24-na-sajte-joomla.html
*	https://hika.su/rasshireniya/radical-form
*/

/**
 * The base class of plugin
 *
 * @package  WT Add products info to Joomla script options
 * @since    1.0
 */
class WtAddProductsInfoToJoomlaScriptOptions extends CMSPlugin implements SubscriberInterface
{

	protected $autoloadLanguage = true;

    protected $allowLegacyListeners = false;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   4.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onAfterDisplayProduct' => 'onAfterDisplayProduct',
            'onBeforeDisplayProductListView' => 'onBeforeDisplayProductListView',
            'onBeforeDisplaywtjshoppingfavoritesView' => 'onBeforeDisplaywtjshoppingfavoritesView'
        ];
    }

    /**
	 * Method to add products info to Joomla scrtipt options to access its via javascript Joomla.getOptions('jshop_products_details')
	 *
	 * @param   object  &$product  JoomShopping product
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterDisplayProduct($event): void
    {
        $product = $event->getArgument(0);
		$jversion = new Version();
        $jshopConfig = \JSFactory::getConfig();

		$product_info = array();
		if ($this->params->get('product_view_show_product_name',1) == 1) {
			$product_info['product_name'] =  $product->name;
		}
		if ($this->params->get('product_view_show_product_image',1) == 1) {
			$product_info['product_image_url'] =  $jshopConfig->image_product_live_path.'/'.$product->image;
		}
		if ($this->params->get('product_view_show_product_ean',1) == 1) {
			$product_info['ean'] =  $product->product_ean;
		}
		if ($this->params->get('product_view_show_product_manufacturer_code',1) == 1) {
			$product_info['manufacturer_code'] =  $product->manufacturer_code;
		}
		if ($this->params->get('product_view_show_product_quantity',1) == 1) {
			$product_info['quantity'] =  $product->product_quantity;
		}
		if ($this->params->get('product_view_show_product_old_price',1) == 1) {
			if (version_compare($jversion->getShortVersion(), '4.0', '<'))
			{
				$product_info['old_price'] = formatprice($product->product_old_price);
			} else {
				$product_info['old_price'] = \JSHelper::formatprice($product->product_old_price);
			}
		}

		if ($this->params->get('product_view_show_product_price',1) == 1 && $this->params->get('product_view_show_product_zero_price',0) == 1) {
			if (version_compare($jversion->getShortVersion(), '4.0', '<'))
			{
				$product_info['price'] = formatprice($product->product_price);
			} else {
				$product_info['price'] = \JSHelper::formatprice($product->product_price);
			}
		}


		if ($this->params->get('product_view_show_product_min_price',1) == 1) {
			if (version_compare($jversion->getShortVersion(), '4.0', '<'))
			{
				$product_info['min_price'] = formatprice($product->min_price);
			} else {
				$product_info['min_price'] = \JSHelper::formatprice($product->min_price);
			}

		}
		if($this->params->get('product_view_show_product_delivery_time',1) == 1){
			$product_info['delivery_time'] =  $product->delivery_time;
		}

		$product_array = array(
			$product->product_id => $product_info
		);

		$doc = Factory::getDocument();
		$doc->addScriptOptions('jshop_products_details',$product_array);

	}

	public function onBeforeDisplayProductListView($event): void
    {
        $view = $event->getArgument(0);
        $productlist = $event->getArgument(1);
		$jversion = new Version();

		$product_info = array();
		if (count((array)$productlist->products) > 0) {
            foreach($productlist->products as $product) {

                if ($this->params->get('category_view_show_product_name',1) == 1) {
                    $product_info[$product->product_id]['product_name'] =  $product->name;
                }
                if ($this->params->get('category_view_show_product_image',1) == 1) {
                    $product_info[$product->product_id]['product_image_url'] =  $product->image;
                }
                if ($this->params->get('category_view_show_product_ean',1) == 1) {
                    $product_info[$product->product_id]['ean'] =  $product->product_ean;
                }
                if ($this->params->get('category_view_show_product_manufacturer_code',1) == 1) {
                    $product_info[$product->product_id]['manufacturer_code'] =  $product->manufacturer_code;
                }
                if ($this->params->get('category_view_show_product_quantity',1) == 1) {
                    $product_info[$product->product_id]['quantity'] =  $product->product_quantity;
                }
                if ($this->params->get('category_view_show_product_old_price',1) == 1) {
                    if (version_compare($jversion->getShortVersion(), '4.0', '<'))
                    {
                        $product_info[$product->product_id]['old_price'] = formatprice($product->product_old_price);
                    } else {
                        $product_info[$product->product_id]['old_price'] = \JSHelper::formatprice($product->product_old_price);
                    }
                }

                if ($this->params->get('category_view_show_product_price',1) == 1 && $this->params->get('category_view_show_product_zero_price',0) == 1) {
                    if (version_compare($jversion->getShortVersion(), '4.0', '<'))
                    {
                        $product_info[$product->product_id]['price'] = formatprice($product->product_price);
                    } else {
                        $product_info[$product->product_id]['price'] = \JSHelper::formatprice($product->product_price);
                    }
                }

                if ($this->params->get('category_view_show_product_min_price',1) == 1) {
                    if (version_compare($jversion->getShortVersion(), '4.0', '<'))
                    {
                        $product_info[$product->product_id]['min_price'] = formatprice($product->min_price);
                    } else {
                        $product_info[$product->product_id]['min_price'] = \JSHelper::formatprice($product->min_price);
                    }
                }
                if ($this->params->get('category_view_show_product_delivery_time',1) == 1) {
                    $product_info[$product->product_id]['delivery_time'] =  $product->delivery_time;
                }
            }
            Factory::getDocument()->addScriptOptions('jshop_products_details',$product_info);
		}//if $productlist->products > 0
	}

	/**
	 * Обработка избранных товаров WT JoomShopping Favorites
	 * @param $view object Объект со списком товаров, общим количеством и т.д.
	 *
	 *
	 * @since 1.0.1
	 */
	public function onBeforeDisplaywtjshoppingfavoritesView($event): void
    {
        $view = $event->getArgument(0);
		$jversion = new Version();
		$product_info = array();
		if(count((array)$view->rows) > 0) {
			foreach($view->rows as $product) {

				if ($this->params->get('category_view_show_product_name',1) == 1) {
					$product_info[$product->product_id]['product_name'] =  $product->name;
				}
				if ($this->params->get('category_view_show_product_image',1) == 1) {
					$product_info[$product->product_id]['product_image_url'] =  $product->image;
				}
				if ($this->params->get('category_view_show_product_ean',1) == 1) {
					$product_info[$product->product_id]['ean'] =  $product->product_ean;
				}
				if ($this->params->get('category_view_show_product_manufacturer_code',1) == 1) {
					$product_info[$product->product_id]['manufacturer_code'] =  $product->manufacturer_code;
				}
				if ($this->params->get('category_view_show_product_quantity',1) == 1) {
					$product_info[$product->product_id]['quantity'] =  $product->product_quantity;
				}
				if ($this->params->get('category_view_show_product_old_price',1) == 1) {
					if (version_compare($jversion->getShortVersion(), '4.0', '<'))
					{
						$product_info[$product->product_id]['old_price'] = formatprice($product->product_old_price);
					} else {
						$product_info[$product->product_id]['old_price'] = \JSHelper::formatprice($product->product_old_price);
					}
				}

				if ($this->params->get('category_view_show_product_price',1) == 1 && $this->params->get('category_view_show_product_zero_price',0) == 1) {
					if (version_compare($jversion->getShortVersion(), '4.0', '<'))
					{
						$product_info[$product->product_id]['price'] = formatprice($product->product_price);
					} else {
						$product_info[$product->product_id]['price'] = \JSHelper::formatprice($product->product_price);
					}
				}

				if ($this->params->get('category_view_show_product_min_price',1) == 1) {
					if (version_compare($jversion->getShortVersion(), '4.0', '<'))
					{
						$product_info[$product->product_id]['min_price'] = formatprice($product->min_price);
					} else {
						$product_info[$product->product_id]['min_price'] = \JSHelper::formatprice($product->min_price);
					}
				}
				if ($this->params->get('category_view_show_product_delivery_time',1) == 1) {
					$product_info[$product->product_id]['delivery_time'] =  $product->delivery_time;
				}
			}
			Factory::getDocument()->addScriptOptions('jshop_products_details',$product_info);
		}
	}
}
