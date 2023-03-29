<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;

class About extends Page
{

    /**
     * Método responsável por retornar o conteúdo (view) da nossa pagina de Sobre
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public static function getAbout() {
        // Organização
        $obOrganization = new Organization;

        // View da home
        $content = View::render('pages/about', [
            'name' => $obOrganization->name,
            'description' => $obOrganization->desciption,
            'site' => $obOrganization->site
        ]);

        // Retorna a view da pagina
        return parent::getPage('SOBRE > WDEV', $content);
    }
}
