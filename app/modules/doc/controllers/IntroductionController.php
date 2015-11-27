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
        $this->tag->setTitle(_t('Introduction'));
    }

    public function readmeAction()
    {
        $this->tag->setTitle(_t('Readme'));
    }

    public function serverAction()
    {
        $this->tag->setTitle(_t('Server configuration'));
    }

    public function benchmarkAction()
    {
        $this->tag->setTitle(_t('Benchmark'));
    }

    public function windowsAction()
    {
        $this->tag->setTitle(_t('Compilation on Windows'));
    }
}
