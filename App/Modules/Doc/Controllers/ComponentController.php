<?php

namespace App\Modules\Doc\Controllers;

/**
 * Component controller.
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class ComponentController extends IndexController
{

    /**
     * Display components page.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->tag->setTitle(_t('components'));
    }

    /**
     * Display model page.
     *
     * @return void
     */
    public function modelAction()
    {
        $this->tag->setTitle(_t('model'));
    }

    /**
     * Display pagination page.
     *
     * @return void
     */
    public function paginationAction()
    {
        $this->tag->setTitle(_t('pagination'));
    }

    /**
     * Display validation page
     *
     * @return void
     */
    public function validationAction()
    {
        $this->tag->setTitle(_t('validation'));
    }
}
