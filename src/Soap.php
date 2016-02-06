<?php

namespace Meng\Soap;

/**
 * @internal
 */
class Soap extends \SoapClient
{
    private $request;
    private $response;
    private $endpoint;
    private $soapAction;
    private $version;

    public function feedRequest($function_name, $arguments, $options = null, $input_headers = null)
    {
        $this->__soapCall($function_name, $arguments, $options, $input_headers);
    }

    public function feedResponse($response)
    {
        $this->response = $response;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getSoapAction()
    {
        return $this->soapAction;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        if (null !== $this->response) {
            return $this->response;
        }

        $this->request = (string)$request;
        $this->endpoint = (string)$location;
        $this->soapAction = (string)$action;
        $this->version = (string)$version;
        return '';
    }
}