<?php

namespace Simian;

require __DIR__ . "/../bootstrap.php";

use Simian\Environment\Environment;
use GuzzleHttp\Client;
use Simian\Repositories\MongoCatalogueRepository;
use Simian\Repositories\MongoMailQueueRepository;
use Simian\Repositories\MongoReviewsRepository;
use Mailgun\Mailgun;
use Simian\Repositories\MongoSellerRepository;

$environment = new Environment('prod');
$merchants = [
//    "A1010PM0QYBVOG",
    "A3RFFOCMGATC6W",
//    "A2CODDGMAUR50
];
//$mailgun = new Mailgun('key-f33b7d4556b361eeba543eeca496654b');

foreach ($merchants as $merchant) {
    $catalogueRepository = new MongoCatalogueRepository($environment, $merchant);
    $reviewsRepository = new MongoReviewsRepository(
                                    $environment,
                                    (new MongoMailQueueRepository($environment))
                         );
    $sellerRepository = new MongoSellerRepository($environment);

    $seller = $sellerRepository->findSeller($merchant);
    $client = new Client();
    $reviewsScraper = new ReviewsScraper(
        $environment,
        $client,
        $reviewsRepository
    );

    $products = $catalogueRepository->getProductsCatalogue();
    $reviewsScraper->run($seller, $products);
}
