<?php
/**
 * Created by IntelliJ IDEA.
 * User: dw01f
 * Date: 25.09.18
 * Time: 12:29
 */
require_once ROOT.'/vendor/autoload.php';

try {
    $db = \Engine\lib\DB::getInstance();
    $db->getDBConnection();
} catch (Exception $e) {
    echo $e->getMessage();
}


$service = new \MiniUrl\Service\ShortUrlService('http://192.168.12.204:88', new \MiniUrl\Repository\PdoRepository($db));
$linkService = new \Engine\lib\LinkService($service);


if ($linkService->getCurrentRequestMethod() === \Engine\lib\LinkService::POST) {
    $requestBody = file_get_contents('php://input');
    $urlToShorten = json_decode($requestBody);
    header('Content-type: application/json');
    echo json_encode([
        'url' => $linkService->getShortLink($urlToShorten->url)
    ]);
}
elseif ($linkService->getCurrentRequestMethod() === \Engine\lib\LinkService::GET) {
    try {
        $fullUrl = $linkService->getFullLink();
    } catch (\Engine\lib\cacheException $e) {
        echo $e->getMessage();
    }
    if (isset($fullUrl) && $fullUrl)
        header($fullUrl, true, 301);
    else
        exit;
}