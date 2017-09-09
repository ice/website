<?php

namespace App\Extensions;

use Ice\Tag;

/**
 * Base controller.
 *
 * @category Extension
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class Controller extends \Ice\Mvc\Controller
{

    /**
     * Before execute action.
     *
     * @return void
     */
    public function before()
    {
        // Add meta tags
        $this->tag
            ->addMeta(['charset' => 'utf-8'])
            ->addMeta(['width=device-width, initial-scale=1, shrink-to-fit=no', 'viewport'])
            ->addMeta(['index, follow', 'robots'])
            ->addMeta([$this->config->key->google_validate, 'google-site-verification'])
            ->addMeta([$this->config->key->ms_validate, 'msvalidate.01']);

        $this->assets
            // Add styles to assets
            ->add('css/bootstrap.min.css', $this->config->assets->bootstrap)
            ->add('css/fonts.css', $this->config->assets->fonts)
            ->add('css/simple-line-icons.css', $this->config->assets->simplelineicons)
            ->add('css/frontend.css', $this->config->assets->frontend)

            // Add scripts to assets
            ->add('js/jquery.min.js', $this->config->assets->jquery)
            ->addJs(['//cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js', 'local' => false])
            ->add('js/bootstrap.min.js', $this->config->assets->bootstrap)
            ->add('js/frontend.js', $this->config->assets->frontend);
    }

    /**
     * Initialize the controller.
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
        $this->setLanguage();

        // Set default title and description
        $this->app->description = $this->config->app->description;
        $this->app->keywords = $this->config->app->keywords;

        $this->tag->setDocType(Tag::XHTML5);
        $this->tag->setTitle(_t($this->config->app->title));
    }

    /**
     * Set the language.
     *
     * @return void
     */
    public function setLanguage()
    {
        // Set the language
        if ($this->session->has('lang')) {
            // Set the language from session
            $this->i18n->lang($this->session->get('lang'));
        } elseif ($this->cookies->has('lang')) {
            // Set the language from cookie
            $this->i18n->lang($this->cookies->get('lang'));
        }
    }

    /**
     * After execute action.
     *
     * @return void
     */
    public function after()
    {
        // Set final title and description
        $description = mb_substr($this->filter->sanitize($this->app->description, 'string'), 0, 200, 'utf-8');
        $this->app->description = $description;

        $this->tag->setTitleSeparator(' | ');
        $this->tag->appendTitle($this->config->app->name);

        // Add meta tags
        $this->tag
            ->addMeta([$this->app->description, 'description', 'property' => 'og:description'])
            ->addMeta([$this->app->keywords, 'keywords']);

        $this->assets
            // Google analytics
            ->addJs(['content' => <<<JS
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
                ga('create', "{$this->config->key->analytics}", 'auto');
                ga('send', 'pageview');
JS
            ], '1.0.0');
    }

    /**
     * Load this if something was not found.
     *
     * @return string
     */
    public function notFound()
    {
        return $this->responseCode(404);
    }

    /**
     * Set status and return response.
     *
     * @param integer $code Response code
     *
     * @return string
     */
    public function responseCode($code = 200)
    {
        $this->app->setAutoRender(false);
        $this->response->setStatus($code);
        $this->view->setMainView('error');

        return $this->response;
    }

    /**
     * Get Markdown file.
     *
     * @param string $path Url to the file
     *
     * @return string
     */
    public function getMd($path)
    {
        $license = file_get_contents($path);
        return $this->view->getEngines()['.md']->getParser()->text($license);
    }
}
