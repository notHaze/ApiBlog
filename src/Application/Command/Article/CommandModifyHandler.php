<?php

namespace Application\Command\Article;

use Application\Command\CommandInterface;
use Application\Command\CommandHandlerInterface;
use Domain\Article\Article;
use Domain\Repository\ArticleRepositoryInterface;
use Domain\Repository\ArticleRepositorylmpl;
use Exception;
class CommandModifyHandler implements  CommandHandlerInterface
{
    private ArticleRepositoryInterface $articleRepository;
    public function __construct(ArticleRepositoryInterface $articleRepository){
        $this->articleRepository = $articleRepository;
    }
    public function handle(CommandInterface $command)
    {
        if (!$command instanceof CommandModify) {
            throw new Exception("CommandModifyHandler can only handle CommandModify");
        }
        $article = new Article($command->getIdArticle(),$command->getDate(),$command->getBody(),$command->getIdUser());
        $this->articleRepository->save($article);
    }
}

?>
