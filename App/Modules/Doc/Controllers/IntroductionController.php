<?php

namespace App\Modules\Doc\Controllers;

/**
 * Introduction controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class IntroductionController extends IndexController
{

    public function indexAction()
    {
        $this->tag->setTitle(_t('introduction'));
    }

    public function readmeAction()
    {
        $this->tag->setTitle(_t('readme'));
    }

    public function serverAction()
    {
        $this->tag->setTitle(_t('serverConfiguration'));
    }

    public function benchmarkAction()
    {
        $this->tag->setTitle(_t('benchmark'));
    }

    public function windowsAction()
    {
        $this->tag->setTitle(_t('compilationWindows'));
    }
}
