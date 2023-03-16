<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
class CommandLike implements   CommandInterface{
    private $idUser;
    private $idArticle;
    private $like;
    public function __construct(int $idUser, int $idArticle, int $like)
    {
        $this->idUser = $idUser;
        $this->idArticle = $idArticle;
        $this->like = $like;
    }
    public function getIdUser()
    {
        return $this->idUser;
    }
    public function getIdArticle()
    {
        return $this->idArticle;
    }
    public function getLike()
    {
        return $this->like;
    }
}
?>