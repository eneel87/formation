<?php
namespace App\Frontend\Modules\Administration;

use Model\MemberManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \Entity\User;
use OCFram\Router;

class AdministrationController extends BackController
{
    use \OCFram\Run;

    public function executeIndex(HTTPRequest$request)
    {
        $User = $this->app->user()->getAttribute('user');

        if(!$User)
        {
            $this->app->user()->setFlash('Vous devez être connecté pour accéder à votre compte');
        }

        $Router = new Router();

        $links = '<ul>';

        if($User->level()==MemberManager::ADMINISTRATOR)
        {
            $links.= '<li><a href="'.$Router->getUrl('Backend', 'Member', 'index').'">Gestion des utilisateurs</a></li>';
        }

        if($User->level()<=MemberManager::WRITER)
        {
            $links.= '<li><a href="'.$Router->getUrl('Backend', 'News', 'index').'">Gestion des news</a></li>';
        }

        if($User->level()>MemberManager::ADMINISTRATOR)
        {
            $links.= '<li><a href="'.$Router->getUrl('Frontend', 'Connexion', 'unsubscribe').'">Formulaire de désincription</a></li>';
        }

        $links.= '<li><a href="#">Modifier mes informations <em>(Work in Progress)</em></a></li>';
        $links.= '</ul>';

        $this->run();
        $this->page->addVar('title', 'Administration');
        $this->page->addVar('links', $links);
        $this->page->addVar('router', new Router());
    }
}