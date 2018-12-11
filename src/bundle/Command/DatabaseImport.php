<?php

namespace Restek\EzPlatformDevToolsBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Import database command
 *
 * @author Travis Raup <travis.raup@restek.com>
 */
class DatabaseImport extends AbstractCommand
{
  /**
   * @var string
   */
  protected static $defaultName = 'db:import';

  protected function configure()
  {
    $this
        ->setName(self::$defaultName)
        ->setDescription('Import a database')
        ->setHelp('This command allows you to import a database.')
        ->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'Where on the storage is the file?')
        ->addOption(
            'database',
            null,
            InputOption::VALUE_OPTIONAL,
            'What database configuration do you want to backup?',
            null)

    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    // set database if provided
    if($input->getOption('database')){
      $this->database['provider'] = $input->getOption('database');
    }

    // import database command
    $this->executeSubCommand(
        'backup-manager:restore',
        array(
            'database' => $this->database['provider'],
            'destination' => $this->destination['provider'],
            'file_path' => $input->getArgument('filename'),
            '-c' => 'gzip'
        ),
        $output
    );

    $output->writeln('Database imported successfully!');

    // clear redis cache command
    $this->executeSubCommand(
        'cache:pool:clear',
        array(
            'pools' => array('cache.redis')
        ),
        $output
    );

    // reindex ezplatform command
    $this->executeSubCommand(
        'ezplatform:reindex',
        array(),
        $output
    );

  }
}
