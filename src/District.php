<?php

namespace Wdy\District;


use GuzzleHttp\Client;
use Wdy\District\Exception\HttpException;
use Wdy\District\Exception\InvalidArgumentException;

class District
{
    private $key;

    private $url;

    private $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
        $this->url = "https://restapi.amap.com/v3/config/district";
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @param string $keywords
     * @param int $subdistrict
     * @param string $output
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getDistrict(string $keywords, int $subdistrict = 0, string $output = 'JSON')
    {
        $this->argValidate($subdistrict, $output);

        $client = $this->getHttpClient();

        $parameters = [
            'key' => $this->key,
            'keywords' => $keywords,
            'subdistrict' => $subdistrict,
            'output' => strtoupper($output)
        ];

        try {
            $response = $client->get($this->url, [
                'query' => $parameters
            ])->getBody()->getContents();
            return 'JSON' === $output ? \json_decode($response, true) : $response;
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * @param $subdistrict
     * @param $output
     * @throws InvalidArgumentException
     */
    private function argValidate($subdistrict, $output)
    {
        if (!in_array((int)$subdistrict, [0, 1, 2, 3])) {
            throw new InvalidArgumentException('subdistrict参数错误');
        }

        if (!in_array(strtoupper($output), ['JSON', 'XML'])) {
            throw new InvalidArgumentException('output参数错误');
        }
    }
}