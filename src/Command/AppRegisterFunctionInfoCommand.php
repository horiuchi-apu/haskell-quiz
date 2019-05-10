<?php

namespace App\Command;

use App\Entity\FunctionInfo;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppRegisterFunctionInfoCommand extends Command
{
    protected static $defaultName = 'app:register-function-info';

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
        $this
            ->setDescription('register functionInfo')
            ->addArgument('filename', InputArgument::REQUIRED, 'filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');

        if ($filename) {
            $em = $this->em;
            $quizRepo = $em->getRepository(Quiz::class);

            $config = new LexerConfig();
            $config->setDelimiter("\t");
            $lexer = new Lexer($config);

            $interpreter = new Interpreter();
            $interpreter->addObserver(function(array $columns) use ($em, $quizRepo) {
                $name = $columns[1];
                $Info = str_replace('\n', "\n", $columns[2]);

                $function = new FunctionInfo();
                $function->setName($name);
                $function->setDescription($Info);

                $quizzes = $quizRepo->findByLikeFunctionName($name);
                if ($quizzes) {
                    /** @var Quiz $quiz */
                    foreach ($quizzes as $quiz) {
                        $quiz->addFunctionInfo($function);
                        $em->persist($quiz);
                    }
                }

                $em->persist($function);
                $em->flush();
            });

            $lexer->parse(__DIR__ . "/../../seeds/function/" . $filename, $interpreter);
        }


        $io->success('関数データの登録が完了しました。');
    }
}
