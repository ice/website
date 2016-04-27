<?php

namespace App\Modules\Frontend\Controllers;

use App\Error;
use App\Extensions\Controller;
use App\Extensions\RecursiveSortedIterator;
use App\Libraries\Email;
use Ice\Validation;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

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

        $dit = new RecursiveDirectoryIterator(__ROOT__ . '/public/dll/', RecursiveDirectoryIterator::SKIP_DOTS);
        $rit = new RecursiveIteratorIterator($dit, RecursiveIteratorIterator::SELF_FIRST);
        $sit = new RecursiveSortedIterator($rit);

        $windows = [];

        foreach ($sit as $item) {
            if (!$item->isDir() && $item->getExtension() == 'zip') {
                if (preg_match(
                    '/(ice-(\d+\.\d+\.\d+)-php-(\d+\.\d+)-(\w+)-vc\d+-x(\d+))\.zip/i',
                    $item->getFilename(),
                    $matches
                )) {
                    $file = $matches[1];
                    $version = $matches[2];
                    $php = $matches[3];
                    $release = strtoupper($matches[4]);
                    $arch = $matches[5];

                    $windows[$php][$version][] = [
                        'name' => $file,
                        'release' => $release,
                        'arch' => $arch,
                    ];
                }
            }
        }

        $this->view->setVars([
            'root' => __ROOT__,
            'windows' => $windows
        ]);

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
