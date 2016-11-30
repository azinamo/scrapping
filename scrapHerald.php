<?php
include_once 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

$client = new Goutte\Client();

$crawler = $client->request('GET', 'http://www.herald.co.zw/');

$h4_count = $crawler->filter('h4')->count();

$h4_contents = array();

if ($h4_count) {
    $crawler->filter('h4')->each(function(Symfony\Component\DomCrawler\Crawler $node, $i) use($h4_contents){
        $h4_contents[$i]['title'] = $node->filter('a')->text();
        $h4_contents[$i]['link'] = $node->filter('a')->attr('href');
        var_dump($h4_contents);
    });
}
print "<pre>";
  print_r($h4_contents);
print "</pre>";
echo "There are ".$h4_count." h4 counts";
//$aTags = $crawler->links();

exit();
