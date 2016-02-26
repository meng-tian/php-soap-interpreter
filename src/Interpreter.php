<?php

namespace Meng\Soap;

class Interpreter
{
    private $soap;

    public function __construct($wsdl, array $options = [])
    {
        $this->soap = new Soap($wsdl, $options);
    }

    /**
     * Interpret the given method and arguments to a SOAP request message.
     *
     * @param string $function_name
     * @param array $arguments
     * @param array $options
     * @param mixed $input_headers
     * @return SoapRequest
     */
    public function request($function_name, array $arguments = [], array $options = null, $input_headers = null)
    {
        return $this->soap->request($function_name, $arguments, $options, $input_headers);
    }

    /**
     * Interpret a SOAP response message to PHP objects.
     *
     * @param string $response
     * @param string $function_name
     * @param array $output_headers
     * @return mixed
     */
    public function response($response, $function_name, array &$output_headers = null)
    {
        return $this->soap->response($response, $function_name, $output_headers);
    }
}