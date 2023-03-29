<?php

namespace App\Utils;

class View
{
    /**
     * Variáveis padrões da View
     * @var array
     */
    private static $vars = [];

    /**
     * Método resposável por definir os dados iniciais da classe
     *
     * @param array $vars
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public static function init($vars = []) {
        self::$vars = $vars;
    }

    /**
     * Método responsável por retornar o conteúdo de uma view
     *
     * @param string $view
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    private static function getContentView($view) {
        $file = __DIR__.'/../../resources/view/'.$view.'.html';
        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo renderizado de uma view
     *
     * @param string $view
     * @param array $vars (string/numeric)
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public static function render($view, $vars = []) {
        // Conteúdo da view
        $contentView = self::getContentView($view);

        // Merge de variaveis da view
        $vars = array_merge(self::$vars, $vars);

        // chaves do array de variaveis
        $keys = array_keys($vars);
        $keys = array_map(function($item) {
            return '{{'.$item.'}}';
        }, $keys);

        // retorna o conteúdo renderizado
        return str_replace($keys, array_values($vars), $contentView);
    }
}
