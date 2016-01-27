<?php
namespace App\Frontend\Modules\Member;

use Model\MemberManager;
use Model\NewsManager;
use \OCFram\Application;
use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use OCFram\Router;
use OCFram\Url;
use OCFram\Page;


class MemberController extends BackController
{
    use \OCFram\Run;

    public function executeFindMember(HTTPRequest $Request)
    {
        if($Request->postData('member_login'))
        {

        }
    }

    public function executeShow(HTTPRequest $Request)
    {
        $Member = $this->managers->getManagerOf('Member')->getUnique($Request->getData('member_id'));

        if ($Member->id()==0)
        {
            $this->app->httpResponse()->redirect404();
        }

        $this->run();
    }
}