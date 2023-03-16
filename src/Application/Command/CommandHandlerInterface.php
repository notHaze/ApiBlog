<?php

namespace Application\Command;

interface CommandHandlerInterface {
    public function handle(CommandInterface $command);
}

?>