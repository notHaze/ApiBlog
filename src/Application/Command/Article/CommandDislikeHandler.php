<?php
namespace Application\Command\Article;
use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Repository\ArticleRepositoryInterface;
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
        $this->articleRepository->dislike($command->getIdUser(), $command->getIdArticle());
    }
}
?>