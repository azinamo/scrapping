<?php
include_once 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\CssSelector\CssSelectorConverter;

$client = new Goutte\Client();

$crawler = $client->request('GET', 'https://www.dailynews.co.zw/');

$h3_count = $crawler->filter('div')->count();

$contents = array();

if ($h3_count) {
    $contents = $crawler->filter("div")->each(function(Symfony\Component\DomCrawler\Crawler $node, $x) use($contents){
        $class = $node->attr('class');
        if ($class == 'categories') {

            $cat = $node->filter('h4')->filter('a')->text();
            if ($cat) {
                $contents[$cat]['articles'] = $node->filter("ul > li")->each(function(Symfony\Component\DomCrawler\Crawler $li, $i){
                    $text = $li->filter('a')->text();
                    if (!$text) {
                        $text = $li->filter('h5 > a')->text();
                    }
                    $href = $li->filter('a')->attr('href');
                    return array('text' => $text, 'link' => "https://www.dailynews.co.zw".$href);
                });
            }
        }
        if ($class == 'topstory') {

            $contents['TopStory']['articles'] = $node->filter("a")->each(function(Symfony\Component\DomCrawler\Crawler $a, $i){
                $text = $a->text();
                $href = $a->attr('href');
                return array('text' => $text, 'link' => "https://www.dailynews.co.zw".$href);
            });
        }
        return $contents;
    });
    $contents = $crawler->filter("ul")->each(function(Symfony\Component\DomCrawler\Crawler $ul, $x) use($contents){
        $class = $ul->attr('class');
        if ($class == 'topstories') {
            $contents['TopStory']['articles'] = $ul->filter("li")->each(function(Symfony\Component\DomCrawler\Crawler $li, $i){
                    $text = $li->filter('a')->text();
                    if (!$text) {
                        $text = $li->filter('h5 > a')->text();
                    }
                    $href = $li->filter('a')->attr('href');
                    return array('text' => $text, 'link' => "https://www.dailynews.co.zw".$href);
                });
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
