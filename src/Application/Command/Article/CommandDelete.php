<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
class CommandDelete implements CommandInterface{
    private $idUser;
    private $idArticle;
    public function __construct(int $idUser, int $idArticle)
    {
        $this->idUser = $idUser;
        $this->idArticle = $idArticle;
    }
    public function getIdUser()
    {
        return $this->idUser;
    }
    public function getIdArticle()
    {
        return $this->idArticle;
    }
}
?>
