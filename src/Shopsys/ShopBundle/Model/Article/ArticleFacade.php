<?php

namespace Shopsys\ShopBundle\Model\Article;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade;
use Shopsys\FrameworkBundle\Model\Article\ArticleData;
use Shopsys\FrameworkBundle\Model\Article\ArticleFacade as BaseArticleFacade;
use Shopsys\FrameworkBundle\Model\Article\ArticleFactoryInterface;
use Shopsys\FrameworkBundle\Model\Article\ArticleRepository;
use Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProduct;
use Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository;

class ArticleFacade extends BaseArticleFacade
{
    /**
     * @var \Shopsys\ShopBundle\Model\Article\ArticleProduct\ArticleProductRepository
     */
    private $articleProductRepository;

    public function __construct(
        EntityManagerInterface $em,
        ArticleRepository $articleRepository,
        Domain $domain,
        FriendlyUrlFacade $friendlyUrlFacade,
        ArticleFactoryInterface $articleFactory,
        ArticleProductRepository $articleProductRepository
    ) {
        parent::__construct($em, $articleRepository, $domain, $friendlyUrlFacade, $articleFactory);
        $this->articleProductRepository = $articleProductRepository;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Article\ArticleData $articleData
     * @return \Shopsys\FrameworkBundle\Model\Article\Article
     */
    public function create(ArticleData $articleData)
    {
        $article = parent::create($articleData);
        $this->refreshArticleProducts($articleData, $article);

        return $article;
    }

    /**
     * @param int $articleId
     * @param \Shopsys\FrameworkBundle\Model\Article\ArticleData $articleData
     * @return \Shopsys\FrameworkBundle\Model\Article\Article
     */
    public function edit($articleId, ArticleData $articleData)
    {
        $article = parent::edit($articleId, $articleData);
        $this->refreshArticleProducts($articleData, $article);

        return $article;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Article\ArticleData $articleData
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     */
    private function refreshArticleProducts(ArticleData $articleData, Article $article)
    {
        $oldArticleProducts = $this->articleProductRepository->getArticleProductsByArticle($article);
        $toFlush = [];
        foreach ($oldArticleProducts as $articleProduct) {
            $this->em->remove($articleProduct);
            $toFlush[] = $articleProduct;
        }
        $this->em->flush($toFlush);

        $toFlush = [];
        foreach ($articleData->products as $product) {
            $newArticlProduct = new ArticleProduct($article, $product);
            $this->em->persist($newArticlProduct);
            $toFlush[] = $newArticlProduct;
        }
        $this->em->flush($toFlush);
    }

    /**
     * @param \Shopsys\ShopBundle\Model\Article\Article $article
     * @return \Shopsys\ShopBundle\Model\Product\Product[]
     */
    public function getProductsByArticle(Article $article)
    {
        $articleProducts = $this->articleProductRepository->getArticleProductsByArticle($article);

        $products = [];
        foreach ($articleProducts as $articleProduct) {
            $products[] = $articleProduct->getProduct();
        }

        return $products;
    }
}
