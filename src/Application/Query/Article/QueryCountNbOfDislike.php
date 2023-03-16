<?php

namespace Application\Query\Article;

use Application\Query\QueryInterface;

class QueryCountNbOfDislike implements QueryInterface
{
    private $Dislike;

    public function __construct(int $Dislike)
    {
        $this->Dislike = $Dislike;
    }

    public function getDislike()
    {
        return $this->Dislike;
    }

}
?>
