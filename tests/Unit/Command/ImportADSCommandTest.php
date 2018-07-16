<?php


namespace Adshares\AdsManager\Tests\Unit\Command;

use Adshares\AdsManager\AdsImporter\Exception\AdsClientException;
use Adshares\AdsManager\AdsImporter\Importer;
use Adshares\AdsManager\AdsImporter\ImporterResult;
use Adshares\AdsManager\Command\ImportADSCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

final class ImportADSCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $kernel = static::bootKernel();
        $application = new Application($kernel->getName());

        $result = new ImporterResult();
        $result->blocks = 10;
        $result->packages = 20;
        $result->transactions = 111;
        $result->nodes = 3;
        $result->accounts = 10;

        $importer = $this->createMock(Importer::class);
        $importer
            ->method('import')
            ->willReturn($result);

        $application->add(new ImportADSCommand($importer));

        $command = $application->find('ads:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();

        $this->assertContains('10 blocks', $output);
        $this->assertContains('20 packages', $output);
        $this->assertContains('111 transactions', $output);
        $this->assertContains('3 nodes', $output);
        $this->assertContains('10 accounts', $output);
    }

    public function testSecondExecutionWhenImporterThrowsException()
    {
        $kernel = static::bootKernel();
        $application = new Application($kernel->getName());

        $result = new ImporterResult();
        $result->blocks = 10;
        $result->packages = 20;
        $result->transactions = 111;
        $result->nodes = 3;
        $result->accounts = 10;

        $importer = $this->createMock(Importer::class);
        $importer
            ->expects($this->at(0))
            ->method('import')
            ->will($this->throwException(new AdsClientException('')));

        $importer
            ->expects($this->at(1))
            ->method('import')
            ->willReturn($result);

        $application->add(new ImportADSCommand($importer));

        $command = $application->find('ads:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'  => $command->getName(),
        ));

        $output = $commandTester->getDisplay();

        $this->assertContains('10 blocks', $output);
        $this->assertContains('20 packages', $output);
        $this->assertContains('111 transactions', $output);
        $this->assertContains('3 nodes', $output);
        $this->assertContains('10 accounts', $output);
    }
}
