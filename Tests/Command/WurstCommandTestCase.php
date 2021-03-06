<?php

namespace MarcW\Bundle\WurstBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use MarcW\Bundle\WurstBundle\Command\WurstCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class WurstCommandTestCase extends \PHPUnit_Framework_TestCase
{
    protected $defaultWurst = 'classic';
    protected $wurstResourcesDirectory;
    protected $sideResourcesDirectory;
    protected $command;
    protected $commandTester;
    protected $wurstTypes;
    protected $sides;

    public function __construct()
    {
        $resourceDirectory = $this->getResourceDirectory();
        $this->wurstResourcesDirectory = $resourceDirectory.'wurst/';
        $this->sideResourcesDirectory = $resourceDirectory.'sides/';

        $this->setCommand();
        $this->commandTester = new CommandTester($this->command);

        $this->wurstTypes = $this->findFilenamesFromGivenDirectory($this->wurstResourcesDirectory);
        $this->sides = $this->findFilenamesFromGivenDirectory($this->sideResourcesDirectory);
    }

    protected function getResourceDirectory()
    {
        $sourceDirectory = __DIR__.'/../../';
        $resourceDirectory = $sourceDirectory.'Resources/';

        return $resourceDirectory;
    }

    protected function setCommand()
    {
        $mockedKernel = $this->getMock('Symfony\\Component\\HttpKernel\\Kernel', array(), array(), '', false);
        $application = new Application($mockedKernel);
        $application->add(new WurstCommand());

        $this->command = $application->find('wurst:print');
    }

    protected function findFilenamesFromGivenDirectory($givenDirectory)
    {
        $foundFiles = Finder::create()
            ->in($givenDirectory)
            ->name('*.txt')
            ->depth(0)
            ->filter(function (SplFileInfo $file) {
                return $file->isReadable();
            })
        ;

        $filenames = array();
        foreach ($foundFiles as $foundFile) {
            $filenames[] = basename($foundFile->getRelativePathName(), '.txt');
        }

        return $filenames;
    }
}
