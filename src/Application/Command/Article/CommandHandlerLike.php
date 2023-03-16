<?php

namespace Application\Command\Article;

interface commadHandlerLike
{
    public function handle(CommandLike $command);
}
?>
