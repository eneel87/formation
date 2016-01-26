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
use \OCFram\Application;
use OCFram\Page;
use \OCFram\Router;


class NewsController extends BackController
{

  use \OCFram\Run;

  public function __construct(Application $app, $module, $action)
  {
    parent::__construct($app, $module, $action);

    $this->page->addVar('router', new Router());

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
    $this->run();
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

    $this->run();
    $this->page->addVar('title', 'Ajout d\'une news');
  }

  public function executeUpdate(HTTPRequest $request)
  {
    $this->processForm($request);

    $this->run();
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

    $this->run();
    $this->page->addVar('title', 'Modification d\'un commentaire');

    if ($request->method() == 'POST')
    {
      $ManagerComment = $this->managers->getManagerof('Comments');
      $Comment = $ManagerComment->getUnique($request->getData('comment_id'));
      $Comment->setContenu($request->postData('contenu'));
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

    $this->run();
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

  public function executeInsertCommentUsingAjax(HTTPRequest $request)
  {
    $CommentManager = $this->managers->getManagerOf('Comments');
    $NewsManager = $this->managers->getManagerOf('News');

    $this->page->setTemplate('jsonLayout.php');

    $News = $NewsManager->getUnique($request->getData('news_id'));

    if(!$News)
    {
      $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs'=>'Cette news n\existe pas !')));
      return;
    }

    $Comment = new Comment([
        'newsId' => $request->getData('news_id'),
        'auteurId' => $this->app->user()->getAttribute('admin')->id(),
        'contenu' => $request->postData('contenu')
    ]);

    $FormBuilder = new CommentFormBuilder($Comment);
    $FormBuilder->build();

    $Form = $FormBuilder->form();

    // On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
    $FormHandler = new \OCFram\FormHandler($Form, $CommentManager, $request);

    if(!$FormHandler->process())
    {
      $errors_a = [];

      foreach($Form->fields() as $Field)
      {
        $Field->isValid();
        $errors_a[$Field->id()] = $Field->errorMessage();
      }


      $this->page->addVar('erreurs', json_encode(array('erreurs'=>$errors_a)));

      return;
    }

    $Comments_a = $CommentManager->getAfterId($News->id(), $request->postData('last_insert_id'));

    $comments_views_a = array();
    $Router = new Router();

    foreach($Comments_a as $Comment)
    {
      $PageComment = new Page($this->app);
      $PageComment->setContentFile(__DIR__.'/Views/comment.php');
      $PageComment->addVar('comment',$Comment);
      $PageComment->addVar('router',$Router);
      $PageComment->setTemplate('jsonLayout.php');

      $comments_views_a[$Comment->id()]=$PageComment->getGeneratedPage();
    }

    $ajax = array('success'=>true,
                  'comments' => $comments_views_a,
                  'validation_message' => 'Votre message a bien été ajouté.'
    );

    $this->page->addVar('ajax', json_encode($ajax));
  }

  public function executeInsertComment(HTTPRequest $request)
  {
    // Si le formulaire a été envoyé.
    if ($request->method() == 'POST')
    {
      $Comment = new Comment([
          'newsId' => $request->getData('news_id'),
          'auteurId' => $this->app->user()->getAttribute('admin')->id(),
          'contenu' => $request->postData('contenu')
      ]);
    }
    else
    {
      $Comment = new Comment;
    }

    $formBuilder = new CommentFormBuilder($Comment);
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

    $Router = new Router();
    $form_action = $Router->getUrl('Backend', 'News', 'insertComment', array('news_id' => $request->getData('news_id')));
    $form_action_ajax_validation = $Router->getUrl('Backend', 'News', 'insertCommentUsingAjax', array('news_id' => $request->getData('news_id')));

    $jsFiles_a = array();
    $jsFiles_a[] = '<script type="text/javascript" src="/js/CommentInsertUsingAjax.js"></script>';

    $news_id = $request->getData('news_id');

    $this->run();
    $this->page->addVar('form_action', $form_action);
    $this->page->addVar('news_id', $news_id);
    $this->page->addVar('form_action_ajax_validation', $form_action_ajax_validation);
    $this->page->addVar('comment', $Comment);
    $this->page->addVar('form', $form->createView());
    $this->page->addVar('title', 'Ajout d\'un commentaire');
    $this->page->addVar('jsFiles_a', $jsFiles_a);
  }

 public function executeUpdateCommentUsingAjax(HTTPRequest $Request)
 {
   $ManagerComment = $this->managers->getManagerof('Comments');
   $Comment = $ManagerComment->getUnique($Request->getData('comment_id'));
   $Membre = $this->app->user()->getAttribute('admin');

   $this->page->setTemplate('jsonLayout.php');

   if(!$Comment)
   {
     $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs'=>'Le commentaire n\'existe pas !')));
     return;
   }

   if($Comment->Membre()->id() != $Membre->id() && $Membre->level() != MemberManager::ADMINISTRATOR)
   {
     $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs'=>'Droits insuffisants')));
     return;
   }

   $CommentPosted = new Comment();
   $CommentPosted->setId($Request->getData('comment_id'));
   $CommentPosted->setAuteurId($this->app->user()->getAttribute('admin')->id());
   $CommentPosted->setContenu($Request->postData('contenu'));

   $FormBuilder = new CommentFormBuilder($CommentPosted);
   $FormBuilder->build();

   $Form = $FormBuilder->form();

   // On récupère le gestionnaire de formulaire (le paramètre de getManagerOf() est bien entendu à remplacer).
   $FormHandler = new \OCFram\FormHandler($Form, $ManagerComment, $Request);

   if(!$FormHandler->process())
   {
     $errors_a = [];

     foreach($Form->fields() as $Field)
     {
       $Field->isValid();
       $errors_a[$Field->id()] = $Field->errorMessage();
     }

     $this->page->setTemplate('jsonLayout.php');
     $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs'=>$errors_a)));

     return;
   }

   $CommentPosted = $ManagerComment->getUnique($CommentPosted->id());

   $this->page->addVar('ajax', json_encode(array('success'=>true, 'comment'=>$CommentPosted)));
 }

  public function executeDeleteCommentUsingAjax(HTTPRequest $Request)
  {
    $CommentManager = $this->managers->getManagerOf('Comments');
    $Comment = $CommentManager->getUnique($Request->getData('comment_id'));

    $this->page->setTemplate('jsonLayout.php');

    if(!$Comment)
    {
      $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs' => 'Le commentaire n\'existe pas !')));
      return;
    }

    $Membre = $this->app->user()->getAttribute('admin');

    if($Comment->auteurId()!= $Membre->id() && $Membre->level() != MemberManager::ADMINISTRATOR)
    {
      $this->page->addVar('ajax', json_encode(array('success'=>false, 'erreurs' => 'Droits insuffisants')));
      return;
    }

    $CommentManager->delete($Request->getData('comment_id'));

    $this->page->addVar('ajax', json_encode(array('success'=>true, 'validation_message'=>'Votre message a bien été supprimé')));

  }
}