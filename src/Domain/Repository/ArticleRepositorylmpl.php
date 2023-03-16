<?php

namespace Domain\Repository;

use Domain\Article\Article;
use Domain\User\User;
use Infrastructure\Database\Database;

class  ArticleRepositorylmpl implements ArticleRepositoryInterface {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getDb();
    }
    public function save(Article $article, User $user)
    {
        $article->setAuthor($user);
        $user->addArticle($article);
        $SQL = "INSERT INTO Article (contenu, datePubli, idUsers) VALUES (:contenu, :datePubli, :idUsers)";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':contenu', $article->getContenu());
        $stmt->bindParam(':datePubli', $article->getDatePubli());
        $stmt->bindParam(':idUsers', $article->getAuthor()->getId());
        $stmt->execute();
    }

    public function find(Article $article)
    {
        $SQL = "SELECT * FROM  Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':contenu', $article->getContenu());
        $stmt->bindParam(':datePubli', $article->getDatePubli());
        $stmt->bindParam(':idUsers', $article->getAuthor()->getId());
        $stmt->execute();
    }

    public function findAll()
    {
        $SQL = "SELECT * FROM Article";
        $stmt = $this->db->prepare($SQL);
        $stmt->execute();
    }

    public function delete(Article $article)
    {
        $SQL = "DELETE FROM Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idUsers', $article->getId());
        $stmt->execute();
    }

    private function existInLikeTable(Article $article, User $user)
    {

        $SQL = "SELECT count(*) FROM liker WHERE idArticle = :idArticle and idUser = :idUser and liker in (1,-1)";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $article->getId());
        $stmt->bindParam(':idUser', $user->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            return False;
        }
        return True;
    }

    public function like(Article $article, User $user)
    {
        $SQL = "INSERT INTO Liker (idArticle, idUser, liker) VALUES (:idArticle, :idUser, :liker)";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idAuteur', $article->getId());
        $stmt->bindParam(':idUser', $user->getId());
        $stmt->bindParam(':liker', 1);
        $stmt->execute();
    }

    public function dislike(Article $article, User $user)
    {
        $SQL = "INSERT INTO Liker (idArticle, idUser, liker) VALUES (:idArticle, :idUser, :liker)";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idAuteur', $article->getId());
        $stmt->bindParam(':idUser', $user->getId());
        $stmt->bindParam(':liker', -1);
        $stmt->execute();
    }
}
