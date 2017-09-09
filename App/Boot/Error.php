<?php

namespace App\Boot;

use Ice\Di;
use Ice\Dump;
use Ice\Exception;
use Ice\Tag;
use Ice\Log\Driver\File;
use App\Libraries\Email;

/**
 * Handle exception, do something with it depending on the environment.
 *
 * @category Boot
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class Error extends Exception
{

    /**
     * Error constructor.
     *
     * @param string $message  Error message
     * @param int    $code     Errror code
     * @param mixed  $previous Previous error
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        // Make sure everything is assigned properly
        parent::__construct($message, (int) $code, $previous);

        $e = $previous ? $previous : $this;
        $error = get_class($e) . '[' . $e->getCode() . ']: ' . $e->getMessage();
        $info = $e->getFile() . '[' . $e->getLine() . ']';
        $debug = "Trace: \n" . $e->getTraceAsString() . "\n";

        // Get the error settings depending on environment
        $di = Di::fetch();

        if ($di->has("dump")) {
            $dump = $di->dump;
        } else {
            $dump = new Dump(true);
        }

        $err = $di->config->env->error;

        if ($err->debug) {
            // Display debug
            if (PHP_SAPI == 'cli') {
                var_dump($error, $info, $debug);
            } else {
                echo $dump->vars($error, $info, $debug);
            }
        } else {
            if (PHP_SAPI == 'cli') {
                echo $message;
            } else {
                if ($err->hide) {
                    $message = _t('somethingIsWrong');
                }

                // Load and display error view
                echo self::view($di, $code, $message);
            }
        }

        if ($err->log) {
            // Log error into the file
            $logger = new File(__ROOT__ . '/App/log/' . date('Ymd') . '.log');
            $logger->error($error);
            $logger->info($info);
            $logger->debug($debug);
        }

        if ($err->email) {
            // Send email to admin
            $log = $dump->vars($error, $info, $debug);

            if ($di->has("request")) {
                $log .= $dump->one($di->request->getData(), '_REQUEST');
                $log .= $dump->one($di->request->getServer()->getData(), '_SERVER');
                $log .= $dump->one($di->request->getPost()->getData(), '_POST');
                $log .= $dump->one($di->request->getQuery()->getData(), '_GET');
            }

            $email = new Email();
            $email->prepare(_t('somethingIsWrong'), $di->config->app->admin, 'email/error', ['log' => $log]);

            if ($email->Send() !== true) {
                $logger = new File(__ROOT__ . '/App/log/' . date('Ymd') . '.log');
                $logger->error($email->ErrorInfo);
            }
        }

        exit(1);
    }

    /**
     * Get the error view.
     *
     * @param object  $di      Di object
     * @param integer $code    Error code
     * @param string  $message Error message
     *
     * @return string
     */
    public static function view($di, $code, $message)
    {
        $di->tag->setDocType(Tag::XHTML5);
        $di->tag->setTitle(_t('status :code', [':code' => $code]));
        $di->tag->appendTitle($di->config->app->name, ' | ');

        // Clear meta tags and assets
        $di->tag->setMeta([]);
        $di->assets->setCollections([]);

        // Add meta tags
        $di->tag
            ->addMeta(['charset' => 'utf-8'])
            ->addMeta(['width=device-width, initial-scale=1, shrink-to-fit=no', 'viewport'])
            ->addMeta(['noindex, nofollow', 'robots']);

        // Add styles to assets
        $di->assets
            ->add('css/bootstrap.min.css', $di->config->assets->bootstrap)
            ->add('css/fonts.css', $di->config->assets->fonts)
            ->add('css/simple-line-icons.css', $di->config->assets->simplelineicons)
            ->add('css/frontend.css', $di->config->assets->frontend);

        // Restore default view settings
        $di->view->setViewsDir(__ROOT__ . '/App/views/');
        $di->view->setPartialsDir('partials/');
        $di->view->setLayoutsDir('layouts/');
        $di->view->setFile('partials/status');

        if ($di->response->isServerError()) {
            $icon = 'icon-close text-danger';
        } elseif ($di->response->isClientError()) {
            $icon = 'icon-exclamation text-info';
        } elseif ($di->response->isRedirection()) {
            $icon = 'icon-reload text-warning';
        } else {
            $icon = 'icon-question';
        }

        $di->view->setVars([
            'icon' => $icon,
            'title' => _t('status :code', [':code' => $code]),
            'content' => $message,
        ]);
        $di->view->setContent($di->view->render());

        return $di->view->layout('error');
    }
}
