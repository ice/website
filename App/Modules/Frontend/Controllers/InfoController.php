<?php

namespace App\Modules\Frontend\Controllers;

use App\Boot\Error;
use App\Extensions\Controller;
use App\Extensions\RecursiveSortedIterator;
use App\Libraries\Email;
use Ice\Validation;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Frontend info controller.
 *
 * @category Controller
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class InfoController extends Controller
{

    /**
     * How to download page.
     *
     * @return void
     */
    public function downloadAction()
    {
        $this->tag->setTitle(_t('download'));
        $this->app->description = _t('download.info');
    }

    /**
     * Display contact form.
     *
     * @return void
     */
    public function contactAction()
    {
        $this->tag->setTitle(_t('contact'));
        $this->app->description = _t('contact');
    }

    /**
     * Display license page.
     *
     * @return void
     */
    public function licenseAction()
    {
        $this->tag->setTitle(_t('license'));
        $this->app->description = _t('license');

        $this->view->setVars([
            'title' => _t('license'),
            'content' => $this->getMd('https://raw.githubusercontent.com/ice/framework/dev/LICENSE')
        ]);
        $this->view->setContent($this->view->partial('md'));
    }

    /**
     * Display changelog page.
     *
     * @return void
     */
    public function changelogAction()
    {
        $this->tag->setTitle(_t('changelog'));
        $this->app->description = _t('changelog');

        $this->view->setVars([
            'title' => _t('changelog'),
            'subtitle' => _t('See the release notes.'),
            'id' => 'changelog',
            'content' => $this->getMd('https://raw.githubusercontent.com/ice/framework/dev/CHANGELOG.md')
        ]);
        $this->view->setContent($this->view->partial('md'));

        $this->assets
            ->addJs(['content' => <<<JS
                $(document).ready(function() {
                    $("#changelog ul li ul li").each(function (i, e) {
                        $(e).html(
                            // Detect & replace github ref
                            $(e).text().replace(/#(\d+)/, function (text, issue) {
                                return $('<a>', {
                                    href: "https://github.com/ice/framework/issues/" + issue,
                                    text: text,
                                    title: $(e).text(),
                                    target: '_blank'
                                }).prop('outerHTML');
                            })
                        );
                    });
                });
JS
            ], '1.0.0');
    }

    /**
     * Validate _POST and send email
     *
     * @throws Error
     * @return void
     */
    public function postContactAction()
    {
        $validation = new Validation();

        $validation->rules([
            'fullName' => 'required',
            'email' => 'required|email',
            'repeatEmail' => 'same:email',
            'content' => 'required|length:10,5000',
        ]);

        $valid = $validation->validate($_POST);

        if (!$valid) {
            $this->view->setVar('errors', $validation->getMessages());
            $this->flash->warning('<strong>' . _t('warning') . '!</strong> ' . _t("correctErrors"));
        } else {
            // Prepare an email
            $email = new Email();
            $email->prepare(_t('Contact'), $this->config->app->admin, 'email/contact', [
                'fullName' => $this->request->getPost('fullName'),
                'email' => $this->request->getPost('email'),
                'content' => $this->request->getPost('content'),
            ]);
            $email->addReplyTo($this->request->getPost('email'));

            // Try to send email
            if ($email->Send() === true) {
                $this->flash->notice('<strong>' . _t('success') . '!</strong> ' . _t("messageSent"));
                unset($_POST);
            } else {
                throw new Error($email->ErrorInfo);
            }
        }
    }
}
