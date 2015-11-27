<?php

namespace App\Modules\Doc\Controllers;

/**
 * Component controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class ComponentController extends IndexController
{

    public function indexAction()
    {
        $this->tag->setTitle(_t('Components'));
    }

    public function validationAction()
    {
        $this->tag->setTitle(_t('Validation'));
    }
}
