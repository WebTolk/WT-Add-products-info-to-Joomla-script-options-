<?php

defined('_JEXEC') || die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Jshoppingproducts\Wt_add_products_info_to_joomla_script_options\Extension\Wt_add_products_info_to_joomla_script_options;

return new class implements ServiceProviderInterface {

    public function register(Container $container): void
    {
        $container->set(
            PluginInterface::class,
            function (Container $container)
            {
                $config  = (array)PluginHelper::getPlugin('jshoppingproducts', 'wt_add_products_info_to_joomla_script_options');
                $subject = $container->get(DispatcherInterface::class);

                $app = Factory::getApplication();

                /** @var \Joomla\CMS\Plugin\CMSPlugin $plugin */
                $plugin = new Wt_add_products_info_to_joomla_script_options($subject, $config);
                $plugin->setApplication($app);

                return $plugin;
            }
        );
    }
}

?>