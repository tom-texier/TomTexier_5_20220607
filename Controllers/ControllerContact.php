<?php

use Texier\Framework\Configuration;
use Texier\Framework\Controller;

class ControllerContact extends Controller
{
    public function index()
    {
        $this->generateView();
    }

    public function send()
    {
        if(
            !$this->request->existsParam('lastname') ||
            !$this->request->existsParam('firstname') ||
            !$this->request->existsParam('email') ||
            !$this->request->existsParam('message') ||
            !$this->request->existsParam('submit') ||
            $this->request->existsParam('country')
        ) {
            $this->redirect('contactez-moi', null, ['error' => "Un ou plusieurs champs contiennent une erreur. Veuillez vérifier et essayer à nouveau."]);
        }

        $lastname = $this->request->getParam('lastname');
        $firstname = $this->request->getParam('firstname');
        $email = $this->request->getParam('email');
        $message = $this->request->getParam('message');

        $to = Configuration::get('contact_email');
        $subject = "Vous avez un nouveau message de votre blog";
        $body = file_get_contents(Configuration::get('rootPath') . 'public/templates/email.html');
        $body = str_replace(
            [ '[[LASTNAME]]', '[[FIRSTNAME]]', '[[EMAIL]]', '[[MESSAGE]]' ],
            [ $lastname, $firstname, $email, $message ],
            $body
        );

        $headers = "From:" . Configuration::get('noreply_email') . "\n";
        $headers .= "Reply-To:" . $email . "\n";

        $result = mail($to, $subject, $body, $headers);

        if(!$result)
            $this->redirect('contactez-moi', null, ['error' => "Une erreur est survenue. Veuillez réessayer ultérieurement."]);

        $this->redirect('contactez-moi', null, ['success' => "Votre message a bien été envoyé. Je mets tout en oeuvre pour vous répondre au plus vite."]);
    }
}