<?php

namespace App\Command;

use App\Entity\Quiz;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppRegisterQuizCommand extends Command
{
    protected static $defaultName = 'app:register-quiz';

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
            ->setDescription('register quiz')
            ->addArgument('filename', InputArgument::REQUIRED, 'filename')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $filename = $input->getArgument('filename');

        if ($filename) {
            $em = $this->em;

            $section = $em->getRepository(Section::class)->findOneBy(['slug' => $filename]);
            if (empty($section)) {
                $section = new Section();
                $section->setName($filename);
                $section->setSlug($filename);
            }

            $config = new LexerConfig();
            $config->setDelimiter("\t");
            $lexer = new Lexer($config);

            $interpreter = new Interpreter();
            $interpreter->addObserver(function(array $columns) use ($em, $section) {
                $quiz = new Quiz();

                $quiz->setPage($columns[1]);
                $quiz->setQuizText($columns[2]);
                $quiz->setAnswerText($columns[3]);

                $section->addQuiz($quiz);

                $em->persist($quiz);
                $em->flush();
            });

            $em->persist($section);
            $em->flush();

            $lexer->parse(__DIR__ . "/../../quizData/" . $filename, $interpreter);
        }


        $io->success('問題データの登録が完了しました。');
    }
}
