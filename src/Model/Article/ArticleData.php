<?php

declare(strict_types=1);

namespace App\Model\Article;

use DateTime;
use Shopsys\FrameworkBundle\Model\Article\ArticleData as BaseArticleData;

class ArticleData extends BaseArticleData
{
    /**
     * @var \DateTime|null
     */
    public $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        parent::__construct();
    }
}
