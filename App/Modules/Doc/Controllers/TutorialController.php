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
        $this->tag->setTitle(_t('tutorials'));
    }

    public function helloAction()
    {
        $this->tag->setTitle(_t('hello'));
    }
}
