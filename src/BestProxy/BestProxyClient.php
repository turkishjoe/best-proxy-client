<?php
/**
 * TODO:
 * Created by PhpStorm.
 * User: prog12
 * Date: 05.09.18
 * Time: 0:37
 */

namespace BestProxy;

class BestProxyClient
{
    const ALLOWED_TYPES = ['https', 'http'];

    const API_BEST_PROXY_URL = 'http://api.best-proxies.ru/proxylist.json';

    protected $apiKey ;

    public function __construct(string $bestProxyApi)
    {
        $this->apiKey = $bestProxyApi;
    }


    /**
     *
     * @param       $limit
     * @param array $params
     *
     * @return mixed
     */
    public function getProxyList($limit, $params = [])
    {
        $params = array_merge([
            'key'=>$this->apiKey,
            'type'=>implode(',', self::ALLOWED_TYPES),
            'limit'=> $limit
        ], $params);

        $url = self::API_BEST_PROXY_URL . '?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);

        if($info['http_code'] != 200)
        {
            throw new BestProxyException("Bad request");
        }

        curl_close($ch);

        return json_decode($result, true);
    }
}