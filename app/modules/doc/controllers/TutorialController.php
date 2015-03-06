<?php

namespace App\Modules\Doc\Controllers;

/**
 * Tutorial controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class TutorialController extends IndexController
{

    public function indexAction()
    {
        $this->tag->setTitle(__('Tutorials'));
    }

    public function helloAction()
    {

    }
}
