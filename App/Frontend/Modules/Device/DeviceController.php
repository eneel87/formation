<?php
namespace App\Frontend\Modules\Device;

use \OCFram\BackController;
use \OCFram\HTTPRequest;

class DeviceController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $detect = new \Mobile_Detect();

        $deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

        $ua = $detect->getUserAgents();

        $this->page->addVar('deviceType', $deviceType);

    }
}