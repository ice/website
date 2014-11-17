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
        $this->assets['styles'][] = $this->tag->link(['css/highlight/tomorrow.min.css?v=8.3']);
        $this->assets['scripts'][] = $this->tag->script(['js/plugins/highlight.min.js?v=8.3']);
        $this->assets['scripts'][] = $this->tag->script([
            'content' => '$(document).ready(function() {$("pre code").each(function(i, e) {hljs.highlightBlock(e)});});'
        ]);
    }
}
