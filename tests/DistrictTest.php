<?php

namespace Wdy\District\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;
use Wdy\District\District;
use Wdy\District\Exception\HttpException;
use Wdy\District\Exception\InvalidArgumentException;

class DistrictTest extends TestCase
{
    public function testGetHttpClient()
    {
        /** arrange */
        $district = new District('mock-key');

        /** act */
        $actual = $district->getHttpClient();

        /* assert */
        $this->assertInstanceOf(ClientInterface::class, $actual);
    }

    public function testSetGuzzleOptions()
    {
        $district = new District('mock-key');
        $this->assertNull($district->getHttpClient()->getConfig('timeout'));

        $district->setGuzzleOptions(['timeout' => 5000]);
        $this->assertSame(5000, $district->getHttpClient()->getConfig('timeout'));
    }

    public function testGetDistrictWithJson()
    {
        /** arrange */
        $response = new Response(200, [], '{"success": true}');

        $client = \Mockery::mock(Client::class);
        $client->allows()->get('https://restapi.amap.com/v3/config/district', [
            'query' => [
                'key' => 'mock-key',
                'keywords' => '成都',
                'subdistrict' => 1,
                'output' => 'JSON',
            ],
        ])->andReturn($response);

        $district = \Mockery::mock(District::class, ['mock-key'])->makePartial();
        $district->allows()->getHttpClient()->andReturn($client);
        /** act */
        $actual = $district->getDistrict('成都', 1);

        /* assert */
        $this->assertSame(['success' => true], $actual);
    }

    public function testGetDistrictWithInvalidArgumentException()
    {
        $district = new District('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $district->getDistrict('成都', 5);

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    public function testGetDistrictWithHttpException()
    {
        /** arrange */
        $client = \Mockery::mock(Client::class);
        $client->allows()->get(new AnyArgs())
            ->andThrow(new \Exception('run time error'));
        $district = \Mockery::mock(District::class, ['mock-key'])->makePartial();
        $district->allows()->getHttpClient()->andReturn($client);

        /* assert */
        $this->expectException(HttpException::class);

        /* act */
        $district->getDistrict('成都');
    }
}
