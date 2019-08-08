<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Model\Order;

use Shopsys\FrameworkBundle\Model\Order\OrderNumberSequenceRepository;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class OrderNumberSequenceRepositoryTest extends TransactionFunctionalTestCase
{
    public function testGetNextNumber()
    {
        $orderNumberSequenceRepository = $this->getContainer()->get(OrderNumberSequenceRepository::class);
        /* @var $orderNumberSequenceRepository \Shopsys\FrameworkBundle\Model\Order\OrderNumberSequenceRepository */

        $numbers = [];
        for ($i = 0; $i < 10; $i++) {
            $numbers[] = $orderNumberSequenceRepository->getNextNumber();
        }

        $uniqueNumbers = array_unique($numbers);

        $this->assertSame(count($numbers), count($uniqueNumbers));
    }
}
