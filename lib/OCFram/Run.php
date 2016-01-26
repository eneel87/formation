<?php
namespace OCFram;

trait Run
{
    public function run()
    {
        $User = $this->app->user();
        $Membre = $User->getAttribute('admin');
        $Router = new Router();

        if($User->isAuthenticated())
        {
            $menu = '<nav>';
                $menu.= '<ul>';
                    $menu.= '<li><a href="'.$Router->getUrl('Frontend', 'News', 'index').'">Accueil</a></li>';

                if($Membre->level()==1)
                {
                    $menu.= '<li><a href="'.$Router->getUrl('Backend', 'Member', 'index').'">Gestion des utilisateurs</a></li>';
                }

                    $menu.= '<li><a href="'.$Router->getUrl('Backend', 'News', 'index').'">Gestion des news</a></li>';
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
                    $menu.= '<li><a href="'.$Router->getUrl('Backend', 'Administration', 'index').'">Connexion</a></li>';
                $menu.= '</ul>';
            $menu.= '</nav>';

            $this->page->addVar('menu', $menu);
        }
    }
}