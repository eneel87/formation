<?php
namespace App\Frontend\Modules\News;

use Model\NewsManager;
use \OCFram\Application;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\Router;
use OCFram\Url;


class NewsController extends BackController
{

  public function __construct(Application $app, $module, $action)
  {
    parent::__construct($app, $module, $action);

    $this->page->addVar('router', new Router());
  }
  public function executeIndex(HTTPRequest $request)
  {

    $nombreNews = $this->app->config()->get('nombre_news');
    $nombreCaracteres = $this->app->config()->get('nombre_caracteres');
    
    // On ajoute une définition pour le titre.
    $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');
    
    // On récupère le manager des news.
    /** @var NewsManager $manager */
    $manager = $this->managers->getManagerOf('News');
    
    // Cette ligne, vous ne pouviez pas la deviner sachant qu'on n'a pas encore touché au modèle.
    // Contentez-vous donc d'écrire cette instruction, nous implémenterons la méthode ensuite.
    $listeNews = $manager->getList(0, $nombreNews);


    
    foreach ($listeNews as $news)
    {
      if (strlen($news->contenu()) > $nombreCaracteres)
      {
        $debut = substr($news->contenu(), 0, $nombreCaracteres);
        $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

        $news->setContenu($debut);
      }
    }

    $Router = new Router();

    
    // On ajoute la variable $listeNews à la vue.
    $this->page->addVar('listeNews', $listeNews);
  }

  public function executeShow(HTTPRequest $request)
  {
    $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
    
    if (empty($news))
    {
      $this->app->httpResponse()->redirect404();
    }

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
    $form_action = $Router->getUrl('Backend', 'News', 'insertComment', array('news_id' => $request->getData('id')));
    $form_action_ajax_validation = $Router->getUrl('Backend', 'News', 'insertCommentUsingAjax', array('news_id' => $request->getData('id')));

    $jsFiles_a = array();
    $jsFiles_a[] = '<script type="text/javascript" src="/js/CommentInsertUsingAjax.js"></script>';

    $news_id = $request->getData('id');

    $this->page->addVar('news_id', $news_id);
    $this->page->addVar('form_action', $form_action);
    $this->page->addVar('form_action_ajax_validation', $form_action_ajax_validation);
    $this->page->addVar('comment', $Comment);
    $this->page->addVar('form', $form->createView());
    $this->page->addVar('title', 'Ajout d\'un commentaire');
    $this->page->addVar('jsFiles_a', $jsFiles_a);



    $this->page->addVar('router', new Router());
    $this->page->addVar('title', $news->titre());
    $this->page->addVar('news', $news);
    $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
  }
}