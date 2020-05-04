<?php

declare(strict_types=1);

namespace Tests\ShopBundle\Functional\Component\HttpFoundation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Tests\ShopBundle\Test\TransactionFunctionalTestCase;

class FragmentHandlerTest extends TransactionFunctionalTestCase
{
    public function testRenderingFragmentDoesNotIgnoreException()
    {
        $httpKernelRuntime = $this->getContainer()->get('twig.runtime.httpkernel');
        /* @var $httpKernelRuntime \Symfony\Bridge\Twig\Extension\HttpKernelRuntime */

        // Rendering a fragment can only be done when handling a Request.
        $this->putFakeRequestToRequestStack();

        $this->expectException(\App\Controller\Test\ExpectedTestException::class);

        /** This should call @see \Shopsys\FrameworkBundle\Component\HttpFoundation\FragmentHandler::render() */
        $httpKernelRuntime->renderFragment('/test/error-handler/exception');
    }

    private function putFakeRequestToRequestStack()
    {
        $requestStack = $this->getContainer()->get('request_stack');
        /* @var $requestStack \Symfony\Component\HttpFoundation\RequestStack */

        $request = new Request();
        $session = new Session(new MockArraySessionStorage());
        $request->setSession($session);
        $requestStack->push($request);
    }
}
