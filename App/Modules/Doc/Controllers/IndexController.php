<?php

namespace App\Modules\Doc\Controllers;

use App\Extensions\Controller;

/**
 * Documentation home controller.
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class IndexController extends Controller
{

    /**
     * Before execute an action.
     *
     * @return void
     */
    public function before()
    {
        parent::before();

        $this->assets
            ->add('css/highlight/tomorrow.min.css', $this->config->assets->highlight)
            ->add('js/plugins/highlight.min.js', $this->config->assets->highlight)
            ->addJs(['content' => <<<JS
                $(document).ready(function() {
                    $("pre code").each(function(i, e) {hljs.highlightBlock(e)});
                });
JS
            ], '1.0.0');
    }

    /**
     * Display home page.
     *
     * @return void
     */
    public function indexAction()
    {
        $this->tag->setTitle(_t('documentation'));
        $this->app->description = _t('documentation');
    }
}
