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
     * Interpret SOAP method and arguments to a request envelope.
     *
     * @param string $function_name
     * @param array $arguments
     * @param array $options
     * @param mixed $input_headers
     * @return array
     */
    public function request($function_name, array $arguments, array $options = null, $input_headers = null)
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
     * @param array $output_headers
     * @return mixed
     */
    public function response($response, array &$output_headers = null)
    {
        $this->soap->feedResponse($response);
        $response = $this->soap->__soapCall($this->lastFunction, $this->lastArguments, null, null, $output_headers);
        $this->soap->feedResponse(null);
        return $response;
    }
}