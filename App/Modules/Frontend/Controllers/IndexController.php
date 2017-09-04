<?php

namespace App\Modules\Frontend\Controllers;

use App\Extensions\Controller;

/**
 * Frontend home controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class IndexController extends Controller
{

    /**
     * Display home page
     */
    public function indexAction()
    {
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
}
