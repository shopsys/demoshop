<?php

namespace Shopsys\ShopBundle\Model\Transport;

use Shopsys\FrameworkBundle\Model\Transport\Transport as BaseTransport;
use Shopsys\FrameworkBundle\Model\Transport\TransportData as BaseTransportData;
use Shopsys\FrameworkBundle\Model\Transport\TransportFactoryInterface;

class TransportFactory implements TransportFactoryInterface
{
    /**
     * @param \Shopsys\ShopBundle\Model\Transport\TransportData $data
     * @return \Shopsys\ShopBundle\Model\Transport\Transport
     */
    public function create(BaseTransportData $data): BaseTransport
    {
        return new Transport($data);
    }
}
