<?php
namespace App\Backend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \Entity\User;

class NewsController extends BackController
{
  public function executeIndex(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Gestion des news');

    $ManagerNews = $this->managers->getmanagerof('news');
    $MemberManager = $this->managers->getmanagerof('member');

    if($this->app->user()->getAttribute('admin')->level()== $MemberManager::ADMINISTRATOR)
    {
      $this->page->addvar('listeNews', $ManagerNews->getlist());
      $this->page->addvar('nombreNews', $ManagerNews->count());
    }
    else
    {
      $nombreNews = $ManagerNews->countUsingMemberId($this->app->user()->getAttribute('admin')->id());

      if($nombreNews)
      {
        $this->page->addvar('nombreNews', $nombreNews);
        $this->page->addvar('listeNews', $ManagerNews->getListUsingMemberId($this->app->user()->getAttribute('admin')->id()));
      }
    }
  }

  public function executeDelete(HTTPRequest $request)
  {
    $newsId = $request->getData('id');

    $this->managers->getManagerOf('News')->delete($newsId);
    $this->managers->getManagerOf('Comments')->deleteFromNews($newsId);

    $this->app->user()->setFlash('La news a bien été supprimée !');

    $this->app->httpResponse()->redirect('.');
  }


  public function executeInsert(HTTPRequest $request)
  {
    $this->processForm($request);

    $this->page->addVar('title', 'Ajout d\'une news');
  }

  public function executeUpdate(HTTPRequest $request)
  {
    $this->processForm($request);

    $this->page->addVar('title', 'Modification d\'une news');
  }

  public function executeUpdateComment(HTTPRequest $request)
  {
    $this->page->addVar('title', 'Modification d\'un commentaire');

    if ($request->method() == 'POST')
    {
      $comment = new Comment([
          'id' => $request->getData('id'),
          'auteur' => $request->postData('auteur'),
          'contenu' => $request->postData('contenu')
      ]);
    }
    else
    {
      $comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
    }

    $formBuilder = new CommentFormBuilder($comment);
    $formBuilder->build();

    $form = $formBuilder->form();

    if ($request->method() == 'POST' && $form->isValid())
    {
      $this->managers->getManagerOf('Comments')->save($comment);
      $this->app->user()->setFlash('Le commentaire a bien été modifié');
      $this->app->httpResponse()->redirect('/admin/');
    }

    $this->page->addVar('form', $form->createView());
  }

  public function processform(HTTPRequest $request)
  {
    if ($request->method() == 'POST')
    {
      $news = new News([
          'auteurId' => $this->app->user()->getAttribute('admin')->id(),
          'titre' => $request->postData('titre'),
          'contenu' => $request->postData('contenu')
      ]);

      if ($request->getExists('id'))
      {
        $news->setId($request->getData('id'));
      }
    }
    else
    {
      // L'identifiant de la news est transmis si on veut la modifier
      if ($request->getExists('id'))
      {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
      }
      else
      {
        $news = new News;
      }
    }

    $formBuilder = new NewsFormBuilder($news);
    $formBuilder->build();

    $form = $formBuilder->form();

    // On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
    $formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('News'), $request);

    if ($formHandler->process())
    {
      // Ici ne résident plus que les opérations à effectuer une fois l'entité du formulaire enregistrée
      // (affichage d'un message informatif, redirection, etc.).
      $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');
      $this->app->httpResponse()->redirect('/admin/');
    }

    $this->page->addVar('form', $form->createView());
  }


  public function executeDeleteComment(HTTPRequest $request)
  {
    $this->managers->getManagerOf('Comments')->delete($request->getData('id'));
    
    $this->app->user()->setFlash('Le commentaire a bien été supprimé !');
    
    $this->app->httpResponse()->redirect('.');
  }
}