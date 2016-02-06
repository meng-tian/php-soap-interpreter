<?php

use Meng\Soap\Interpreter;

class InterpreterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider responseEnvelope
     */
    public function withWsdl($response)
    {
        $interpreter = new Interpreter('http://www.webservicex.net/CurrencyConvertor.asmx?WSDL');
        $request = $interpreter->request('ConversionRate', [['FromCurrency' => 'AFA', 'ToCurrency' => 'ALL']]);
        $this->assertArrayHasKey('Endpoint', $request);
        $this->assertArrayHasKey('SoapAction', $request);
        $this->assertArrayHasKey('Version', $request);
        $this->assertArrayHasKey('Envelope', $request);

        $response = $interpreter->response($response);
        $this->assertEquals(['ConversionRateResult' => '-1'], (array)$response);
    }

    /**
     * @test
     * @dataProvider responseEnvelope
     */
    public function withoutWsdl($response)
    {
        $interpreter = new Interpreter(null, ['uri'=>'', 'location'=>'']);
        $request = $interpreter->request('anything', [['a' => 'b', 'c' => 'd']]);
        $this->assertArrayHasKey('Endpoint', $request);
        $this->assertArrayHasKey('SoapAction', $request);
        $this->assertArrayHasKey('Version', $request);
        $this->assertArrayHasKey('Envelope', $request);

        $response = $interpreter->response($response);
        $this->assertEquals(-1, $response);
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
