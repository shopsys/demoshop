<?php

declare(strict_types=1);

namespace App\Component\Router;

use Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory;
use Symfony\Component\Routing\RouterInterface;

class DomainContextSwitcher
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory
     */
    private $domainRouterFactory;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Shopsys\FrameworkBundle\Component\Router\DomainRouterFactory $domainRouterFactory
     */
    public function __construct(RouterInterface $router, DomainRouterFactory $domainRouterFactory)
    {
        $this->router = $router;
        $this->domainRouterFactory = $domainRouterFactory;
    }

    /**
     * @param int $domainId
     * @param \Shopsys\FrameworkBundle\Component\Router\DomainRouter $domainRouter
     */
    public function changeRouterContext(int $domainId): void
    {
        $domainRouter = $this->domainRouterFactory->getRouter($domainId);
        $host = $domainRouter->getContext()->getHost();

        $this->router->getContext()->setHost($host);
    }
}
