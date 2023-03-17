<?php

namespace Application\Command\Article;
use Application\Command\CommandInterface;
class CommandModify implements CommandInterface{
    private $idUser;
    private $idArticle;
    private string $body;
    private $date;

    public function __construct(int $idUser, int $idArticle, String $body, $date)
    {
        $this->idUser = $idUser;
        $this->idArticle = $idArticle;
        $this->body = $body;
        $this->date = $date;
    }
    public function getIdUser()
    {
        return $this->idUser;
    }
    public function getIdArticle()
    {
        return $this->idArticle;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }
}
?>
