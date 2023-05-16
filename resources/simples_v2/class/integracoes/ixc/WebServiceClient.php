<?php

namespace IXCsoft;

class WebserviceClientException extends \Exception{ }

class WebServiceClient implements \Iterator, \ArrayAccess{

    private $host;
    private $curl;
    private $headers = [];
    private $responseBody;
    private $decoded_resposta;
    private $responseHeaders;

    /**
     * api constructor.
     * @param string $host endereco da api
     * @param string $token token para autenticacao e obrigatorio
     * @param bool $selfSigned certificado autoassinado e obrigatorio
     */
    public function __construct($host, $token = null, $selfSigned = false){
        $this->host = $host;
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_HEADER, 1);
        if($token){
            curl_setopt($this->curl, CURLOPT_USERPWD, $token);
        }
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        if($selfSigned){
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($this->curl, CURLOPT_TIMEOUT,10);
    }

    public function __destruct(){
        curl_close($this->curl);
    }

    /**
     * Incluir cabecalho customizado na requisicao
     * @param string $key Nome do attributo
     * @param string $value Valor do attributo
     */
    public function setCabecalho($key, $value){
        $this->headers[] = sprintf("%s: %s", $key, $value);
    }

    public function getCabecalho(){
        return $this->headers;
    }

    /**
     * Fazer uma requisicao GET
     * @param string $url endereco da requisicao
     * @param array $params GET parametros da requisicao
     */
    public function get($url, array $params = []){
        
        $this->headers[] = "ixcsoft: listar";
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        $this->request($url);
    }

     /**
     * Fazer uma requisição POST
     * @param string $url endereço da requisição
     * @param string array com o conteúdo
     * @param bool $json
     */
    public function post($url, array $params = []){
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $params);
        $this->request($url);
    }

    /**
     * Fazer uma requisição PUT
     * @param string $url endereço da requisição
     * @param mixed $data array com o conteúdo
     * @param string $registro id do registro
     */
    public function put($url, $data, $registro){
        if ($json = !is_scalar($data)){
            $data = json_encode($data);
        }
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
        $this->request($url . '/' . $registro, $json);
    }

    private function request($target, $json = false){
        if(!strpos($target, '&')){
            $target = trim($target) . '/';
        }
        curl_setopt($this->curl, CURLOPT_URL, trim($this->host, '/') . '/' . trim($target));
        if ($json) {
            $this->headers[] = "Content-type: application/json";
        }
        if($this->headers){
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
            $this->headers = [];
        }

        $raw_response = curl_exec($this->curl);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $header = substr($raw_response, 0, $header_size);
        $this->responseHeaders = array_filter(explode(PHP_EOL, $header), function($line){
            return !preg_match("/^(HTTP|\r\n|Server|Date)/", $line) && trim($line);
        });

        $this->responseBody = substr($raw_response, $header_size);
    }

    /**
     * Retorna o conteudo da ultima requisicao
     * @param bool $json_decode
     * @return mixed
     */
    public function getRespostaConteudo($json_decode = true){
        
        if($json_decode == true){
            $decode = json_decode($this->responseBody, true);
            //return $this->decoded_resposta = $this->array_map_recursive('utf8_encode', $decode);
            return $decode;
        }else{
            return $this->decoded_resposta = $this->responseBody;
        }
        return $this->decoded_resposta = $json_decode ? json_decode($this->responseBody, true) : $this->responseBody;
    }

    /**
     * @param $callback funcao para executar recursivamente no array
     * @param $array array que devera ser executado a funcao
     * @return array
     */
    private function array_map_recursive($callback, $array){
        $func = function ($item) use (&$func, &$callback){
            return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
        };
        return array_map($func, $array);
    }

    /**
     * Retorna o cabeçalho da ultima requisicao
     * @return array
     */
    public function getResposta_cabecalho() {
        return $this->responseHeaders;
    }

    //Iterator methods
    public function current(){
        return current($this->decoded_resposta);
    }

    public function key(){
        return key($this->decoded_resposta);
    }

    public function next(){
        return next($this->decoded_resposta);
    }

    public function valid(){
        return is_array($this->decoded_resposta) && (key($this->decoded_resposta) !== NULL);
    }

    //ArrayAcess methods
    public function rewind(){
        $this->getRespostaConteudo(true);
        return reset($this->responseBody);
    }

    public function offsetExists($chave){
        $this->getRespostaConteudo(true);
        return is_array($this->responseBody) ?
                isset($this->responseBody[$chave]) : isset($this->responseBody->{$chave});
    }

    public function offsetGet($chave){
        $this->decode_resposta();
        if (!$this->offsetExists($chave)){
            return NULL;
        }

        return is_array($this->decoded_resposta) ?
                $this->decoded_resposta[$chave] : $this->decoded_resposta->{$chave};
    }

    public function offsetSet($chave, $valor){
        throw new WebserviceClientException("Decoded resposta data is immutable.");
    }

    public function offsetUnset($chave){
        throw new WebserviceClientException("Decoded resposta data is immutable.");
    }
}