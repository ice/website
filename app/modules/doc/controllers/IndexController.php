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
        
        $this->assets['styles'][] = $this->tag->link(['css/highlight/tomorrow.min.css?v=8.3']);
        $this->assets['scripts'][] = $this->tag->script(['js/plugins/highlight.min.js?v=8.3']);
        $this->assets['scripts'][] = $this->tag->script([
            'content' => '$(document).ready(function() {$("pre code").each(function(i, e) {hljs.highlightBlock(e)});});'
        ]);
    }

    /**
     * Display doc index
     */
    public function indexAction()
    {
        $this->tag->setTitle(__('Documentation'));
    }

    /**
     * Display readme
     */
    public function readmeAction()
    {
        $this->tag->setTitle(__('Readme'));
    }
}
