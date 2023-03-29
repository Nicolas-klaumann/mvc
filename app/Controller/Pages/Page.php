<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page
{
    /**
     * Método responsável por renderizar o topo da pagina
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    private static function getHeader() {
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o rodapé da pagina
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    private static function getFooter() {
        return View::render('pages/footer');
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa pagina generica
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public static function getPage($title, $content) {
        return View::render('pages/page', [
            'title' => $title,
            'header' => self::getHeader(),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }
}
