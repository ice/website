<?php

namespace App\Boot;

use Ice\Di;
use Ice\Dump;
use Ice\Exception;
use Ice\Log\Driver\File as Logger;
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
            // Load and display error view
            if (PHP_SAPI == 'cli') {
                echo _t('somethingIsWrong');
            } else {
                // Load and display error view
                $view = $di->view;

                if ($err->hide) {
                    unset($message, $code);
                } else {
                    $view->setVar('message', $error);
                }

                $assets['styles'] = [
                    $di->tag->link(['css/bootstrap.min.css?v=4.0.0bata1']),
                    $di->tag->link(['css/fonts.css']),
                    $di->tag->link(['css/frontend.css']),
                    $di->tag->link(['css/simple-line-icons.css'])
                ];

                echo $view->layout('error', $assets);
            }
        }

        if ($err->log) {
            // Log error into the file
            $logger = new Logger(__ROOT__ . '/App/log/' . date('Ymd') . '.log');
            $logger->error($error);
            $logger->info($info);
            $logger->debug($debug);
        }

        if ($err->email) {
            // Send email to admin
            $log = $dump->vars($error, $info, $debug);

            $email = new Email();
            $email->prepare(_t('somethingIsWrong'), $di->config->app->admin, 'email/error', ['log' => $log]);

            if ($email->Send() !== true) {
                $logger = new Logger(__ROOT__ . '/App/log/' . date('Ymd') . '.log');
                $logger->error($email->ErrorInfo);
            }
        }
    }
}
