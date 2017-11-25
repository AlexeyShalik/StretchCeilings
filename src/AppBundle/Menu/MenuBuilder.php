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
        $menu = $this->commonMenu($factory);
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
        $menu = $this->commonMenu($factory);
        return $menu;
    }

    private function commonMenu(FactoryInterface $factory)
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
                'Управление заказами звонков',
                array(
                    'route' => 'orders_to_call')
            );
        return $menu;
    }

    public function frontMenuIndex(FactoryInterface $factory)
    {
        $menu = $factory->createItem('Home', array('childrenAttributes' => array('class' => 'nav navbar-nav')));
        $menu
            ->addChild(
                'Главная',
                array(
                    'uri' => '#')
            );
        $menu
            ->addChild(
                'Каталог',
                array(
                    'uri' => '#catalog')
            );
        $menu
            ->addChild(
                'Наши работы',
                array(
                    'route' => 'our-works')
            );
        $menu
            ->addChild(
                'Калькулятор цен',
                array(
                    'uri' => '#')
            );
        $menu
            ->addChild(
                'Заказать звонок',
                array(
                    'uri' => '#',
                    'attributes' => array('class' => 'call-me'))
            );
        $menu
            ->addChild(
                'О нас',
                array(
                    'uri' => '#about-us')
            );
        $menu
            ->addChild(
                'Наши магазины',
                array(
                    'uri' => '#map')
            );
        $menu
            ->addChild(
                'Контакты',
                array(
                    'uri' => '#contact')
            );

        return $menu;
    }

    public function frontMenuOurWorks(FactoryInterface $factory)
    {
        $menu = $factory->createItem('Home', array('childrenAttributes' => array('class' => 'nav navbar-nav')));
        $menu
            ->addChild(
                'Главная',
                array(
                    'route' => 'index')
            );
        $uri = $menu->getChild('Главная')->getUri();
        $menu
            ->addChild(
                'Каталог',
                array(
                    'uri' => $uri.'#catalog',)
            );
        $menu
            ->addChild(
                'Наши работы',
                array(
                    'uri' => '#our-works')
            );
        $menu
            ->addChild(
                'Калькулятор цен',
                array(
                    'uri' => $uri.'#')
            );
        $menu
            ->addChild(
                'Заказать звонок',
                array(
                    'uri' => '#',
                    'attributes' => array('class' => 'call-me'))
            );
        $menu
            ->addChild(
                'О нас',
                array(
                    'uri' => $uri.'#about-us')
            );
        $menu
            ->addChild(
                'Наши магазины',
                array(
                    'uri' => $uri.'#map')
            );
        $menu
            ->addChild(
                'Контакты',
                array(
                    'uri' => $uri.'#contact')
            );

        return $menu;
    }
}