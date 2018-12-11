<?php

namespace Restek\EzPlatformDevToolsBundle\Command;

use BackupManager\Databases\DatabaseProvider;
use BackupManager\Filesystems\FilesystemProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Database Manager Abstract Class
 *
 * @author Travis Raup <travis.raup@restek.com>
 */
abstract class AbstractCommand extends Command
{
  /**
   * @var array
   */
  protected $database = array();

  /**
   * @var array
   */
  protected $destination = array();

  /**
   * @var string
   */
  protected $filename;

  /**
   * AbstractCommand constructor.
   * @param DatabaseProvider $databaseProvider
   * @param FilesystemProvider $filesystemProvider
   * @throws \BackupManager\Config\ConfigFieldNotFound
   * @throws \BackupManager\Config\ConfigNotFoundForConnection
   * @throws \BackupManager\Databases\DatabaseTypeNotSupported
   */
  public function __construct(DatabaseProvider $databaseProvider, FilesystemProvider $filesystemProvider)
  {
    // default database
    $this->database['provider'] = $databaseProvider->getAvailableProviders()[0];
    foreach((array) $databaseProvider->get($this->database['provider']) as $databaseConfig) {
      $this->database['config'] = $databaseConfig;
    }

    // default destination
    $this->destination['provider'] = $filesystemProvider->getAvailableProviders()[0];
    $this->destination['config'] = $filesystemProvider->getConfig($filesystemProvider->getAvailableProviders()[0]);

    parent::__construct();
  }

  /**
   * @param string $name
   * @param array $parameters
   * @param OutputInterface $output
   * @return int
   * @throws \Exception
   */
  protected function executeSubCommand(string $name, array $parameters, OutputInterface $output)
  {
    return $this->getApplication()
        ->find($name)
        ->run(new ArrayInput($parameters), $output);
  }
}