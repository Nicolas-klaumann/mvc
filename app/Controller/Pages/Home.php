<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class Home extends Page
{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa home
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public static function getHome() {
        // Organização
        $obOrganization = new Organization;

        // View da home
        $content = View::render('pages/home', [
            'name' => $obOrganization->name
        ]);

        // Retorna a view da pagina
        return parent::getPage('HOME > WDEV', $content);
    }
}
