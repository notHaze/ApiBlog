<?php

namespace Application\Command\Article;
use Application\Command\CommandHandlerInterface;
use Application\Command\CommandInterface;
use Domain\Article\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Exception;

class commandDeleteHandler implements CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function handle(CommandInterface $command)
    {
        if (!$command instanceof commandDelete) {
            throw new Exception("commandHandlerDelete can only handle commandDelete");
        }
        $article = new Article($command->getIdArticle(), null, null, null);
        $this->articleRepository->delete($article);

    }
}