<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
class CommandDislike implements CommandInterface{
    private $idUser;
    private $idArticle;
    private $dislike;
    public function __construct(int $idUser, int $idArticle, int $dislike)
    {
        $this->idUser = $idUser;
        $this->idArticle = $idArticle;
        $this->dislike = $dislike;
    }
    public function getIdUser()
    {
        return $this->idUser;
    }
    public function getIdArticle()
    {
        return $this->idArticle;
    }
    public function getDislike()
    {
        return $this->dislike;
    }

}
?>