<?php

namespace Application\Query;

interface QueryHandlerInterface {
    public function ask(QueryInterface $query);
}
?>