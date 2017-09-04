<?php

namespace App\Modules\Frontend\Controllers;

use App\Extensions\Controller;

/**
 * Frontend language controller
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class LangController extends Controller
{

    /**
     * Set a language.
     *
     * @return void
     */
    public function setAction()
    {
        $params = $this->router->getParams();

        if ($lang = $params["param"]) {
            // Store lang in session and cookie
            $this->session->set('lang', $lang);
            $this->cookies->set('lang', $lang, time() + 365 * 86400);
        }
        // Go to the last place
        $referer = $this->request->getServer("HTTP_REFERER");
        if (strpos($referer, $this->request->getServer("HTTP_HOST") . "/") !== false) {
            return $this->response->setHeader("Location", $referer);
        } else {
            $this->response->redirect();
        }
    }
}
