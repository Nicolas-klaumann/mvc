<?php

namespace App\Http;

use \Closure;
use \Exception;
use \ReflectionFunction;

class Router
{
    /**
     * URL completa do projeto (raiz)
     * @var string
     */
    private $url = '';

    /**
     * Prefixo de todas as rotas
     * @var string
     */
    private $prefix = '';

    /**
     * Indice de rotas
     * @var array
     */
    private $routes = [];

    /**
     * Instancia de request
     * @var Request
     */
    private $request;

    /**
     * Método responsável por iniciar a classe
     * @param string $url
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function __construct($url)
    {
        $this->request = new Request();
        $this->url = $url;
        $this->setPrefix();
    }

    /**
     * Método responsável por definir o prefixo das rotas
     *
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    private function setPrefix() {
        // informções da url atual
        $parseUrl = parse_url($this->url);

        //define o prefixo
        $this->prefix = $parseUrl['path'] ?? '';
    }

    /**
     * Método responsável por adicionar uma rota na classe
     *
     * @param string $method
     * @param string $route
     * @param array $params
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    private function addRoute($method, $route, $params = []) {
        // Validação dos parametros
        foreach ($params as $key => $value) {
            if($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
                continue;
            }
        }

        // Variaveis da rota
        $params['variables'] = [];

        // Padrão de validação das variaveis das rotas
        $patternVariable = '/{(.*?)}/';
        if(preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        // Padrão de validação da URL
        $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

        // Adiciona a rota dentro da classe
        $this->routes[$patternRoute][$method] = $params;
    }

    /**
     * Método resposável por definir uma rota de GET
     *
     * @param string $route
     * @param array $params
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function get($route, $params = []) {
        return $this->addRoute('GET', $route, $params);
    }

    /**
     * Método resposável por definir uma rota de POST
     *
     * @param string $route
     * @param array $params
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function post($route, $params = []) {
        return $this->addRoute('POST', $route, $params);
    }

    /**
     * Método resposável por definir uma rota de PUT
     *
     * @param string $route
     * @param array $params
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function put($route, $params = []) {
        return $this->addRoute('PUT', $route, $params);
    }

    /**
     * Método resposável por definir uma rota de DELETE
     *
     * @param string $route
     * @param array $params
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function delete($route, $params = []) {
        return $this->addRoute('DELETE', $route, $params);
    }

    /**
     * Método responsável por retornar a URI desconsiderando o prefixo
     *
     * @return string
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    private function getUri() {
        // URI da request
        $uri = $this->request->getUri();

        // Fatia a URI com o prefixo
        $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

        // Retorna a URI sem prefixo
        return end($xUri);
    }

    /**
     * Método responsável por retornar os dados da rota atual
     *
     * @return array
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    private function getRoute() {
        // URI
        $uri = $this->getUri();

        // Method
        $httpMethod = $this->request->getHttpMethod();

        // Valida as rotas
        foreach ($this->routes as $patternRoute => $methods) {
            // Verifica se a uri bate com o padrão
            if (preg_match($patternRoute, $uri, $matches)) {
                // Verifica o metodo
                if (isset($methods[$httpMethod])) {
                    // Remove a primeira posição
                    unset($matches[0]);

                    // Variaveis processadas
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // Retorno dos parametros da rota
                    return $methods[$httpMethod];
                }
                // Método não permitido/definido
                throw new Exception('Método não permitido', 405);
            }
        }
        // Url não encontrada
        throw new Exception('URL não encontrada', 404);
    }

    /**
     * Método responsável por executar a rota atual
     *
     * @return Response
     * @author Nicolas Klaumann <nicolas@conectra.com.br> (28-03-2023)
     */
    public function run() {
        try {
            // Obtem a rota atual
            $route = $this->getRoute();

            // Verifia o controlador
            if (!isset($route['controller'])) {
                throw new Exception('A URL não pode ser processada', 500);
            }
            // Argumentos da função
            $args = [];

            // Reflection
            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $paramater) {
                $name = $paramater->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            // Retorna a execução da função
            return call_user_func_array($route['controller'], $args);
        } catch (Exception $e) {
            return new Response($e->getCode(), $e->getMessage());
        }
    }
}
