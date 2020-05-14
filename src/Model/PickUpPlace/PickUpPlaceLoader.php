<?php

declare(strict_types=1);

namespace App\Model\PickUpPlace;

use App\Model\Transport\Transport;
use SimpleXMLElement;

class PickUpPlaceLoader
{
    /**
     * @var string[]
     */
    protected $daysTranslationMap;

    public function __construct()
    {
        $this->daysTranslationMap = [
            'monday' => 'Pondělí',
            'tuesday' => 'Úterý',
            'wednesday' => 'Středa',
            'thursday' => 'Čtvrtek',
            'friday' => 'Pátek',
            'saturday' => 'Sobota',
            'sunday' => 'Neděle',
        ];
    }

    /**
     * @param string $feedUrl
     * @param string $transportType
     * @return \App\Model\PickUpPlace\PickUpPlaceData[]
     */
    public function load($feedUrl, $transportType)
    {
        $pickUpPlaceFeedData = [];
        $xml = simplexml_load_file($feedUrl);

        if ($xml === false) {
            throw new \App\Model\PickUpPlace\Exception\PickUpPlaceXmlParsingException(
                'Could not load XML file "' . $feedUrl . '".'
            );
        }
        try {
            foreach ($xml->children() as $xmlNode) {
                if ($transportType === Transport::TYPE_ZASILKOVNA) {
                    if ($xmlNode->getName() === 'branches') {
                        foreach ($xmlNode->children() as $branch) {
                            $pickUpPlaceFeedData[] = $this->parseZasilkovnaData($branch);
                        }
                    }
                }
            }
        } catch (\ErrorException $exception) {
            throw new \App\Model\PickUpPlace\Exception\PickUpPlaceXmlParsingException(
                'Could not parse XML file "' . $feedUrl . '": ' . $exception->getMessage(),
                $exception
            );
        }
        return $pickUpPlaceFeedData;
    }

    /**
     * @param \SimpleXMLElement $xmlNode
     * @return \App\Model\PickUpPlace\PickUpPlaceData
     */
    protected function parseZasilkovnaData(SimpleXMLElement $xmlNode)
    {
        $pickUpPlaceData = new PickUpPlaceData();
        $pickUpPlaceData->placeId = (string)$xmlNode->id;
        $pickUpPlaceData->postCode = str_replace(' ', '', (string)$xmlNode->zip);
        $pickUpPlaceData->name = (string)$xmlNode->name;
        $pickUpPlaceData->street = (string)$xmlNode->street;
        $pickUpPlaceData->city = (string)$xmlNode->city;
        $pickUpPlaceData->description = $this->parseOpeningHours($xmlNode->openingHours->regular);
        $pickUpPlaceData->countryCode = (string)$xmlNode->country;
        $pickUpPlaceData->transportType = Transport::TYPE_ZASILKOVNA;
        $pickUpPlaceData->gpsLongitude = (string)$xmlNode->longitude;
        $pickUpPlaceData->gpsLatitude = (string)$xmlNode->latitude;
        return $pickUpPlaceData;
    }

    /**
     * @param \SimpleXMLElement $xmlOpeningHours
     * @return string
     */
    protected function parseOpeningHours(SimpleXMLElement $xmlOpeningHours)
    {
        $descriptionRows = [];
        foreach ($xmlOpeningHours->children() as $xmlOpeningHoursDay) {
            /* @var $xmlOpeningHoursDay \SimpleXMLElement */
            $day = $this->daysTranslationMap[$xmlOpeningHoursDay->getName()];
            $hours = (string)$xmlOpeningHoursDay;
            $descriptionRows[] = $day . ': ' . ($hours === '' ? '---' : $hours);
        }
        return implode("\n", $descriptionRows);
    }
}
