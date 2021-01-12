<?php


namespace App\Listener;


use App\Entity\User;
use Pd\MenuBundle\Event\PdMenuEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MenuListener
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onCreate(PdMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->setChildAttr(['class' => 'navbar-nav mr-auto']);

        $menu->addChild('main')
            ->setLabel('Главная')
            ->setRoute('main')
            ->setLinkAttr(['class' => 'nav-link'])
            ->setListAttr(['class' => 'nav-item']);

        $menu->addChild('profile_add_car')
            ->setLabel('Добавить машину')
            ->setRoute('profile_add_car')
            ->setLinkAttr(['class' => 'nav-link'])
            ->setListAttr(['class' => 'nav-item']);

        $user = $this->getUser();
        if ($user) {
            $menu->addChild('frontend_logout')
                ->setLabel('Выйти')
                ->setRoute('frontend_logout')
                ->setLinkAttr(['class' => 'nav-link'])
                ->setListAttr(['class' => 'nav-item']);
        } else {
            $menu->addChild('frontend_login')
                ->setLabel('Войти')
                ->setRoute('frontend_login')
                ->setLinkAttr(['class' => 'nav-link'])
                ->setListAttr(['class' => 'nav-item']);

            $menu->addChild('frontend_reg')
                ->setLabel('Регистрация')
                ->setRoute('frontend_reg')
                ->setLinkAttr(['class' => 'nav-link'])
                ->setListAttr(['class' => 'nav-item']);
        }
    }

    /**
     * @return User|null
     */
    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }

        return $token->getUser();
    }
}