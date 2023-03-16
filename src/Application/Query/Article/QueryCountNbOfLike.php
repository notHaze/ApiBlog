<?php
namespace Application\Query\Article;

use Application\Query\QueryInterface;

class QueryCountNbOfLike implements QueryInterface
{
    private $Nblike;

    public function __construct(int $Nblike)
    {
        $this->Nblike = $Nblike;
    }

    public function getNblike()
    {
        return $this->Nblike;
    }
}
?>