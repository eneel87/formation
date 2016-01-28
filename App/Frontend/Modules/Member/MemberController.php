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
        if($Request->method()!='POST' || $Request->postData('member_login')==null)
        {
            $this->app->httpResponse()->redirect404();
        }

        $Members_Manager = $this->managers->getManagerOf('Member');
        $Member = $Members_Manager->getMemberUsingLogin(trim($Request->postData('member_login')));

        if($Member->id()==0)
        {
            $this->app->httpResponse()->redirect404();
        }

        $this->app->httpResponse()->redirect('/member-'.$Member->id().'.html');

    }

    public function executeShow(HTTPRequest $Request)
    {
        $Member = $this->managers->getManagerOf('Member')->getUnique($Request->getData('member_id'));

        if ($Member->id()==0)
        {
            $this->app->httpResponse()->redirect404();
        }

        $this->run();

        $CommentsManager = $this->managers->getManagerOf('Comments');
        $Comments_a = $CommentsManager->getCommentsUsingMemberId($Request->getData('member_id'));

        $Member->commentscount = $CommentsManager->countUsingMemberId($Request->getData('member_id'));

        $NewsManager = $this->managers->getManagerOf('News');
        $News_a = $NewsManager->getListUsingMemberId($Request->getData('member_id'));

        foreach($News_a as $News)
        {
            $News->commentscount = $CommentsManager->countUsingNewsId($News->id());
        }

        $Router = new Router();

        $this->page->addVar('Member', $Member);
        $this->page->addVar('Comments_a', $Comments_a);
        $this->page->addVar('News_a', $News_a);
        $this->page->addVar('Router', $Router);
    }
}