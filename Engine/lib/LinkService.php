<?php
/**
 * Created by IntelliJ IDEA.
 * User: dw01f
 * Date: 25.09.18
 * Time: 14:39
 */

namespace Engine\lib;

use MiniUrl\Service\ShortUrlService;

class LinkService
{
    protected $linkProcessor;
    const POST = 'POST';
    const GET = 'GET';

    protected $requestMethod;
    protected $redisCache;

    public function __construct(ShortUrlService $linkProcessor)
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case self::GET:
                $this->requestMethod = self::GET;
                break;
            case self::POST:
                $this->requestMethod = self::POST;
                break;
        }
        $this->redisCache = new redisCache();
        $this->linkProcessor = $linkProcessor;
    }

    public function getCurrentRequestMethod()
    {
        return $this->requestMethod;
    }

    public function getFullLink()
    {
        $fullurl = $this->fullUrl();
        try {
            if ($url = $this->redisCache->checkRedisCache($fullurl)) {
                return $url;
            }
            $link = $this->linkProcessor->expand($_SERVER['REQUEST_URI']);

            if ($link === null) {
                throw new cacheException("Ой... Не могу найти такую сылку :(", 404);
            }
            $fullLink = $link->getLongUrl();
            $this->redisCache->setRedisCache($fullurl, $fullLink);
            return $fullLink;
        } catch (cacheException $e) {
            echo 'Возникла проблема. Сообщение: '.$e->getMessage().' Код: '.$e->getCode();
        }
    }

    public function getShortLink($link)
    {
        $link = $this->linkProcessor->shorten($link);
        return $link->getShortUrl();
    }


    public function urlOrigin($s, $use_forwarded_host = false)
    {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] === 'on');
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $httpHost = isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : $httpHost;
        $host = ($host !== null) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
    }

    public function fullUrl($use_forwarded_host = false)
    {
        return $this->urlOrigin($_SERVER, $use_forwarded_host) . $_SERVER['REQUEST_URI'];
    }

}