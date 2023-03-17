<?php

namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Repository\ArticleRepositoryInterface;
use Exception;
class commandLikeHandler implements  CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository){
        $this->articleRepository = $articleRepository;
    }
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof commandLike) {
            throw new Exception("commandHandlerLike can only handle commandLike");
        }
        $this->articleRepository->like($command->getIdUser(), $command->getIdArticle());
    }
}
?>
