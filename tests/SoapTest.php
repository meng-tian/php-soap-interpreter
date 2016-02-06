<?php

use Meng\Soap\Soap;

class SoapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
   public function requestWithWsdl()
   {
       $soap = new Soap('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');

       $this->assertNull($soap->getEndpoint());
       $this->assertNull($soap->getRequest());
       $this->assertNull($soap->getSoapAction());
       $this->assertNull($soap->getVersion());
       $soap->feedRequest('ConversionRate', [['FromCurrency' => 'AFA', 'ToCurrency' => 'ALL']]);
       $this->assertNotNull($soap->getEndpoint());
       $this->assertNotNull($soap->getRequest());
       $this->assertNotNull($soap->getSoapAction());
       $this->assertNotNull($soap->getVersion());
   }

    /**
     * @test
     */
    public function requestWithoutWsdl()
    {
        $soap = new Soap(null, ['uri'=>'', 'location'=>'www.location.com']);
        $this->assertNull($soap->getEndpoint());
        $this->assertNull($soap->getRequest());
        $this->assertNull($soap->getSoapAction());
        $this->assertNull($soap->getVersion());
        $soap->feedRequest('ConversionRate', [['FromCurrency' => 'AFA', 'ToCurrency' => 'ALL']]);
        $this->assertEquals('www.location.com', $soap->getEndpoint());
        $this->assertNotNull($soap->getRequest());
        $this->assertNotNull($soap->getSoapAction());
        $this->assertNotNull($soap->getVersion());
    }

    /**
     * @test
     * @dataProvider responseEnvelope
     */
    public function responseWithWsdl($envelope)
    {
        $soap = new Soap('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $soap->feedResponse($envelope);
        $result = (array)$soap->ConversionRate();
        $this->assertEquals(['ConversionRateResult' => '-1'], $result);
    }

    /**
     * @test
     * @dataProvider responseEnvelope
     */
    public function responseWithoutWsdl($envelope)
    {
        $soap = new Soap(null, ['location'=>'', 'uri'=>'']);
        $soap->feedResponse($envelope);
        $this->assertEquals(-1, $soap->anything());
    }

    public function responseEnvelope()
    {
        $responseEnvelope = <<<EOD
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <ConversionRateResponse xmlns="http://www.webserviceX.NET/">
      <ConversionRateResult>-1</ConversionRateResult>
    </ConversionRateResponse>
  </soap:Body>
</soap:Envelope>
EOD;
        return [
            [$responseEnvelope]
        ];
    }
}
