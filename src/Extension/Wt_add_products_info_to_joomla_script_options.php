<?php
/**
 * @package       WT Add products info to Joomla script options
 * @version       2.0.1
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2023 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Joomla\Plugin\Jshoppingproducts\Wt_add_products_info_to_joomla_script_options\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
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
class Wt_add_products_info_to_joomla_script_options extends CMSPlugin implements SubscriberInterface
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
	 * Method to add products info to Joomla script options to access it's via javascript Joomla.getOptions('jshop_products_details')
	 *
	 * @param  object  $event  JoomShopping after display product event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterDisplayProduct($event): void
    {
        /* @var $product object JoomShopping product */
        $product = $event->getArgument(0);
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
			$product_info['old_price'] = \JSHelper::formatprice($product->product_old_price);
		}

		if ($this->params->get('product_view_show_product_price',1) == 1 && $this->params->get('product_view_show_product_zero_price',0) == 1) {
			$product_info['price'] = \JSHelper::formatprice($product->product_price);
		}


		if ($this->params->get('product_view_show_product_min_price',1) == 1) {
			$product_info['min_price'] = \JSHelper::formatprice($product->min_price);
		}
		if($this->params->get('product_view_show_product_delivery_time',1) == 1){
			$product_info['delivery_time'] =  $product->delivery_time;
		}

		$product_array = array(
			$product->product_id => $product_info
		);

        /* @var $doc Joomla\CMS\Document\Document */
		$doc = $this->getApplication()->getDocument();
		$doc->addScriptOptions('jshop_products_details', $product_array);
	}

    /**
     * @param object $event JoomShopping before display product list view event
     *
     * @return void
     *
     * @since 1.0
     * */
	public function onBeforeDisplayProductListView($event): void
    {
        $view = $event->getArgument(0);
        /* @var $productlist object JoomShopping product list view */
        $productlist = $event->getArgument(1);

		$product_info = [];
		if (property_exists($productlist,'products') && count((array)$productlist->products) > 0) {
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
                    $product_info[$product->product_id]['old_price'] = \JSHelper::formatprice($product->product_old_price);
                }

                if ($this->params->get('category_view_show_product_price',1) == 1 && $this->params->get('category_view_show_product_zero_price',0) == 1) {
                    $product_info[$product->product_id]['price'] = \JSHelper::formatprice($product->product_price);
                }

                if ($this->params->get('category_view_show_product_min_price',1) == 1) {
                    $product_info[$product->product_id]['min_price'] = \JSHelper::formatprice($product->min_price);
                }
                if ($this->params->get('category_view_show_product_delivery_time',1) == 1) {
                    $product_info[$product->product_id]['delivery_time'] =  $product->delivery_time;
                }
            }

            $this->getApplication()->getDocument()->addScriptOptions('jshop_products_details', $product_info);
		}
	}

	/**
	 * Обработка избранных товаров WT JoomShopping Favorites
	 * @param $event object событие отображения списка товаров
	 *
	 * @return void
	 *
	 * @since 1.0.1
	 */
	public function onBeforeDisplaywtjshoppingfavoritesView($event): void
    {
        /* @var $view object Объект со списком товаров, общим количеством и т.д. */
        $view = $event->getArgument(0);

		$product_info = [];
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
					$product_info[$product->product_id]['old_price'] = \JSHelper::formatprice($product->product_old_price);
				}

				if ($this->params->get('category_view_show_product_price',1) == 1 && $this->params->get('category_view_show_product_zero_price',0) == 1) {
					$product_info[$product->product_id]['price'] = \JSHelper::formatprice($product->product_price);
				}

				if ($this->params->get('category_view_show_product_min_price',1) == 1) {
					$product_info[$product->product_id]['min_price'] = \JSHelper::formatprice($product->min_price);
				}
				if ($this->params->get('category_view_show_product_delivery_time',1) == 1) {
					$product_info[$product->product_id]['delivery_time'] =  $product->delivery_time;
				}
			}

			$this->getApplication()->getDocument()->addScriptOptions('jshop_products_details', $product_info);
		}
	}
}
