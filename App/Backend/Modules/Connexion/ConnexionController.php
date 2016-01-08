<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\User;



class ConnexionController extends BackController
{

  const LEVEL_AUTHORISATION = 2;

  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Connexion');

    if ($request->postExists('login'))
    {
      $manager = $this->managers->getManagerOf('User');

      $user = $manager->getUserUsingLoginAndPassword($request->postData('login'), $request->postData('password'));

      if($user->level()<= self::LEVEL_AUTHORISATION)
      {
        $this->app->user()->setAuthenticated(true);
        $this->app->user()->setAttribute('admin', $user);
        $this->app->httpResponse()->redirect('.');
      }
      else
      {
        $this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
      }
    }
  }

  public  function executeDeconnexion(HTTPRequest $request){

    $this->app->user()->setAuthenticated(false);
    $this->app->user()->unsetAttribute('admin');
    $this->app->user()->setFlash('Vous avez été correctement déconnecté.');
    $this->app->httpResponse()->redirect('/');

  }
}