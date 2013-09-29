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
			$request 	= new \MyApp\UseCase\LoginRequest(
				array(
					'email' 	=> $input->getArgument( 'username' ),
					'password' 	=> $input->getArgument( 'password' )
				)
			);

			// Create our Use Case, that transforms the LoginRequest into a LoginResponse
			$user_repo 	= $app['repositories.user'];
			$use_case 	= new \MyApp\UseCase\Login( $user_repo, $app['password_encoder'] );
			$response 	= $use_case->execute( $request );

			$output->writeln( "<info>Success!</info>");
		}
		catch( \Exception $e )
		{
			$output->writeln( "<error>Some error has occured: " . $e->getMessage() . "</error>");
		}
    }
}