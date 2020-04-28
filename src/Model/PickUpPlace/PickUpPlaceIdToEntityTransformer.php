<?php

declare(strict_types=1);

namespace App\Model\PickUpPlace;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class PickUpPlaceIdToEntityTransformer implements DataTransformerInterface
{
    /**
     * @var \App\Model\PickUpPlace\PickUpPlaceFacade
     */
    protected $pickUpPlaceFacade;

    /**
     * @param \App\Model\PickUpPlace\PickUpPlaceFacade $pickUpPlaceFacade
     */
    public function __construct(PickUpPlaceFacade $pickUpPlaceFacade)
    {
        $this->pickUpPlaceFacade = $pickUpPlaceFacade;
    }

    /**
     * @param mixed $pickUpPlace
     * @return int|null
     *@var \App\Model\PickUpPlace\PickUpPlace
     */
    public function transform($pickUpPlace)
    {
        if ($pickUpPlace instanceof PickUpPlace) {
            return $pickUpPlace->getId();
        }
        return null;
    }

    /**
     * @param mixed $pickUpPlaceId
     * @return null|\App\Model\PickUpPlace\PickUpPlace
     * @var int|null
     */
    public function reverseTransform($pickUpPlaceId)
    {
        if ($pickUpPlaceId === null) {
            return null;
        }
        try {
            $pickUpPlace = $this->pickUpPlaceFacade->getById((int)$pickUpPlaceId);
        } catch (\App\Model\PickUpPlace\Exception\PickUpPlaceNotFoundException $notFoundException) {
            throw new TransformationFailedException('Pick up place not found', null, $notFoundException);
        }
        return $pickUpPlace;
    }
}
