<?php
namespace App\Backend\Modules\Administration;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \FormBuilder\CommentFormBuilder;
use \FormBuilder\NewsFormBuilder;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \Entity\User;

class AdministrationController extends BackController
{
    public function executeIndex(HTTPRequest$request)
    {
        $this->page->addVar('title', 'Administration');
    }
}