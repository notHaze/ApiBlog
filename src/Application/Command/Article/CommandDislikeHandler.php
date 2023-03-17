<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Article\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\User\User;
use Exception;
class CommandDislikeHandler implements CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    public function __construct( ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof CommandDislike) {
            throw new Exception("CommandDislikeHandler can only handle CommandDislike");
        }
        $article = new Article($command->getIdArticle(), null, null, null);
        $user = new User(null, null, null, $command->getIdUser());
        $this->articleRepository->dislike($article, $user);
    }
}
?>