<?php

namespace Meng\Soap;

/**
 * @internal
 */
class Soap extends \SoapClient
{
    private $endpoint;
    private $soapRequest;
    private $soapResponse;
    private $soapAction;
    private $soapVersion;

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        if (null !== $this->soapResponse) {
            return $this->soapResponse;
        }

        $this->endpoint = (string)$location;
        $this->soapAction = (string)$action;
        $this->soapVersion = (string)$version;
        $this->soapRequest = (string)$request;
        return '';
    }

    public function request($function_name, $arguments, $options, $input_headers)
    {
        $this->__soapCall($function_name, $arguments, $options, $input_headers);
        return new SoapRequest($this->endpoint, $this->soapAction, $this->soapVersion, $this->soapRequest);
    }

    public function response($response, $function_name, &$output_headers)
    {
        $this->soapResponse = $response;
        $response = $this->__soapCall($function_name, [], null, null, $output_headers);
        $this->soapResponse = null;
        return $response;
    }
}