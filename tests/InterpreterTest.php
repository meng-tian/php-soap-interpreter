<?php

use Meng\Soap\Interpreter;
use Meng\Soap\SoapRequest;

class InterpreterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function requestWsdlArrayArguments()
    {
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $request = $interpreter->request('ConversionRate', [['FromCurrency' => 'AFA', 'ToCurrency' => 'ALL']]);
        $this->assertEquals('http://www.webservicex.net/CurrencyConvertor.asmx', $request->getEndpoint());
        $this->assertEquals('http://www.webserviceX.NET/ConversionRate', $request->getSoapAction());
        $this->assertEquals('1', $request->getSoapVersion());
        $this->assertNotEmpty($request->getSoapMessage());
        $this->assertContains('http://schemas.xmlsoap.org/soap/envelope/', $request->getSoapMessage());
        $this->assertContains('ConversionRate', $request->getSoapMessage());
        $this->assertContains('FromCurrency', $request->getSoapMessage());
        $this->assertContains('AFA', $request->getSoapMessage());
        $this->assertContains('ToCurrency', $request->getSoapMessage());
        $this->assertContains('ALL', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function requestWsdlObjectArguments()
    {
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $rate = new ConversionRate;
        $rate->FromCurrency = 'AFA';
        $rate->ToCurrency = 'ALL';
        $request = $interpreter->request('ConversionRate', [$rate]);
        $this->assertEquals('http://www.webservicex.net/CurrencyConvertor.asmx', $request->getEndpoint());
        $this->assertEquals('http://www.webserviceX.NET/ConversionRate', $request->getSoapAction());
        $this->assertEquals('1', $request->getSoapVersion());
        $this->assertNotEmpty($request->getSoapMessage());
        $this->assertContains('http://schemas.xmlsoap.org/soap/envelope/', $request->getSoapMessage());
        $this->assertContains('ConversionRate', $request->getSoapMessage());
        $this->assertContains('FromCurrency', $request->getSoapMessage());
        $this->assertContains('AFA', $request->getSoapMessage());
        $this->assertContains('ToCurrency', $request->getSoapMessage());
        $this->assertContains('ALL', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function requestWsdlInputHeaders()
    {
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $request = $interpreter->request(
            'ConversionRate',
            [['FromCurrency' => 'AFA', 'ToCurrency' => 'ALL']],
            null,
            [new SoapHeader('www.namespace.com', 'test_header', 'header_data')]
        );
        $this->assertEquals('http://www.webservicex.net/CurrencyConvertor.asmx', $request->getEndpoint());
        $this->assertEquals('http://www.webserviceX.NET/ConversionRate', $request->getSoapAction());
        $this->assertEquals('1', $request->getSoapVersion());
        $this->assertNotEmpty($request->getSoapMessage());
        $this->assertContains('http://schemas.xmlsoap.org/soap/envelope/', $request->getSoapMessage());
        $this->assertContains('www.namespace.com', $request->getSoapMessage());
        $this->assertContains('test_header', $request->getSoapMessage());
        $this->assertContains('header_data', $request->getSoapMessage());
        $this->assertContains('ConversionRate', $request->getSoapMessage());
        $this->assertContains('FromCurrency', $request->getSoapMessage());
        $this->assertContains('AFA', $request->getSoapMessage());
        $this->assertContains('ToCurrency', $request->getSoapMessage());
        $this->assertContains('ALL', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function requestTypeMapToXML()
    {
        $interpreter = new Interpreter(
            'http://www.webservicex.net/CurrencyConvertor.asmx?WSDL',
            [
                'typemap' => [
                    [
                        'type_name' => 'ConversionRate',
                        'type_ns' => 'http://www.webserviceX.NET/',
                        'to_xml' => function() {
                            return "<ConversionRate><FromCurrency>OLD</FromCurrency><ToCurrency>NEW</ToCurrency></ConversionRate>";
                        }
                    ]
                ]
            ]
        );

        $request = $interpreter->request('ConversionRate', [[]]);
        $this->assertEquals('http://www.webservicex.net/CurrencyConvertor.asmx', $request->getEndpoint());
        $this->assertEquals('http://www.webserviceX.NET/ConversionRate', $request->getSoapAction());
        $this->assertEquals('1', $request->getSoapVersion());
        $this->assertNotEmpty($request->getSoapMessage());
        $this->assertContains('http://schemas.xmlsoap.org/soap/envelope/', $request->getSoapMessage());
        $this->assertContains('ConversionRate', $request->getSoapMessage());
        $this->assertContains('FromCurrency', $request->getSoapMessage());
        $this->assertContains('OLD', $request->getSoapMessage());
        $this->assertContains('ToCurrency', $request->getSoapMessage());
        $this->assertContains('NEW', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function responseWsdl()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $responseMessage = $interpreter->response($responseMessage, 'ConversionRate');
        $this->assertInstanceOf('\StdClass', $responseMessage);
        $this->assertEquals(['ConversionRateResult' => '-1'], (array)$responseMessage);
    }

    /**
     * @test
     */
    public function responseWsdlOutputHeaders()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <m:Trans xmlns:m="http://www.w3schools.com/transaction/" soap:mustUnderstand="1">
      234
    </m:Trans>
  </soap:Header>
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $outputHeaders = [];
        $responseMessage = $interpreter->response($responseMessage, 'ConversionRate', $outputHeaders);
        $this->assertInstanceOf('\StdClass', $responseMessage);
        $this->assertEquals(['ConversionRateResult' => '-1'], (array)$responseMessage);
        $this->assertNotEmpty($outputHeaders);
    }

    /**
     * @test
     */
    public function responseWsdlClassMap()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL', ['classmap' => ['ConversionRateResponse' => '\ConversionRateResponse']]);
        $responseMessage = $interpreter->response($responseMessage, 'ConversionRate');
        $this->assertInstanceOf('\ConversionRateResponse', $responseMessage);
        $this->assertEquals(['ConversionRateResult' => '-1'], (array)$responseMessage);
    }

    /**
     * @test
     */
    public function responseTypeMapFromXML()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter(
            'http://www.webservicex.net/CurrencyConvertor.asmx?WSDL',
            [
                'typemap' => [
                    [
                        'type_name' => 'ConversionRateResponse',
                        'type_ns' => 'http://www.webserviceX.NET/',
                        'from_xml' => function() {
                            $rateResponse = new ConversionRateResponse;
                            $rateResponse->MockedResult = 100;
                            return $rateResponse;
                        }
                    ]
                ]
            ]
        );

        $responseMessage = $interpreter->response($responseMessage, 'ConversionRate');
        $this->assertInstanceOf('\ConversionRateResponse', $responseMessage);
        $this->assertEquals(['MockedResult' => 100], (array)$responseMessage);
    }

    /**
     * @test
     */
    public function responseWsdlDisableExceptions()
    {
        $interpreter = new Interpreter(null, ['uri'=>'www.uri.com', 'location'=>'www.location.com', 'exceptions' => false]);
        $responseMessage = <<<EOD
<SOAP-ENV:Envelope
  xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP-ENV:Body>
       <SOAP-ENV:Fault>
           <faultcode>SOAP-ENV:Server</faultcode>
           <faultstring>Server Error</faultstring>
           <detail>
               <e:myfaultdetails xmlns:e="Some-URI">
                 <message>
                   My application didn't work
                 </message>
                 <errorcode>
                   1001
                 </errorcode>
               </e:myfaultdetails>
           </detail>
       </SOAP-ENV:Fault>
   </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOD;
        $result = $interpreter->response($responseMessage, 'AnyMethod');
        $this->assertInstanceOf('\SoapFault', $result);
    }

    /**
     * @test
     */
    public function requestWsdlSoapV12()
    {
        $interpreter = new Interpreter('http://www.webservicex.net/airport.asmx?WSDL', ['soap_version' => SOAP_1_2]);
        $request = $interpreter->request('GetAirportInformationByCountry', [['country' => 'United Kingdom']]);
        $this->assertEquals('http://www.webservicex.net/airport.asmx', $request->getEndpoint());
        $this->assertEquals('http://www.webserviceX.NET/GetAirportInformationByCountry', $request->getSoapAction());
        $this->assertEquals('2', $request->getSoapVersion());
        $this->assertNotEmpty($request->getSoapMessage());
        $this->assertContains('http://www.w3.org/2003/05/soap-envelope', $request->getSoapMessage());
        $this->assertContains('GetAirportInformationByCountry', $request->getSoapMessage());
        $this->assertContains('country', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function responseWsdlSoapV12()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <GetAirportInformationByCountryResponse xmlns="http://www.webserviceX.NET">
            <GetAirportInformationByCountryResult>&lt;NewDataSet /&gt;</GetAirportInformationByCountryResult>
        </GetAirportInformationByCountryResponse>
    </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter('http://www.webservicex.net/airport.asmx?WSDL', ['soap_version' => SOAP_1_2]);
        $responseMessage = $interpreter->response($responseMessage, 'GetAirportInformationByCountry');
        $this->assertEquals(['GetAirportInformationByCountryResult' => '<NewDataSet />'], (array)$responseMessage);
    }

    /**
     * @test
     */
    public function requestWithoutWsdl()
    {
        $interpreter = new Interpreter(null, ['uri'=>'www.uri.com', 'location'=>'www.location.com']);
        $request = $interpreter->request('anything', [['one' => 'two', 'three' => 'four']]);
        $this->assertEquals('www.location.com', $request->getEndpoint());
        $this->assertEquals('www.uri.com#anything', $request->getSoapAction());
        $this->assertEquals('1', $request->getSoapVersion());
        $this->assertContains('one', $request->getSoapMessage());
        $this->assertContains('two', $request->getSoapMessage());
        $this->assertContains('three', $request->getSoapMessage());
        $this->assertContains('four', $request->getSoapMessage());
    }

    /**
     * @test
     */
    public function responseWithoutWsdl()
    {
        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://www.w3.org/2003/05/soap-envelope" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <soap:Body>
        <GetAirportInformationByCountryResponse xmlns="http://www.webserviceX.NET">
            <GetAirportInformationByCountryResult>&lt;NewDataSet /&gt;</GetAirportInformationByCountryResult>
        </GetAirportInformationByCountryResponse>
    </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter(null, ['uri'=>'www.uri.com', 'location'=>'www.location.com', 'soap_version' => SOAP_1_2]);
        $responseMessage = $interpreter->response($responseMessage, 'GetAirportInformationByCountry');
        $this->assertEquals('<NewDataSet />', $responseMessage);

        $responseMessage = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Header>
    <m:Trans xmlns:m="http://www.w3schools.com/transaction/" soap:mustUnderstand="1">
      234
    </m:Trans>
  </soap:Header>
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        $interpreter = new Interpreter(null, ['uri'=>'www.uri.com', 'location'=>'www.location.com']);
        $outputHeaders = [];
        $responseMessage = $interpreter->response($responseMessage, 'ConversionRate', $outputHeaders);
        $this->assertEquals('-1', $responseMessage);
        $this->assertNotEmpty($outputHeaders);
    }

    /**
     * @test
     */
    public function faultResponseNotAffectSubsequentRequests()
    {
        $interpreter = new Interpreter(null, ['uri'=>'www.uri.com', 'location'=>'www.location.com']);
        $responseMessage = <<<EOD
<SOAP-ENV:Envelope
  xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP-ENV:Body>
       <SOAP-ENV:Fault>
           <faultcode>SOAP-ENV:Server</faultcode>
           <faultstring>Server Error</faultstring>
           <detail>
               <e:myfaultdetails xmlns:e="Some-URI">
                 <message>
                   My application didn't work
                 </message>
                 <errorcode>
                   1001
                 </errorcode>
               </e:myfaultdetails>
           </detail>
       </SOAP-ENV:Fault>
   </SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOD;
        try {
            $interpreter->response($responseMessage, 'AnyMethod');
        } catch (Exception $e) {
        }
        $request = $interpreter->request('AnyMethod');
        $this->assertTrue($request instanceof SoapRequest);
    }
}

/** Test support only */
class  ConversionRate
{

}

/** Test support only */
class ConversionRateResponse
{

}
