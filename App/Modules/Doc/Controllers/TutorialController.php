<?php

namespace App\Modules\Doc\Controllers;

/**
 * Tutorial controller.
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class TutorialController extends IndexController
{

    /**
     * Display tutorials page.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->tag->setTitle(_t('tutorials'));
    }

    /**
     * Display hello tutorial.
     *
     * @return void
     */
    public function helloAction()
    {
        $this->tag->setTitle(_t('hello'));
    }
}
