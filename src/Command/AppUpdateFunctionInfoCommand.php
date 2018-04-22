<?php

namespace App\Command;

use App\Entity\FunctionInfo;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppUpdateFunctionInfoCommand extends Command
{
    protected static $defaultName = 'app:update-function-info';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('update functionInfo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $functionRepo = $this->em->getRepository(FunctionInfo::class);
        $quizRepo     = $this->em->getRepository(Quiz::class);

        /** @var FunctionInfo $function */
        foreach ($functionRepo->findAll() as $function) {
            $name = $function->getName();
            $quizzes = $quizRepo->findByLikeFunctionName($name);
            if ($quizzes) {
                /** @var Quiz $quiz */
                foreach ($quizzes as $quiz) {
                    $io->note("quizId: {$quiz->getId()}");
                    $quiz->addFunctionInfo($function);
                    $this->em->persist($quiz);
                }
            }
            $this->em->persist($function);
        }
        $this->em->flush();
        $io->success('関数データの更新が完了しました。');
    }
}
