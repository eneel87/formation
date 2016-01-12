<?php
namespace App\Backend\Modules\News;

use Model\MemberManager;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\News;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \Entity\User;
use OCFram\Application;

class NewsController extends BackController
{

  public function __construct(Application $app, $module, $action)
  {
    parent::__construct($app, $module, $action);

    $Member = $this->app->user()->getAttribute('admin');

    if(!$this->app->httpRequest()->getExists('id'))
    {
      return;
    }
    $news_id = $this->app->httpRequest()->getData('id');
    $ManagerNews = $this->managers->getmanagerof('news');
    $News = $ManagerNews->getUnique($news_id);

    if(!$News)
    {
      $this->app->user()->setFlash('La news spécifiée n\'existe pas');
      $this->app->httpResponse()->redirect('/admin/');
    }

    if(($action=="update" || $action=="delete") && $Member->level() != MemberManager::ADMINISTRATOR )
    {
      $ManagerNews = $this->managers->getmanagerof('news');

      if($Member->id()!= $ManagerNews->getUnique($news_id)->auteurId())
      {
        $this->app->user()->setFlash("Vous n'avez les droits nécessaires pour cette action !");
        $this->app->httpResponse()->redirect('/admin/');
      }
    }
  }
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
    $ManagerComment = $this->managers->getManagerof('Comments');

    $comment_id = $request->getData('comment_id');
    $Comment = $ManagerComment->getUnique($comment_id);
    $Membre = $this->app->user()->getAttribute('admin');

    if(!$Comment)
    {
      $this->app->user()->setFlash("Le commentaire spécifié n'existe pas !");
      $this->app->httpResponse()->redirect('/admin/');
    }

    if($Membre->id() != $Comment->Membre()->id() && $Membre->level() != MemberManager::ADMINISTRATOR)
    {
      $this->app->user()->setFlash("Vous n'avez pas les droits nécessaires pour cette action !");
      $this->app->httpResponse()->redirect('/admin/');
    }

    $this->page->addVar('title', 'Modification d\'un commentaire');

    if ($request->method() == 'POST')
    {
      $ManagerComment = $this->managers->getManagerof('Comments');
      $Comment = $ManagerComment->getUnique($request->getData('comment_id'));
      $Comment->setContenu($request->postData('contenu'));
    }
    else
    {
      $Comment = $this->managers->getManagerOf('Comments')->get($request->getData('comment_id'));
    }

    $formBuilder = new CommentFormBuilder($Comment);
    $formBuilder->build();

    $form = $formBuilder->form();

    if ($request->method() == 'POST' && $form->isValid())
    {
      $this->managers->getManagerOf('Comments')->save($Comment);
      $this->app->user()->setFlash('Le commentaire a bien été modifié');
      $this->app->httpResponse()->redirect('/news-'.$Comment->newsId().'.html');
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
      $this->app->httpResponse()->redirect('/admin/news.html');
    }

    $this->page->addVar('form', $form->createView());
  }


  public function executeDeleteComment(HTTPRequest $request)
  {
    $ManagerComment = $this->managers->getManagerof('Comments');

    $comment_id = $request->getData('comment_id');
    $Comment = $ManagerComment->getUnique($comment_id);
    $Membre = $this->app->user()->getAttribute('admin');

    if(!$Comment)
    {
      $this->app->user()->setFlash("Le commentaire spécifié n'existe pas !");
      $this->app->httpResponse()->redirect('/admin/');
    }

    if($Membre->id() != $Comment->Membre()->id() && $Membre->level() != MemberManager::ADMINISTRATOR)
    {
      $this->app->user()->setFlash("Vous n'avez pas les droits nécessaires pour cette action !");
      $this->app->httpResponse()->redirect('/admin/');
    }

    $this->managers->getManagerOf('Comments')->delete($comment_id);
    
    $this->app->user()->setFlash('Le commentaire a bien été supprimé !');
    
    $this->app->httpResponse()->redirect('/news-'.$Comment->newsId().'.html');
  }

  public function executeInsertComment(HTTPRequest $request)
  {
    // Si le formulaire a été envoyé.
    if ($request->method() == 'POST')
    {
      $comment = new Comment([
          'newsId' => $request->getData('news_id'),
          'auteurId' => $this->app->user()->getAttribute('admin')->id(),
          'contenu' => $request->postData('contenu')
      ]);
    }
    else
    {
      $comment = new Comment;
    }

    $formBuilder = new CommentFormBuilder($comment);
    $formBuilder->build();

    $form = $formBuilder->form();

    // On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
    $formHandler = new \OCFram\FormHandler($form, $this->managers->getManagerOf('Comments'), $request);

    if ($formHandler->process())
    {
      // Ici ne résident plus que les opérations à effectuer une fois l'entité du formulaire enregistrée
      // (affichage d'un message informatif, redirection, etc.).
      $this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
      $this->app->httpResponse()->redirect('../news-'.$request->getData('news_id').'.html');
    }

    $this->page->addVar('comment', $comment);
    $this->page->addVar('form', $form->createView());
    $this->page->addVar('title', 'Ajout d\'un commentaire');
  }
}