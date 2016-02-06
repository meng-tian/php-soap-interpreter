<?php

namespace Meng\Soap;

class Interpreter
{
    private $soap;
    private $lastFunction;
    private $lastArguments;

    public function __construct($wsdl, array $options = [])
    {
        $this->soap = new Soap($wsdl, $options);
    }

    /**
     * Interpret SOAP method and arguments to an request envelope.
     *
     * @param string $function_name
     * @param array $arguments
     * @param array $options
     * @param array $input_headers
     * @return array
     */
    public function request($function_name, $arguments, $options = null, $input_headers = null)
    {
        $this->soap->feedRequest($function_name, $arguments, $options, $input_headers);
        $this->lastFunction = $function_name;
        $this->lastArguments = $arguments;
        return [
            'Endpoint' => $this->soap->getEndpoint(),
            'SoapAction' => $this->soap->getSoapAction(),
            'Version' => $this->soap->getVersion(),
            'Envelope' => $this->soap->getRequest()
        ];
    }

    /**
     * Interpret a response envelope to PHP objects.
     *
     * @param string $response
     * @return mixed
     */
    public function response($response)
    {
        $this->soap->feedResponse($response);
        $response = $this->soap->__call($this->lastFunction, $this->lastArguments);
        $this->soap->feedResponse(null);
        return $response;
    }
}