<?php

declare(strict_types=1);

namespace Shopsys\ShopBundle\Model\Transport;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Model\Transport\Transport as BaseTransport;
use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;

/**
 * @ORM\Table(name="transports")
 * @ORM\Entity
 * @method setTranslations(\Shopsys\ShopBundle\Model\Transport\TransportData $transportData)
 * @method setDomains(\Shopsys\ShopBundle\Model\Transport\TransportData $transportData)
 * @method createDomains(\Shopsys\ShopBundle\Model\Transport\TransportData $transportData)
 */
class Transport extends BaseTransport
{
    public const TYPE_DEFAULT = 'basic';
    public const TYPE_ZASILKOVNA = 'zasilkovna';

    /**
     * @var string
     *
     * @ORM\Column(type="string", options={"default": "basic"})
     */
    protected $type;

    /**
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     */
    public function __construct(BaseTransportData $transportData)
    {
        $this->type = $transportData->type;
        parent::__construct($transportData);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isPickUpPlaceType(): bool
    {
        return $this->type === self::TYPE_ZASILKOVNA;
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $transportData
     */
    public function edit(BaseTransportData $transportData)
    {
        $this->type = $transportData->type;
        parent::edit($transportData);
    }
}
