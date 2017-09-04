<?php

namespace App\Modules\Doc\Controllers;

use App\Extensions\Controller;

/**
 * Documentation home controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class IndexController extends Controller
{

    public function before()
    {
        parent::before();

        $this->assets
            ->add('css/highlight/tomorrow.min.css', '9.12.0')
            ->add('js/plugins/highlight.min.js', '9.12.0')
            ->addJs(['content' => <<<JS
                $(document).ready(function() {
                    $("pre code").each(function(i, e) {hljs.highlightBlock(e)});
                });
JS
            ], '1.0.0');
    }

    /**
     * Display doc home page
     */
    public function indexAction()
    {
        $this->tag->setTitle(_t('documentation'));
        $this->app->description = _t('documentation');
    }
}
