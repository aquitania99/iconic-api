<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\True_;
use RecursiveArrayIterator;
use RecursiveIterator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IconicApiController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    protected function getIconicProducts($gender, $page, $page_size)
    {
        $response = $this->httpClient->request(
            'GET',
            'https://eve.theiconic.com.au/catalog/products?gender='.$gender.'&page='.$page.'&page_size='.$page_size.'&sort=popularity'
        );

        $content = $response->toArray();
        
        $get_results = $this->_generateAbResults($content);

        $video_count = array_column($get_results, 'video_count');
        array_multisort($video_count, SORT_DESC, $get_results);

        return json_encode($get_results);
    }

    protected function getIconicProductVideo($url)
    {
        $response = $this->httpClient->request(
            'GET',
            $url
        );

        $content = $response->toArray();

        return $content;
    }

    private function _generateAbResults($args)
    {
        if (array_key_exists("_embedded", $args)) {
            $count = 0;
            $less = 0;
            $res = [];
            foreach ($args['_embedded'] as $item) {
                foreach ($item as $prod) {
                    $count++;
                    $url = '';
                    if ($prod['video_count'] > 0) {
                        $url = $prod['_links']['self']['href']."/videos";        
                        $get_video = $this->getIconicProductVideo($url);
                        $video_url = $get_video['_embedded']['videos_url'][0]['url'];
                        $prod["video_url"] = $video_url;
                        array_push($res, $prod);
                        array_pop($prod);
                        $less++;
                    } else {
                        array_push($res, $prod);
                    }
                }
            }

            return $res;
        }
    }

    /**
     * @Route("/iconic/api", name="iconic_api_list")
     */
    public function list(Request $request)
    {
        $gender = $request->get('gender', 'female'); 
        $page = $request->get('page', 1);
        $page_size = $request->get('psize', 10);

        $response = $this->getIconicProducts($gender, $page, $page_size);

        return new JsonResponse(json_decode($response, true));
    }
}
