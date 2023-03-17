<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
class CommandDelete implements CommandInterface{
    private $idArticle;
    public function __construct(int $idArticle)
    {
        $this->idArticle = $idArticle;
    }
    public function getIdArticle()
    {
        return $this->idArticle;
    }
}
?>
