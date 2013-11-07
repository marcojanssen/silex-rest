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
        'like'  => 'Spray\PersistenceBundle\EntityFilter\Common\Like'
    );

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
    }

}