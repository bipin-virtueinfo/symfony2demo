<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace Admin\CommonBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Users', array('route' => 'user_list'));
        $menu->addChild('Category', array('route' => 'category_list'));
        return $menu;
    }
}
