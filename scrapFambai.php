<?php
include_once 'vendor/autoload.php';
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\CssSelector\CssSelectorConverter;

$client = new Goutte\Client();

$crawler = $client->request('GET', 'http://www.cometozimbabwe.com/properties/load/?path=/accommodation/&data=anyPrice-true+price-10,999+star-1,5+sort-rand+limit-120+page-1');

$h3_count = $crawler->filter('article')->count();

$properties = array();

if ($h3_count) {
    $properties = $crawler->filter("article.property-item ")->each(function(Symfony\Component\DomCrawler\Crawler $article, $x) use($properties){
        $class = $article->attr('class');
       // if ($class == 'property-item col-sm-6 col-md-4') {

            $properties = $article->filter("article > div")->each(function(Symfony\Component\DomCrawler\Crawler $div, $i){
                    $text = $div->filter('h3 > a')->text();
                    $description = $div->filter('div.propertyExcerptBox > span')->text();
                    $description = preg_replace('/ \s /', '', $description);
//                    if (!$text) {
//                        $text = $li->filter('h5 > a')->text();
//                    }
//                    $href = $li->filter('a')->attr('href');
                    return array('text' => $text, 'description' => trim($description));
                });
        //}
        return $properties;
    });
    print "<pre>";
    print_r($properties);
    print "</pre>";
}

echo "There are ".$h3_count." h3 counts";
//$aTags = $crawler->links();

exit();
