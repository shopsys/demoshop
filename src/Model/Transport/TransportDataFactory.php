<?php

declare(strict_types=1);

namespace App\Model\Transport;

use Shopsys\FrameworkBundle\Model\Transport\Transport as BaseTransport;
use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportDataFactory as BaseTransportDataFactory;

class TransportDataFactory extends BaseTransportDataFactory
{
    /**
     * @return \App\Model\Transport\TransportData
     */
    public function create(): BaseTransportData
    {
        $transportData = new TransportData();

        $this->fillNew($transportData);

        return $transportData;
    }

    /**
     * @param \App\Model\Transport\Transport $transport
     * @return \App\Model\Transport\TransportData $transportData
     */
    public function createFromTransport(BaseTransport $transport): BaseTransportData
    {
        $transportData = new TransportData();
        $this->fillFromTransport($transportData, $transport);

        return $transportData;
    }

    /**
     * @param \App\Model\Transport\TransportData $transportData
     */
    protected function fillNew(BaseTransportData $transportData)
    {
        $transportData->type = Transport::TYPE_DEFAULT;
        parent::fillNew($transportData);
    }

    /**
     * @param \App\Model\Transport\TransportData $transportData
     * @param \App\Model\Transport\Transport $transport
     */
    protected function fillFromTransport(BaseTransportData $transportData, BaseTransport $transport)
    {
        $transportData->type = $transport->getType();
        parent::fillFromTransport($transportData, $transport);
    }
}
