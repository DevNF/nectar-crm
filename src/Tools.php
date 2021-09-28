<?php

namespace NFService\NectarCRM;

use Exception;

/**
 * Classe Tools
 *
 * Classe responsável pela comunicação com a API Nectar CRM
 *
 * @category  NFService
 * @package   NFService\NectarCRM\Tools
 * @author    Diego Almeida <diego.feres82 at gmail dot com>
 * @copyright 2021 NFSERVICE
 * @license   https://opensource.org/licenses/MIT MIT
 */
class Tools
{
    /**
     * URL base para comunicação com a API
     *
     * @var string
     */
    public static $API_URL = 'https://app.nectarcrm.com.br/crm/api/1';

    /**
     * Variável responsável por armazenar os dados a serem utilizados para comunicação com a API
     * Dados como token, ambiente(produção ou homologação) e debug(true|false)
     *
     * @var array
     */
    private $config = [
        'token' => '',
        'debug' => false,
        'upload' => false,
        'decode' => true
    ];

    /**
     * Define se a classe realizará um upload
     *
     * @param bool $isUpload Boleano para definir se é upload ou não
     *
     * @access public
     * @return void
     */
    public function setUpload(bool $isUpload) :void
    {
        $this->config['upload'] = $isUpload;
    }

    /**
     * Define se a classe realizará o decode do retorno
     *
     * @param bool $decode Boleano para definir se fa decode ou não
     *
     * @access public
     * @return void
     */
    public function setDecode(bool $decode) :void
    {
        $this->config['decode'] = $decode;
    }

    /**
     * Função responsável por definir se está em modo de debug ou não a comunicação com a API
     * Utilizado para pegar informações da requisição
     *
     * @param bool $isDebug Boleano para definir se é produção ou não
     *
     * @access public
     * @return void
     */
    public function setDebug(bool $isDebug) :void
    {
        $this->config['debug'] = $isDebug;
    }

    /**
     * Função responsável por definir o token a ser utilizado para comunicação com a API
     *
     * @param string $token Token para autenticação na API
     *
     * @access public
     * @return void
     */
    public function setToken(string $token) :void
    {
        $this->config['token'] = $token;
    }

    /**
     * Recupera se é upload ou não
     *
     *
     * @access public
     * @return bool
     */
    public function getUpload() : bool
    {
        return $this->config['upload'];
    }

    /**
     * Recupera se faz decode ou não
     *
     *
     * @access public
     * @return bool
     */
    public function getDecode() : bool
    {
        return $this->config['decode'];
    }

    /**
     * Retorna o token utilizado para comunicação com a API
     *
     * @access public
     * @return string
     */
    public function getToken() :string
    {
        return $this->config['token'];
    }

    /**
     * Retorna os cabeçalhos padrão para comunicação com a API
     *
     * @access private
     * @return array
     */
    private function getDefaultHeaders() :array
    {
        $headers = [
            'Access-Token: '.$this->config['token'],
            'Accept: application/json',
        ];

        if (!$this->config['upload']) {
            $headers[] = 'Content-Type: application/json';
        } else {
            $headers[] = 'Content-Type: multipart/form-data';
        }
        return $headers;
    }

