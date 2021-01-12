<?php


namespace App\Menu;


use Pd\MenuBundle\Builder\ItemInterface;
use Pd\MenuBundle\Builder\Menu;

/**
 * Class FrontMenu
 * @package App\Menu
 *
 * @link https://github.com/appaydin/pd-menu
 */
class FrontMenu extends Menu
{
    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createMenu(array $options = []): ItemInterface
    {
        return $this->createRoot('settings_menu', true);
    }
}
