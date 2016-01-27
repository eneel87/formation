<?php
namespace OCFram;

use Model\MemberManager;

trait Run
{
    public function run()
    {
        $User = $this->app->user();
        $Membre = $User->getAttribute('user');
        $Router = new Router();

        if($User->isAuthenticated())
        {
            $menu = '<nav>';
                $menu.= '<ul>';
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'News', 'index').'">Accueil</a></li>';

                if($Membre->level()== MemberManager::ADMINISTRATOR)
                {
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'Administration', 'index').'">Administration</a></li>';
                }

                if($Membre->level()> MemberManager::ADMINISTRATOR)
                {
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'Administration', 'index').'">Mon Compte</a>';
                }

                    $menu.= '<li><a href="'.$Router->getUrl('Backend', 'Connexion', 'deconnexion').'">Déconnexion de '.htmlspecialchars($Membre->login()).'</a></li>';
                $menu.= '</ul>';
            $menu.= '</nav>';

            $this->page->addVar('menu', $menu);
        }
        else
        {
            $menu = '<nav>';
                $menu.= '<ul>';
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'News', 'index').'">Accueil</a></li>';
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'Connexion', 'index').'">Connexion</a></li>';
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'Connexion', 'inscription').'">Inscription</a></li>';
                $menu.= '</ul>';
            $menu.= '</nav>';

            $this->page->addVar('menu', $menu);
        }
    }
}