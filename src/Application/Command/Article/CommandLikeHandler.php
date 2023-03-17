<?php

namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Article\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\User\User;
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
        $article = new Article($command->getIdArticle(), null, null, null);
        $user = new User(null, null, null, $command->getIdUser());
        $this->articleRepository->like($article, $user);
    }
}
?>
