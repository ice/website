<?php

namespace App\Extensions;

use Ice\Tag;

/**
 * Base controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class Controller extends \Ice\Mvc\Controller
{

    /**
     * Meta description
     * @var string
     */
    public $siteDesc;

    /**
     * Assets container
     * @var array
     */
    public $assets = [];

    /**
     * Before execute action
     *
     * @return void
     */
    public function before()
    {
        // Set default title and description
        $this->tag->setDocType(Tag::XHTML5);
        $this->tag->setTitle(_t($this->config->app->title));
        $this->siteDesc = _t($this->config->app->description);

        // Add css and js to assets collection
        $this->assets['styles'][] = $this->tag->link(['css/bootstrap.min.css?v=3.3.1']);
        $this->assets['styles'][] = $this->tag->link(['css/fonts.css']);
        $this->assets['styles'][] = $this->tag->link(['css/app.css']);
        $this->assets['styles'][] = $this->tag->link(['css/simple-line-icons.css?v=2.2.2']);

        $this->assets['scripts'][] = $this->tag->script(['js/jquery.min.js?v=1.11.1']);
        $this->assets['scripts'][] = $this->tag->script(['js/bootstrap.min.js?v=3.3.1']);
        $this->assets['scripts'][] = $this->tag->script(['js/plugins.js']);
    }

    /**
     * Initialize the controller
     *
     * @return void
     */
    public function initialize()
    {
        $lifetime = $this->config->session->lifetime;

        // Check the session lifetime
        if ($this->session->has('last_active') && time() - $this->session->get('last_active') > $lifetime) {
            $this->session->destroy();
        }

        $this->session->set('last_active', time());

        // Set the language
        if ($this->session->has('lang')) {
            // Set the language from session
            $this->i18n->lang($this->session->get('lang'));
        } elseif ($this->cookies->has('lang')) {
            // Set the language from cookie
            $this->i18n->lang($this->cookies->get('lang'));
        }

        // Send langs to the view
        $this->view->setVars([
            // Translate langs before send
            'siteLangs' => array_map('_t', $this->config->i18n->langs->toArray()),
            'header' => 'header'
        ]);
    }

    /**
     * After execute action
     *
     * @return void
     */
    public function after()
    {
        // Set final title and description
        $this->tag->setTitleSeparator(' | ');
        $this->tag->appendTitle($this->config->app->name);

        $this->assets['scripts'][] = $this->tag->script(['content' =>
            "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-56854903-1', 'auto');
            ga('send', 'pageview');"
        ]);
        $this->view->setVars([
            'siteDesc' => mb_substr($this->filter->sanitize($this->siteDesc, 'string'), 0, 200, 'utf-8'),
            'assets' => $this->assets,
        ]);
    }

    /**
     * Load this if something was not found
     */
    public function notFound()
    {
        $this->response->setStatus(404);
        $this->view->setMainView('error');
    }
}
