<?php
namespace App\Backend\Modules\Administration;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \Entity\User;
use OCFram\Router;

class AdministrationController extends BackController
{
    use \OCFram\Run;

    public function executeIndex(HTTPRequest$request)
    {
        $this->run();
        $this->page->addVar('title', 'Administration');
        $this->page->addVar('router', new Router());
    }
}