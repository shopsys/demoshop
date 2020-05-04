<?php

declare(strict_types=1);

namespace App\Model\PickUpPlace;

use App\Model\PickUpPlace\Exception\PickUpPlaceXmlParsingException;
use App\Model\Transport\Transport;
use Doctrine\ORM\EntityManagerInterface;
use Shopsys\Plugin\Cron\SimpleCronModuleInterface;
use Symfony\Bridge\Monolog\Logger;

class PickUpPlaceCronModule implements SimpleCronModuleInterface
{
    /**
     * @var string
     */
    protected $feedUrl;

    /**
     * @param \App\Model\PickUpPlace\PickUpPlaceFacade
     */
    protected $pickUpPlaceFacade;

    /**
     * @var \Symfony\Bridge\Monolog\Logger
     */
    protected $logger;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param $feedUrl
     * @param \App\Model\PickUpPlace\PickUpPlaceFacade $pickUpPlaceFacade
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct($feedUrl, PickUpPlaceFacade $pickUpPlaceFacade, EntityManagerInterface $em)
    {
        $this->feedUrl = $feedUrl;
        $this->pickUpPlaceFacade = $pickUpPlaceFacade;
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function run()
    {
        $this->updatePickupPlaces($this->feedUrl);
    }

    /**
     * @param string $feedUrl
     */
    protected function updatePickupPlaces($feedUrl)
    {
        try {
            $this->em->beginTransaction();
            $this->pickUpPlaceFacade->downloadPickUpPlaceFromFeed($feedUrl, $this->logger, Transport::TYPE_ZASILKOVNA);

            $this->em->commit();
        } catch (PickUpPlaceXmlParsingException $exception) {
            $this->logger->addError($exception->getMessage(), ['exception' => $exception]);

            $this->em->rollback();
        }
    }
}