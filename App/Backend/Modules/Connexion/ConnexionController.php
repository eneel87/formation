<?php
namespace App\Backend\Modules\Connexion;

use Model\MemberManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;



class ConnexionController extends BackController
{
  use \OCFram\Run;

  const LEVEL_AUTHORISATION = 2;

  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Connexion');
    $this->run();

    if ($request->postExists('login') && $request->postExists('password'))
    {
      /** @var MemberManager $MemberManager */
      $MemberManager = $this->managers->getManagerOf('Member');

      $Member = $MemberManager->getMemberUsingLoginAndPassword($request->postData('login'), $request->postData('password'));

      if($Member && $Member->level()<= self::LEVEL_AUTHORISATION)
      {
        $this->app->user()->setAuthenticated(true);
        $this->app->user()->setAttribute('user', $Member);
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
    $this->app->user()->unsetAttribute('user');
    $this->app->user()->setFlash('Vous avez été correctement déconnecté.');
    $this->app->httpResponse()->redirect('/');

  }
}