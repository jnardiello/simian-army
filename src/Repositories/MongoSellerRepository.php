<?php

/**
* This file is part of Work Digital's Data Platform.
*
* (c) 2015 Work Digital
*/

namespace Simian\Repositories;

use Simian\Seller;
use Simian\Environment\Environment;

/**
* Class MongoSellerRepository
*
* @author Jacopo Nardiello <jacopo.nardiello@dice.com>
*/
class MongoSellerRepository
{
    private $collection;

    public function __construct(Environment $environment)
    {
        $client = new \MongoClient($environment->get('mongo.host'));
        $db = $client->selectDB($environment->get('mongo.data.db'));

        $this->collection = $db->selectCollection($environment->get('mongo.merchants'));
    }

    public function findSeller($sellerId)
    {
        $data = $this->collection->findOne([
            '_id' => $sellerId,
        ]);

        return new Seller(
            $data['_id'],
            $data['name'],
            $data['email']
        );
    }

    public function insertOne(Seller $seller)
    {
        $this->collection->insert($seller->toArray());
    }
}