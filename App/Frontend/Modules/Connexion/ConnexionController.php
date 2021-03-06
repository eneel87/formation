<?php
namespace App\Frontend\Modules\Connexion;

use Entity\Alertc;
use Entity\Alertd;
use Entity\Member;
use FormBuilder\ConnexionFormBuilder;
use \FormBuilder\MemberFormBuilder;
use \OCFram\BackController;
use OCFram\FormHandler;
use \OCFram\HTTPRequest;
use OCFram\Router;

class ConnexionController extends BackController
{
    use \OCFram\Run;

    public function executeIndex(HTTPRequest $Request)
    {
        $this->page->addVar('title', 'Inscription');
        $this->run();

        if($Request->method()=='POST')
        {
            $Member = new Member();
            $Member->setPassword($Request->postData('password'));
            $Member->setLogin($Request->postData('login'));
        }
        else
        {
            $Member = new Member();
        }

        $MemberManager = $this->managers->getManagerOf('Member');

        $ConnexionFormBuilder = new ConnexionFormBuilder($Member);
        $ConnexionFormBuilder->build();

        $Form = $ConnexionFormBuilder->form();

        if($Request->method()=='POST' && $Form->isValid())
        {
            $Member = $MemberManager->getMemberUsingLoginAndPassword($Member->login(), $Member->password());

            if(!$Member)
            {
                $this->app->user()->setFlash('<span style="color:red">Mot de passe et/ou Login incorrects</span>');
                $this->page->addVar('form', $Form->createView());
            }

            $this->app->user()->setAuthenticated(true);
            $this->app->user()->setAttribute('user', $Member);
            $this->app->httpResponse()->redirect('.');
        }

        $this->page->addVar('form', $Form->createView());
    }

    public function executeInscription(HTTPRequest $Request)
    {
        $this->page->addVar('title', 'Inscription');
        $this->run();

        if($Request->method()=='POST')
        {
            $Member = new Member();
            $Member->setLogin($Request->postData('login'));
            $Member->setPassword($Request->postData('password'));
            $Member->setPassword_confirmation($Request->postData('password_confirmation'));
            $Member->setEmail($Request->postData('email'));
            $Member->setLevel(3);
        }
        else
        {
            $Member = new Member();
        }

        $FormBuilder = new MemberFormBuilder($Member, $this->managers->getManagerOf('Member'));
        $FormBuilder->build();

        $Form = $FormBuilder->form();

        // On récupère le gestionnaire de formulaire.
        $FormHandler = new \OCFram\FormHandler($Form, $this->managers->getManagerOf('Member'), $Request);

        if ($FormHandler->process())
        {
            // Ici ne résident plus que les opérations à effectuer une fois l'entité du formulaire enregistrée
            // (affichage d'un message informatif, redirection, etc.).
            $this->app->user()->setFlash('Le membre a bien été ajouté, merci !');
            $this->app->user()->setAuthenticated(true);
            $this->app->user()->setAttribute('user', $Member);
            $this->app->httpResponse()->redirect('.');
        }

        $Router = new Router();

        $this->page->addVar('form', $Form->createView());
        $this->page->addVar('router', $Router);
    }

    public function executeUnsubscribe(HTTPRequest $Request)
    {
        $this->run();
        $this->page->addVar('title', 'Désinscription');

        if($Request->method()=='POST')
        {
            $unsubscribe_message = $Request->postData('unsubscribe_message');

            if(!$unsubscribe_message)
            {
                $this->page->addVar('erreur', 'Votre message ne doit pas être vide.');
                return;
            }

            $FORManager = $this->managers->getManagerOf('FOR');
            $Characteristicc = $FORManager->getCharacteristiccUsingName('unsubscribe_message');

            $FVC_id = $FORManager->addCharacteristicvaluec($Characteristicc->id(), $unsubscribe_message);

            $Alertc = new Alertc(array(
                'FAC_fk_FAY' => 1,
                'FAC_fk_FAE' => 1
            ));

            $FAC_id = $FORManager->addAlertc($Alertc);

            $Alertd = new Alertd(array(
                'FAD_fk_FAC' => $FAC_id,
                'FAD_fk_FHC' => $Characteristicc->id(),
                'FAD_fk_FVC' => $FVC_id
            ));

            $FORManager->addAlertd($Alertd);

            $this->app->user()->setFlash('Votre demande a bien été enregistrée');
            $this->app->httpResponse()->redirect('/mon-compte.html');
        }

    }
}
