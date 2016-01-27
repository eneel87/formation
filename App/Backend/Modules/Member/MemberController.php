<?php
namespace App\Backend\Modules\Member;

use FormBuilder\AdminFormBuilder;
use Model\MemberManager;
use OCFram\Application;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Member;
use \OCFram\FormHandler;
use OCFram\Router;


class MemberController extends BackController
{
    use \OCFram\Run;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app, $module, $action);

        if ($this->app->user()->getAttribute('user')->level() != MemberManager::ADMINISTRATOR)
        {
            $this->app->httpResponse()->redirect('/admin/');
        }

        $this->page->addVar('router', new Router());
    }

    public function executeIndex(HTTPRequest $request)
    {
        $manager = $this->managers->getManagerOf('Member');

        $this->run();
        $this->page->addvar('listeMembers', $manager->getlist());
        $this->page->addVar('nombreMembers', $manager->count());
    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Ajout d\'un utilisateur');
        $this->run();

        $this->processForm($request);
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Modification d\'un utilisateur');
        $this->run();

        $this->processForm($request);
    }

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == "POST")
        {
            $member = new Member([
                'login' => $request->postData('login'),
                'password' => $request->postData('password'),
                'level' => $request->postData('level')
            ]);

            if ($request->getExists('id'))
            {
                $member->setId($request->getData('id'));
            }
        }
        else
        {
            // L'identifiant de l'utilisateur est transmis si on veut le modifier
            if ($request->getExists('id'))
            {
                $member = $this->managers->getManagerOf('Member')->getUnique($request->getData('id'));
            }
            else
            {
                $member = new Member;
            }
        }

        $formBuilder = new AdminFormBuilder($member);
        $formBuilder->build();

        $form = $formBuilder->form();

        // On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
        $formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Member'), $request);

        if ($formHandler->process())
        {
            if($member->id()== $this->app->user()->getAttribute('user')->id())
            {
                $this->app->user()->setAttribute('user', $member);
            }
            // Ici ne résident plus que les opérations à effectuer une fois l'entité du formulaire enregistrée
            // (affichage d'un message informatif, redirection, etc.).
            $this->app->user()->setFlash($member->isNew() ? 'L \'utilisateur a bien été ajouté !' : 'L\'utiilsateur a bien été modifié !');
            $this->app->httpResponse()->redirect('/admin/members.html');
        }

        $this->page->addVar('form', $form->createView());
        $this->run();
    }

    public function executeDelete(HTTPRequest $request)
    {
        $MemberId = $request->getData('id');

        $this->managers->getManagerOf('Member')->delete($MemberId);

        if($this->app->user()->getAttribute('user')->id() == $MemberId)
        {
            $this->app->httpResponse()->redirect('/admin/deconnexion.html');
        }

        $this->app->user()->setFlash('L\'utilisateur a bien été supprimé !');

        $this->app->httpResponse()->redirect('/admin/members.html');
    }
}