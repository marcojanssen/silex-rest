<?php
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