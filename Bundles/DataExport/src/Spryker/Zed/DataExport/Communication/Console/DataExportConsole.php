<?php

namespace Spryker\Zed\DataExport\Communication\Console;

use Generated\Shared\Transfer\DataExportReportTransfer;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\DataExport\Business\DataExportFacadeInterface getFacade()
 * @method \Spryker\Zed\DataExport\Communication\DataExportCommunicationFactory getFactory()
 * @method \Spryker\Zed\DataExport\DataExportConfig getConfig()
 */
class DataExportConsole extends Console
{
    use BundleConfigResolverAwareTrait;

    protected const COMMAND_NAME = 'data:export';

    protected const OPTION_CONFIG = 'config';
    protected const OPTION_SHORTCUT_CONFIG = 'c';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->addOption(static::OPTION_CONFIG, static::OPTION_SHORTCUT_CONFIG, InputOption::VALUE_REQUIRED, 'Define configuration file');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exportConfigurationsPath = $this->getConfig()->getExportConfigurationsPath(). '/' . $input->getOption(static::OPTION_CONFIG);
        $exportConfigurations = $this->getFactory()->getService()->readConfiguration($exportConfigurationsPath);

        $dataExportReportTransfer = $this->getFacade()->exportBatch($exportConfigurations);

        $this->printDataExportReport($output, $dataExportReportTransfer);

        return $dataExportReportTransfer->getIsSuccess() ? static::CODE_SUCCESS : static::CODE_ERROR;
    }

    /**
     * @param OutputInterface $output
     * @param DataExportReportTransfer $dataExportReportTransfer
     */
    protected function printDataExportReport(OutputInterface $output, DataExportReportTransfer $dataExportReportTransfer): void
    {
        foreach($dataExportReportTransfer->getResults() as $dataExportResultTransfer) {
            foreach($dataExportResultTransfer->getDocuments() as $dataExportResultDocumentTransfer) {
                $output->writeln(sprintf("<fg=white>Document: %s (Objects: %d)</fg=white>", $dataExportResultDocumentTransfer->getName(), $dataExportResultDocumentTransfer->getObjectCount()));
            }
        }
    }
}
