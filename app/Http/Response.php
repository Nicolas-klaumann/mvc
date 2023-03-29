<?php

namespace App\Http;

class Response
{
    /**
     * Código de status HTTP
     * @var integer
     */
    private $httpCode = 200;

    /**
     * Undocumented variable
     *
     * @var array
     */
    private $headers = [];

    /**
     * Tipo de conteúdo que está sendo retornado
     * @var string
     */
    private $contentType = 'text/html';

    /**
     * Conteúdo do response
     * @var mixed
     */
    private $content;

    /**
     * Método responsável por iniciar a classe e definir os valores
     *
     * @param integer $httpCode
     * @param mixed $content
     * @param string $contentType
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    /**
     * Método responsável por alterar o contentType do response
     *
     * @param string $contentType
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public function setContentType($contentType) {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    /**
     * Método responsável por adicionar um registro no cabeçalho de response
     *
     * @param string $key
     * @param string $value
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public function addHeader($key, $value) {
        $this->headers[$key] = $value;
    }

    /**
     * Método resposável por enviar os headers para o navegador
     *
     * @return void
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    private function sendHeaders() {
        // status
        http_response_code($this->httpCode);

        // enviar headers
        foreach ($this->headers as $key => $value) {
            header($key.': '.$value);
        }
    }

    /**
     * Método responsável por enviar a resposta para o usuário
     *
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (27-03-2023)
     */
    public function sendResponse() {
        // envia os headers
        $this->sendHeaders();

        // imprimi o conteudo
        switch ($this->contentType) {
            case 'text/html':
                echo $this->content;
                exit;
        }
    }
}
