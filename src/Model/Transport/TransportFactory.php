<?php

declare(strict_types=1);

namespace App\Model\Transport;

use Shopsys\FrameworkBundle\Model\Transport\Transport as BaseTransport;
use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportFactoryInterface;

class TransportFactory implements TransportFactoryInterface
{
    /**
     * @param \App\Model\Transport\TransportData $data
     * @return \App\Model\Transport\Transport
     */
    public function create(BaseTransportData $data): BaseTransport
    {
        return new Transport($data);
    }
}
