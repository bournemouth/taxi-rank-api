<?php

namespace BournemouthData\TaxiRank;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class TaxiRankController
{

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getAll()
    {
        /** @var $taxiRankRecords TaxiRank[] */
        $taxiRankRecords = $this->app['db.taxiRanks'];

        $taxiRankCollection = new \Nocarrier\Hal('/api/v1/taxi-ranks');

        foreach ($taxiRankRecords as $taxiRank) {
            $taxiRankResource = new \Nocarrier\Hal(
                '/api/v1/taxi-ranks/' . $taxiRank->getId(),
                $this->serialize($taxiRank)
            );
            $taxiRankCollection->addResource('taxiRanks', $taxiRankResource);
        }

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankCollection->asJson(true));
        return $response;
    }

    public function getTaxiRank()
    {
        $id = $this->app['request']->get('id');
        /** @var $taxiRank TaxiRank */
        $taxiRank = $this->app['db.taxiRanks'][$id];

        $taxiRankResource = new \Nocarrier\Hal('/api/v1/taxi-ranks/' . $taxiRank->getId(), $this->serialize($taxiRank));

        $response = new Response();
        $response->headers->set('Content-Type', 'application/hal+json');
        $response->setContent($taxiRankResource->asJson(true));
        return $response;
    }

    /**
     * @param $taxiRank
     * @return array
     */
    private function serialize($taxiRank)
    {
        return [
            'id' => $taxiRank->getId(),
            'name' => $taxiRank->getName(),
            'description' => $taxiRank->getDescription(),
            'lat' => $taxiRank->getLat(),
            'lng' => $taxiRank->getLng()
        ];
    }
}
