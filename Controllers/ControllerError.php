<?php

use Texier\Framework\Configuration;
use Texier\Framework\Controller;

class ControllerError extends Controller
{

    public function index()
    {
        if($this->request->existsParam('id')) {
            http_response_code(intval($this->request->getParam('id')));
            $message = Configuration::get('error_' . $this->request->getParam('id'));
        }

        $this->generateView([
            'status_code' => $this->request->getParam('id') ?: false,
            'error_message' => $message ?? "Une erreur inconnu est survenue. Veuillez contacter l'administrateur du site."
        ]);
    }
}
