<?php
namespace AppBundle\Menu;
use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function adminMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Home', array('childrenAttributes' => array('class' => 'nav nav-sidebar')));
        $menu
        ->addChild(
            'Статистика',
            array(
                'route' => 'dashboard')
        );

        $menu
            ->addChild(
                'Управление пользователями',
                array(
                    'route' => 'user_manager')
            );
        return $menu;
    }

    public function managerMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('Home', array('childrenAttributes' => array('class' => 'nav nav-sidebar')));
        $menu
            ->addChild(
                'Статистика',
                array(
                    'route' => 'dashboard',
                    'attributes' => array('class' => 'active'))
            );
        return $menu;
    }
}