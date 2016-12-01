<?php
include_once 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\CssSelector\CssSelectorConverter;

$client = new Goutte\Client();

$crawler = $client->request('GET', 'https://www.newsday.co.zw/');

$h3_count = $crawler->filter('section')->count();

$contents = array();

if ($h3_count) {
    $contents = $crawler->filter("div")->each(function(Symfony\Component\DomCrawler\Crawler $node, $x) use($contents){
        $class = $node->attr('class');
        if ($class == 'cat-posts fl' || $class == 'cat-posts fr') {

            $cat = $node->filter('h4')->filter('a')->text();
            if ($cat) {
                $contents[$cat]['articles'] = $node->filter("ul > li")->each(function(Symfony\Component\DomCrawler\Crawler $li, $i){
                    $text = $li->filter('a')->text();
                    if (!$text) {
                        $text = $li->filter('h5 > a')->text();
                    }
                    $href = $li->filter('a')->attr('href');
                    return array('text' => $text, 'link' => $href);
                });
            }
        }
        return $contents;
    });
    print "<pre>";
        print_r($contents);
    print "</pre>";
}

echo "There are ".$h3_count." h3 counts";
//$aTags = $crawler->links();

exit();
