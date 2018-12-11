<?php

namespace Restek\EzPlatformDevToolsBundle\Command;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Export database command
 *
 * @author Travis Raup <travis.raup@restek.com>
 */
class DatabaseExport extends AbstractCommand
{
  /**
   * @var string
   */
  protected static $defaultName = 'db:export';

  protected function configure()
  {
    $this
        ->setName(self::$defaultName)
        ->setDescription('Export the database')
        ->setHelp('This command allows you to export the database.')
        ->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'What should the export file be called?')
        ->addOption(
            'database',
            null,
            InputOption::VALUE_OPTIONAL,
            'Which database configuration do you want to backup?',
            null)
        ->addOption(
            'delete',
            null,
            InputOption::VALUE_NONE,
            'If the file already exists, would you like to replace it?',
            null
        )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $filename = $input->getArgument('filename');

    // set database if provided
    if($input->getOption('database')){
      $this->database['provider'] = $input->getOption('database');
    }

    if($input->getOption('delete')) {
      // delete file if it already exists
      $filesystem = new Filesystem(new Local($this->destination['config']['root']));
      if ($filesystem->has($filename . '.gz')) {
        $filesystem->delete($filename . '.gz');
      }
    }

    // export database command
    $this->executeSubCommand(
        'backup-manager:backup',
        array(
            'database' => $this->database['provider'],
            'destinations' => array($this->destination['provider']),
            '-c' => 'gzip',
            '--filename' => $input->getArgument('filename')
        ),
        $output
    );

    $output->writeln('Database exported successfully!');
  }
}