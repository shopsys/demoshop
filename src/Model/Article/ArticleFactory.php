<?php

declare(strict_types=1);

namespace App\Model\Article;

use Shopsys\FrameworkBundle\Model\Article\Article as BaseArticle;
use Shopsys\FrameworkBundle\Model\Article\ArticleData as BaseArticleData;
use Shopsys\FrameworkBundle\Model\Article\ArticleFactoryInterface;

class ArticleFactory implements ArticleFactoryInterface
{
    /**
     * @param \App\Model\Article\ArticleData $data
     * @return \App\Model\Article\Article
     */
    public function create(BaseArticleData $data): BaseArticle
    {
        return new Article($data);
    }
}
