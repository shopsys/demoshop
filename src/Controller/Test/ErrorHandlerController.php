<?php

declare(strict_types=1);

namespace App\Controller\Test;

use App\Controller\Front\FrontBaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandlerController extends FrontBaseController
{
    /**
     * @Route("/error-handler/notice")
     */
    public function noticeAction()
    {
        $undefined[42];

        return new Response('');
    }

    /**
     * @Route("/error-handler/exception")
     */
    public function exceptionAction()
    {
        throw new \App\Controller\Test\ExpectedTestException('Expected exception');
    }
}
