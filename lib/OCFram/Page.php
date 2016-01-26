<?php
namespace OCFram;

class Page extends ApplicationComponent
{
  protected $template;
  protected $contentFile;
  protected $vars = [];
  protected $menu;

  public function __construct(Application $App)
  {
    parent::__construct($App);
    $this->template = 'layout.php';
  }

  public function addVar($var, $value)
  {
    if (!is_string($var) || is_numeric($var) || empty($var))
    {
      throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
    }

    $this->vars[$var] = $value;
  }

  public function getGeneratedPage()
  {
    if (!file_exists($this->contentFile))
    {
      throw new \RuntimeException('La vue spécifiée n\'existe pas');
    }

    $user = $this->app->user();

    extract($this->vars);

    ob_start();
      require $this->contentFile;
    $content = ob_get_clean();

    $menu = $this->getMenu();

    ob_start();
      require __DIR__.'/../../App/'.$this->app->name().'/Templates/'.$this->template;
    return ob_get_clean();
  }

  public function getMenu()
  {
    $menu_view = __DIR__.'/../../App/'.$this->app->name().'/Templates/menu.php';

    if (!file_exists($menu_view))
    {
      throw new \RuntimeException('La vue spécifiée pour le menu n\'existe pas');
    }

    $router = new Router();
    $user = $this->app->user();

    ob_start();
      require $menu_view;
    $menu = ob_get_clean();

    return $menu;
  }

  public function setContentFile($contentFile)
  {
    if (!is_string($contentFile) || empty($contentFile))
    {
      throw new \InvalidArgumentException('La vue spécifiée est invalide');
    }

    $this->contentFile = $contentFile;
  }

  public function setTemplate($template)
  {
    if (!is_string($template))
    {
      throw new \RuntimeException('Le template spécifié n\'existe pas');
    }

    $this->template = $template;
  }

  public function template()
  {
    $this->template;
  }
}