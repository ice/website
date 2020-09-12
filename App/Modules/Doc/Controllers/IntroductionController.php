<?php

namespace App\Modules\Doc\Controllers;

/**
 * Introduction controller.
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class IntroductionController extends IndexController
{

    /**
     * Display introduction page.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->tag->setTitle(_t('introduction'));
    }

    /**
     * Display read me page.
     *
     * @return void
     */
    public function readmeAction()
    {
        $this->tag->setTitle(_t('readme'));

        $this->view->setVars([
            'title' => _t('readme'),
            'content' => $this->getMd('https://raw.githubusercontent.com/ice/framework/dev/README.md')
        ]);
        $this->view->setContent($this->view->partial('md'));
    }

    /**
     * Display server configuration page.
     *
     * @return void
     */
    public function serverAction()
    {
        $this->tag->setTitle(_t('serverConfiguration'));
    }

    /**
     * Display benchmark page.
     *
     * @return void
     */
    public function benchmarkAction()
    {
        $this->tag->setTitle(_t('benchmark'));
    }

    /**
     * Display compilation on windows page.
     *
     * @return void
     */
    public function windowsAction()
    {
        $this->tag->setTitle(_t('compilationWindows'));
    }
}
