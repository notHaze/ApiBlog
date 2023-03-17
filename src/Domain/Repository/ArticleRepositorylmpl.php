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
    public function save(Article $article)
    {
        if ($this->existArticle($article)) {
            $SQL = "UPDATE Article set contenu = :contenu";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':contenu', $article->getBody());
        } else {
            $SQL = "INSERT INTO Article (contenu, datePubli, idAuteur) VALUES (:contenu, :datePubli, :idAuteur)";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':contenu', $article->getBody());
            $stmt->bindParam(':datePubli', $article->getPublicationDate());
            $stmt->bindParam(':idAuteur', $article->getWriter());
        }

        $stmt->execute();
        //get the last inserted id
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function find(Article $article)
    {
        $SQL = "SELECT * FROM  Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':contenu', $article->getContenu());
        $stmt->bindParam(':datePubli', $article->getDatePubli());
        $stmt->bindParam(':idUsers', $article->getAuthor()->getId());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findAll()
    {
        $SQL = "SELECT * FROM Article";
        $stmt = $this->db->prepare($SQL);
        $stmt->execute();
        return $stmt->fetchAll();
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

    private function existArticle(Article $article)
    {
        $SQL = "SELECT count(*) FROM Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $article->getId());
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            return False;
        }
        return True;
    }

    public function like(Article $article, User $user, $like=1)
    {
        if ($this->existInLikeTable($article, $user)) {
            $SQL = "UPDATE liker set liker = :liker WHERE idArticle = :idArticle and idUser = :idUser";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':idArticle', $article->getId());
            $stmt->bindParam(':idUser', $user->getId());
            $stmt->bindParam(':liker', $like);
            $stmt->execute();
        } else {
            $SQL = "INSERT INTO Liker (idArticle, idUser, liker) VALUES (:idArticle, :idUser, :liker)";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':idAuteur', $article->getId());
            $stmt->bindParam(':idUser', $user->getId());
            $stmt->bindParam(':liker', $like);
            $stmt->execute();
        }

    }

    public function dislike(Article $article, User $user)
    {
        $this->like($article, $user, -1);
    }

    public function getLikes(Article $article)
    {
        $SQL = "SELECT count(*) FROM liker WHERE idArticle = :idArticle and liker = 1";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $article->getId());
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDislikes(Article $article)
    {
        $SQL = "SELECT count(*) FROM liker WHERE idArticle = :idArticle and liker = -1";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $article->getId());
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
