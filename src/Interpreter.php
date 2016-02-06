<?php

namespace Meng\Soap;

class Interpreter
{
    private $requestInterpreter;
    private $responseInterpreter;

    public function __construct($wsdl, array $options = [])
    {
        $this->requestInterpreter = new Soap($wsdl, $options);
        $this->responseInterpreter = new Soap(null, array_merge($options, ['location'=>'', 'uri'=>'']));
    }

    /**
     * Interpret SOAP method and arguments to an request envelope.
     *
     * @param $function_name
     * @param $arguments
     * @param null $options
     * @param null $input_headers
     * @return array
     */
    public function request($function_name, $arguments, $options = null, $input_headers = null)
    {
        $this->requestInterpreter->feedRequest($function_name, $arguments, $options, $input_headers);
        return [
            'Endpoint' => $this->requestInterpreter->getEndpoint(),
            'SoapAction' => $this->requestInterpreter->getSoapAction(),
            'Version' => $this->requestInterpreter->getVersion(),
            'Envelope' => $this->requestInterpreter->getRequest()
        ];
    }

    /**
     * Interpret a response envelope to PHP objects.
     *
     * @param $response
     * @return mixed
     */
    public function response($response)
    {
        $this->responseInterpreter->feedResponse($response);
        return $this->responseInterpreter->interpret();
    }
}