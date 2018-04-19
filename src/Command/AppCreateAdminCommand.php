<?php

namespace App\Command;

use App\Entity\AdminUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppCreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        parent::__construct($name);
        $this->passwordEncoder = $passwordEncoder;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');

        $admin = new AdminUser();

        $question = new Question('username: ');
        $username = $helper->ask($input, $output, $question);

        $question = new Question('password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $question);
        $password = $this->passwordEncoder->encodePassword($admin, $password);

        $admin->setUsername($username);
        $admin->setPassword($password);

        $this->em->persist($admin);
        $this->em->flush();

        $io->success("created {$username}");
    }
}
