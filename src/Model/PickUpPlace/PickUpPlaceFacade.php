<?php

declare(strict_types=1);

namespace App\Model\PickUpPlace;

use App\Model\Country\CountryFacade;
use App\Model\Transport\Transport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Monolog\Logger;

class PickUpPlaceFacade
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \App\Model\PickUpPlace\PickUpPlaceLoader
     */
    protected $pickUpPlaceLoader;

    /**
     * @var \App\Model\PickUpPlace\PickUpPlaceRepository
     */
    protected $pickUpPlaceRepository;

    /**
     * @var \App\Model\Country\CountryFacade
     */
    protected $countryFacade;

    /**
     * @param \App\Model\PickUpPlace\PickUpPlaceRepository $pickUpPlaceRepository
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \App\Model\PickUpPlace\PickUpPlaceLoader $pickUpPlaceLoader
     * @param \App\Model\Country\CountryFacade $countryFacade
     */
    public function __construct(
        PickUpPlaceRepository $pickUpPlaceRepository,
        EntityManagerInterface $em,
        PickUpPlaceLoader $pickUpPlaceLoader,
        CountryFacade $countryFacade
    ) {
        $this->pickUpPlaceRepository = $pickUpPlaceRepository;
        $this->em = $em;
        $this->pickUpPlaceLoader = $pickUpPlaceLoader;
        $this->countryFacade = $countryFacade;
    }

    /**
     * @param int $id
     * @return null|\App\Model\PickUpPlace\PickUpPlace
     */
    public function getById($id)
    {
        return $this->pickUpPlaceRepository->getById($id);
    }

    /**
     * @param \App\Model\PickUpPlace\PickUpPlaceData $pickUpPlaceData
     * @return \App\Model\PickUpPlace\PickUpPlace
     */
    public function create(PickUpPlaceData $pickUpPlaceData)
    {
        $pickUpPlace = new PickUpPlace($pickUpPlaceData);
        $this->em->persist($pickUpPlace);
        $this->em->flush($pickUpPlace);
        return $pickUpPlace;
    }

    /**
     * @param string $searchQuery
     * @param string[] $countryCodes
     * @param string $transportType
     * @return mixed
     */
    public function findActiveBySearchQueryAndTransportType($searchQuery, $countryCodes, $transportType)
    {
        return $this->pickUpPlaceRepository->findActiveBySearchQueryAndTransportType(
            $searchQuery,
            $countryCodes,
            $transportType
        );
    }

    /**
     * @param string $transportType
     * @return \App\Model\PickUpPlace\PickUpPlace[]
     */
    public function findAllActiveByTransportType($transportType)
    {
        return $this->pickUpPlaceRepository->findAllActiveByTransportType($transportType);
    }

    /**
     * @param string $feedUrl
     * @param \Symfony\Bridge\Monolog\Logger $logger
     * @param string $transportType
     */
    public function downloadPickUpPlaceFromFeed($feedUrl, Logger $logger, $transportType)
    {
        $this->pickUpPlaceRepository->markAllAsPendingByTransportType($transportType);
        $pickUpPlacesData = $this->pickUpPlaceLoader->load($feedUrl, $transportType);
        $this->refreshPickUpPlace($pickUpPlacesData, $logger);
        $this->pickUpPlaceRepository->deactivateAllPendingByTransportType(Transport::TYPE_ZASILKOVNA);
    }

    /**
     * @param \App\Model\PickUpPlace\PickUpPlaceData[] $pickUpPlacesData
     * @param \Symfony\Bridge\Monolog\Logger $logger
     */
    protected function refreshPickUpPlace(array $pickUpPlacesData, Logger $logger)
    {
        $newPickUpPlacesCount = 0;
        $updatedPickUpPlacesCount = 0;
        foreach ($pickUpPlacesData as $pickUpPlaceData) {
            $pickUpPlace = $this->pickUpPlaceRepository->findByPlaceIdAndTransportType(
                $pickUpPlaceData->placeId,
                $pickUpPlaceData->transportType
            );
            if ($pickUpPlace === null) {
                $this->create($pickUpPlaceData);
                $newPickUpPlacesCount++;
            } else {
                $pickUpPlace->edit($pickUpPlaceData);
                $pickUpPlace->markAsNotPending();
                $this->em->flush($pickUpPlace);
                $updatedPickUpPlacesCount++;
            }
        }
        $message = sprintf(
            'PickUpPlaces were updated. Number of new places: %d, updated places: %d',
            $newPickUpPlacesCount,
            $updatedPickUpPlacesCount
        );
        $logger->info($message);
    }
}