    /**
     * Consulta os contatos
     *
     * @access public
     * @return array
     */
    public function consultaContatos(array $params = []): array
    {
        try {
            $dados = $this->get("contatos", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta um contato por id
     *
     * @param int $id ID do contato no NectarCRM
     *
     * @access public
     * @return array
     */
    public function consultaContatoId(int $id, array $params = []): array
    {
        try {
            $dados = $this->get("contatos/$id", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta um contato por E-mail
     *
     * @param string $email E-mail do contato
     *
     * @access public
     * @return array
     */
    public function consultaContatoEmail(string $email = '', array $params = []): array
    {
        try {
            $dados = $this->get("contatos/email/$email", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta um contato por telefone
     *
     * @param string $telefone Telefone do contato
     *
     * @access public
     * @return array
     */
    public function consultaContatoTelefone(string $telefone = '', array $params = []): array
    {
        try {
            $dados = $this->get("contatos/telefone/$telefone", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta um contato por CNPJ
     *
     * @param string $cnpj CNPJ do contato
     *
     * @access public
     * @return array
     */
    public function consultaContatoCnpj(string $cnpj = '', array $params = []): array
    {
        try {
            $dados = $this->get("contatos/cnpj/$cnpj", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta um contato por CPF
     *
     * @param string $cpf CPF do contato
     *
     * @access public
     * @return array
     */
    public function consultaContatoCpf(string $cpf = '', array $params = []): array
    {
        try {
            $dados = $this->get("contatos/cpf/$cpf", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Cadastra um novo contato
     *
     * @param array $dados Dados para o cadastro do contato
     *
     * @access public
     * @return array
     */
    public function cadastraContato(array $dados, array $params = []): array
    {
        $errors = [];
        if (!isset($dados['nome']) || empty($dados['nome'])) {
            $errors[] = 'É obrigatório o envio do nome do contato';
        }
        if (!isset($dados['constante']) || empty($dados['constante'])) {
            $errors[] = 'É obrigatório o envio do tipo do contato';
        } else if (!in_array((int) $dados['constante'], [0, 1, 2, 3, 5])) {
            $errors[] = 'Os tipos de contatos aceitos são 0, 1, 2, 3 e 5';
        }
        if (!empty($errors)) {
            throw new Exception(implode("\r\n", $errors), 1);
        }

        try {
            $dados = $this->post('contatos', $dados, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw $error;
        }
    }

    /**
     * Atualiza um contato existente
     *
     * @param int $id ID do contato no NectarCRM
     * @param array $dados Dados para atualização do contato
     *
     * @access public
     * @return array
     */
    public function atualizaContato(int $id, array $dados, array $params = []): array
    {
        $errors = [];
        if (!isset($dados['nome']) || empty($dados['nome'])) {
            $errors[] = 'É obrigatório o envio do nome do contato';
        }
        if (!isset($dados['constante']) || empty($dados['constante'])) {
            $errors[] = 'É obrigatório o envio do tipo do contato';
        } else if (!in_array((int) $dados['constante'], [0, 1, 2, 3, 5])) {
            $errors[] = 'Os tipos de contatos aceitos são 0, 1, 2, 3 e 5';
        }
        if ((!isset($dados['cpf']) || empty($dados['cpf'])) && (!isset($dados['cnpj']) || empty($dados['cnpj']))) {
            $errors[] = 'Informe ou CPF/CNPJ do contato';
        }
        if (!empty($errors)) {
            throw new Exception(implode("\r\n", $errors), 1);
        }

        try {
            $dados = $this->put('contatos/'.$id, $dados, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Deleta um contato
     *
     * @param int $id ID do contato no NectarCRM
     *
     * @access public
     * @return array
     */
    public function deletaContato(int $id, array $params = [])
    {
        if (!isset($id) || empty($id)) {
            throw new Exception("O ID do contato é obrigatório para exclusão", 1);
        }

        try {
            $dados = $this->delete('contatos/'.$id, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta as oportunidades
     *
     * @access public
     * @return array
     */
    public function consultaOportunidades(array $params = []): array
    {
        try {
            $dados = $this->get("oportunidades", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta uma oportunidade pelo ID da mesma
     *
     * @param int $id ID da oportunidade no NectarCRM
     *
     * @access public
     * @return array
     */
    public function consultaOportunidadeId(int $id, array $params = []): array
    {
        try {
            $dados = $this->get("oportunidades/$id", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta uma oportunidade pelo e-mail cadastrado na mesma
     *
     * @param string $email E-mail cadastrado na oportunidade
     *
     * @access public
     * @return array
     */
    public function consultaOportunidadeEmail(string $email, array $params = []): array
    {
        try {
            $dados = $this->get("oportunidades/email/$email", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta uma oportunidade pelo telefone cadastrado na mesma
     *
     * @param string $telefone Telefone cadastrado na oportunidade
     *
     * @access public
     * @return array
     */
    public function consultaOportunidadeTelefone(string $telefone, array $params = []): array
    {
        try {
            $dados = $this->get("oportunidades/telefone/$telefone", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Consulta uma oportunidade pelo id do contato cadastrado na mesma
     *
     * @param int $contatoId ID do contato cadastrado na oportunidade
     *
     * @access public
     * @return array
     */
    public function consultaOportunidadeContatoId(int $contatoId, array $params = []): array
    {
        try {
            $dados = $this->get("oportunidades/contatoId/$contatoId", $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Cadastra uma nova oportunidade
     *
     * @param array $dados Dados para o cadastro da oportunidade
     *
     * @access public
     * @return array
     */
    public function cadastraOportunidade(array $dados, array $params = []): array
    {
        $errors = [];
        if (!isset($dados['cliente']['id']) || empty($dados['cliente']['id'])) {
            $errors[] = 'É obrigatório o envio do ID do Cliente (Contato)';
        }
        if (!isset($dados['nome']) || empty($dados['nome'])) {
            $errors[] = 'É obrigatório o envio do nome da oportunidade';
        }
        if (!empty($errors)) {
            throw new Exception(implode("\r\n", $errors), 1);
        }

        try {
            $dados = $this->post('oportunidades', $dados, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw $error;
        }
    }

    /**
     * Deleta um oportunidade
     *
     * @param int $id ID da oportunidade no NectarCRM
     *
     * @access public
     * @return array
     */
    public function deletaOportunidade(int $id, array $params = [])
    {
        if (!isset($id) || empty($id)) {
            throw new Exception("O ID da oportunidade é obrigatório para exclusão", 1);
        }

        try {
            $dados = $this->delete('oportunidades/'.$id, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw new Exception($error, 1);
        }
    }

    /**
     * Atualiza uma oportunidade
     *
     * @param int $id ID da oportunidade no NectarCRM
     * @param array $dados Dados para o cadastro da oportunidade
     *
     * @access public
     * @return array
     */
    public function atualizaOportunidade(int $id, array $dados, array $params = []): array
    {
        $errors = [];
        if (!isset($dados['cliente']['id']) || empty($dados['cliente']['id'])) {
            $errors[] = 'É obrigatório o envio do ID do Cliente (Contato)';
        }
        if (!isset($dados['nome']) || empty($dados['nome'])) {
            $errors[] = 'É obrigatório o envio do nome da oportunidade';
        }
        if (!empty($errors)) {
            throw new Exception(implode("\r\n", $errors), 1);
        }

        try {
            $dados = $this->put("oportunidades/$id", $dados, $params);

            if ($dados['httpCode'] == 200) {
                return $dados;
            }

            if (isset($dados['body']->message)) {
                throw new Exception($dados['body']->message, 1);
            }

            if (isset($dados['body']->mensagens)) {
                throw new Exception(implode("\r\n", $dados['body']->mensagens), 1);
            }

            throw new Exception('Ocorreu um erro interno', 1);
        } catch (Exception $error) {
            throw $error;
        }
    }

    /**
     * Execute a GET Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function get(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders()
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a POST Request
     *
     * @param string $path
     * @param string $body
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function post(string $path, array $body = [], array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => !$this->config['upload'] ? json_encode($body) : $body,
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders()
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a PUT Request
     *
     * @param string $path
     * @param string $body
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function put(string $path, array $body = [], array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders(),
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($body)
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a DELETE Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function delete(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_HTTPHEADER => $this->getDefaultHeaders(),
            CURLOPT_CUSTOMREQUEST => "DELETE"
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = array_merge($opts[CURLOPT_HTTPHEADER], $headers);
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Execute a OPTION Request
     *
     * @param string $path
     * @param array $params
     * @param array $headers Cabeçalhos adicionais para requisição
     *
     * @access protected
     * @return array
     */
    protected function options(string $path, array $params = [], array $headers = []) :array
    {
        $opts = [
            CURLOPT_CUSTOMREQUEST => "OPTIONS"
        ];

        if (!empty($headers)) {
            $opts[CURLOPT_HTTPHEADER] = $headers;
        }

        $exec = $this->execute($path, $opts, $params);

        return $exec;
    }

    /**
     * Função responsável por realizar a requisição e devolver os dados
     *
     * @param string $path Rota a ser acessada
     * @param array $opts Opções do CURL
     * @param array $params Parametros query a serem passados para requisição
     *
     * @access protected
     * @return array
     */
    protected function execute(string $path, array $opts = [], array $params = []) :array
    {
        if (!preg_match("/^\//", $path)) {
            $path = '/' . $path;
        }

        $url = self::$API_URL.$path;

        $curlC = curl_init();

        if (!empty($opts)) {
            curl_setopt_array($curlC, $opts);
        }

        if (!empty($params)) {
            $paramsJoined = [];

            foreach ($params as $param) {
                if (isset($param['name']) && !empty($param['name']) && isset($param['value']) && !empty($param['value'])) {
                    $paramsJoined[] = urlencode($param['name'])."=".urlencode($param['value']);
                }
            }

            if (!empty($paramsJoined)) {
                $params = '?'.implode('&', $paramsJoined);
                $url = $url.$params;
            }
        }

        curl_setopt($curlC, CURLOPT_URL, $url);
        curl_setopt($curlC, CURLOPT_RETURNTRANSFER, true);
        if (!empty($dados)) {
            curl_setopt($curlC, CURLOPT_POSTFIELDS, json_encode($dados));
        }
        $retorno = curl_exec($curlC);
        $info = curl_getinfo($curlC);
        $return["body"] = ($this->config['decode'] || !$this->config['decode'] && $info['http_code'] != '200') ? json_decode($retorno) : $retorno;
        $return["httpCode"] = curl_getinfo($curlC, CURLINFO_HTTP_CODE);
        if ($this->config['debug']) {
            $return['info'] = curl_getinfo($curlC);
        }
        curl_close($curlC);

        return $return;
    }
}
