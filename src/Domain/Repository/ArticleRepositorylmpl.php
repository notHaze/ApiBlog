<?php

namespace Domain\Repository;

use Domain\Article\Article;
use Domain\User\User;
use Infrastructure\Database\Database;
use PDO;

class  ArticleRepositorylmpl implements ArticleRepositoryInterface {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getDb();
    }
    public function save(Article $article)
    {
        $contenu = $article->getBody();
        if ($this->existArticle($article)) {
            $SQL = "UPDATE Article set contenu = :contenu";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':contenu', $contenu);
        } else {
            $datePubli = $article->getPublicationDate();
            $idAuteur = $article->getWriter();
            $SQL = "INSERT INTO Article (contenu, datePubli, idAuteur) VALUES (:contenu, :datePubli, :idAuteur)";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':contenu', $contenu);
            $stmt->bindParam(':datePubli', $datePubli);
            $stmt->bindParam(':idAuteur', $idAuteur);
        }

        $stmt->execute();
        //get the last inserted id
        $id = $this->db->lastInsertId();
        return $id;
    }

    public function findOwn($id)
    {
        $SQL = "SELECT idArticle, contenu, datePubli, username FROM  Article, User WHERE idAuteur = :idAuteur and idAuteur = idUser";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idAuteur', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $SQL = "SELECT idArticle, contenu, datePubli, username FROM Article, User WHERE idAuteur = idUser";
        $stmt = $this->db->prepare($SQL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(Article $article)
    {
        $id = $article->getId();
        $SQL = "DELETE FROM Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();

    }

    private function existInLikeTable(Article $article, User $user)
    {
        $idUser = $user->getId();
        $idArticle = $article->getId();
        $SQL = "SELECT count(*) FROM liker WHERE idArticle = :idArticle and idUser = :idUser and liker in (1,'-1')";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $idArticle);
        $stmt->bindParam(':idUser', $idUser);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            return False;
        }
        return True;
    }

    private function existArticle(Article $article)
    {
        $id = $article->getId();
        $SQL = "SELECT count(*) FROM Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result[0] == 0) {
            return False;
        }
        return True;
    }

    public function like(Article $article, User $user, $like=1)
    {
        $idUser = $user->getId();
        $idArticle = $article->getId();
        if ($this->existInLikeTable($article, $user)) {
            $SQL = "UPDATE liker set liker = :liker WHERE idArticle = :idArticle and idUser = :idUser";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':idArticle', $idArticle);
            $stmt->bindParam(':idUser', $idUser);
            $stmt->bindParam(':liker', $like);
            $stmt->execute();
        } else {
            $SQL = "INSERT INTO Liker (idArticle, idUser, liker) VALUES (:idArticle, :idUser, :liker)";
            $stmt = $this->db->prepare($SQL);
            $stmt->bindParam(':idArticle', $idArticle);
            $stmt->bindParam(':idUser', $idUser);
            $stmt->bindParam(':liker', $like);
            $stmt->execute();
        }

    }

    public function dislike(Article $article, User $user)
    {
        $this->like($article, $user, -1);
    }

    public function getLikes($id)
    {
        $SQL = "SELECT count(*) nbLike FROM liker WHERE idArticle = :idArticle and liker = 1";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDislikes($id)
    {
        $SQL = "SELECT count(*) nbDislike FROM liker WHERE idArticle = :idArticle and liker = '-1'";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOne($id)
    {
        $SQL = "SELECT * FROM Article WHERE idArticle = :idArticle";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeopleLike($id)
    {
        $SQL = "SELECT username FROM liker, User WHERE idArticle = :idArticle and liker = 1 and liker.idUser = User.idUser";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPeopleDislike($id)
    {
        $SQL = "SELECT username FROM liker, User WHERE idArticle = :idArticle and liker = '-1' and liker.idUser = User.idUser";
        $stmt = $this->db->prepare($SQL);
        $stmt->bindParam(':idArticle', $id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
