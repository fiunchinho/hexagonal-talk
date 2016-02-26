<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoginCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName( 'app:login' )
            ->setDescription( 'Command to login in your CLI app' )
            ->addArgument( 'username', InputArgument::REQUIRED, 'Your username' )
            ->addArgument( 'password', InputArgument::REQUIRED, 'Your password' )
            ->setHelp( 'Command to login in your CLI app' )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try
		{
			// Create LoginRequest with the required parameters
			$request 	= new LoginRequest($input->getArgument('username'), $input->getArgument('password'));

			// Create our Use Case, that transforms the LoginRequest into a LoginResponse
			$use_case 	= new \MyApp\UseCase\Login( $app['session'], $app['repositories.user'], $app['password_encoder'] );
			$use_case->execute( $request );

			$output->writeln( "<info>Success!</info>");
		}
		catch( \Exception $e )
		{
			$output->writeln( "<error>Some error has occured: " . $e->getMessage() . "</error>");
		}
    }
}