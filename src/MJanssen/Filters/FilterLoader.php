<?php
/**
 * Created by JetBrains PhpStorm.
 * User: roberto
 * Date: 9/20/13
 * Time: 11:55 AM
 * To change this template use File | Settings | File Templates.
 */

namespace MJanssen\Filters;

use Zend\Loader\PluginClassLoader;

class FilterLoader extends PluginClassLoader
{
    protected $plugins = array(
        'like'  => 'MJanssen\Filters\LikeFilter'
    );

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

}