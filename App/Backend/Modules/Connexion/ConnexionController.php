<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\User;

class ConnexionController extends BackController
{
 /* public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Connexion');
    
    if ($request->postExists('login'))
    {
      $login = $request->postData('login');
      $password = $request->postData('password');
      
      if ($login == $this->app->config()->get('login') && $password == $this->app->config()->get('pass'))
      {
        $this->app->user()->setAuthenticated(true);
        $this->app->httpResponse()->redirect('.');
      }
      else
      {
        $this->app->user()->setFlash('Le pseudo ou le mot de passe est incorrect.');
      }
    }
  }*/

  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Connexion');

    if ($request->postExists('login'))
    {

      $user = new User(array('login' => $request->postData('login'), 'password' => $request->postData('password')));

      $manager = $this->managers->getManagerOf('User');

      if($manager->matchUser($user))
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