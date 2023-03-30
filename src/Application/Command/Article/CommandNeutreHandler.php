<?php

namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Article\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\User\User;
use Exception;

class commandNeutreHandler implements  CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository){
        $this->articleRepository = $articleRepository;
    }
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof commandNeutre) {
            throw new Exception("commandHandlerNeutre can only handle commandNeutre");
        }
        $this->articleRepository->neutre($command->getIdArticle(), $command->getIdUser());
    }
}
?>
