<?php

namespace Domain\Article;

class Article
{
    private $id;
    private $date;
    private $body;
    private $writer;

    public function __construct($id, $date, $body, $writer)
    {
        $this->id = $id;
        $this->date = $date;
        $this->body = $body;
        $this->writer = $writer;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getWriter()
    {
        return $this->writer;
    }

    public function getPublicationDate()
    {
        return $this->date;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function __toString()
    {
        return json_encode(array("id" => $this->id, "date" => $this->date, "body" => $this->body, "writer" => $this->writer));
    }

    public function setId($id) {
        $this->id = $id;
    }
}


?>