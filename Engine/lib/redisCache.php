<?php
/**
 * Created by IntelliJ IDEA.
 * User: dw01f
 * Date: 26.09.18
 * Time: 9:20
 */

namespace Engine\lib;


use Cache\Adapter\Redis\RedisCachePool;
use Psr\Log\InvalidArgumentException;

class redisCache
{
    const REDIS_HOST = '127.0.0.1';
    const REDIS_PORT = 6379;
    const REDIS_LIFETIME = 3600;

    protected $redisCache;

    public function __construct()
    {
        $client = new \Redis();
        $client->connect(self::REDIS_HOST, self::REDIS_PORT);
        $pool = new RedisCachePool($client);
        $this->redisCache = $pool;
    }

    public function checkRedisCache($url)
    {
        $hash = $this->getURLHash($url);
        try {
            if ($cached = $this->redisCache->get($hash)) {
                return $cached;
            }
            return false;
        } catch (InvalidArgumentException $e) {
            throw new cacheException('Проблемы с кэш!.Код: ' . $e->getCode());
        }
    }

    public function setRedisCache($link,$fulllink)
    {
        try {
            $hash = $this->getURLHash($link);
            $this->redisCache->set($hash, $fulllink, self::REDIS_LIFETIME);
            $this->redisCache->commit();
            return true;
        } catch (InvalidArgumentException $e) {
            throw new cacheException('Проблемы с кэш!.Код: ' . $e->getCode());
        }
    }

    private function getURLHash($url)
    {
        return sha1($url);
    }

}