<?php
include_once 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\CssSelector\CssSelectorConverter;

$client = new Goutte\Client();

$crawler = $client->request('GET', 'http://www.herald.co.zw/');

$h3_count = $crawler->filter('section')->count();

$contents = array();

if ($h3_count) {
    $crawler->filter("section")->each(function(Symfony\Component\DomCrawler\Crawler $node, $x){
        $class = $node->attr('class');
        if ($class == 'feat-cat') {
            $cat = null;
            $node->filter("header")->each(function(Symfony\Component\DomCrawler\Crawler $header, $i) use(&$cat, &$contents){
                $catHeader = $header->attr('class');
                if ($i == 0) $cat = $catHeader;
                if ($catHeader == 'cat-header') {
                    $contents[$cat]['title'] = $header->filter('a')->text();
                    $contents[$cat]['articles'] = array();
                } else {
                    $contents[$cat]['articles'][$i] = $header->filter('a')->text();
                }
                //        $h3_contents[$i]['title'] = $node->filter('a')->text();
                //        $h3_contents[$i]['link'] = $node->filter('a')->attr('href');

            });
            print "<pre>";
            print_r($contents);
            print "</pre>";
        }
    });
}

echo "There are ".$h3_count." h3 counts";
//$aTags = $crawler->links();

exit();
