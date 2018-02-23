<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\YelpTrait;

class YelpFusion extends \Neighborhoods\YelpFusion\Yelp
{
    use YelpTrait;

    private $bizLookupEndpoint = 'v3/businesses/';


    protected $fillable = ['biz_id', 'name', 'description'];


    /**
     * Get biz using yelp biz id
     *
     * @param $yelpBizId
     * @param $bearerToken
     * @return object
     * @throws Exception
     */
    public function bizlookup($yelpBizId, $bearerToken, $reviews = false)
    {
        $endpoint = $this->bizLookupEndpoint . $yelpBizId;
        if ($reviews) {
            $endpoint.= '/reviews';
        }
        return $this->parseResponse(
            $this->guzzle->get(
                $endpoint,
                [
                    'headers' => [
                        'authorization' => 'Bearer ' . $bearerToken,
                    ]
                ]
            )
        );
    }



}
