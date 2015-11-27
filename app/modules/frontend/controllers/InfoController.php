<?php

namespace App\Modules\Frontend\Controllers;

use App\Error;
use App\Extensions\Controller;
use App\Libraries\Email;
use Ice\Validation;

/**
 * Frontend info controller
 *
 * @package     Ice/Website
 * @category    Controller
 */
class InfoController extends Controller
{

    /**
     * How to download
     */
    public function downloadAction()
    {
        $this->tag->setTitle(_t('Download'));
        $this->view->setVar('root', __ROOT__);
    }
    
    /**
     * Display contact form
     */
    public function contactAction()
    {
        $this->tag->setTitle(_t('Contact'));
        $this->siteDesc = _t('Contact');
    }

    /**
     * Display license
     */
    public function licenseAction()
    {
        $this->tag->setTitle(_t('License'));
        $this->siteDesc = _t('License');
    }

    /**
     * Validate _POST and send email
     *
     * @throws Error
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

        $validation->setLabels([
            'fullName' => _t('Full name'),
            'content' => _t('Content'),
            'email' => _t('Email'),
            'repeatEmail' => _t('Repeat email')
        ]);

        $valid = $validation->validate($_POST);

        if (!$valid) {
            $this->view->setVar('errors', $validation->getMessages());
            $this->flash->warning('<strong>' . _t('Warning') . '!</strong> ' . _t("Please correct the errors."));
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
                $this->flash->notice('<strong>' . _t('Success') . '!</strong> ' . _t("Message was sent."));
                unset($_POST);
            } else {
                throw new Error($email->ErrorInfo);
            }
        }
    }
}
