<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Collector
{

    /**
     * @var TouchQueryContainer
     */
    protected $queryContainer;

    /**
     * @var ExporterInterface
     */
    protected $exporter;

    /**
     * @param TouchQueryContainer $queryContainer
     * @param ExporterInterface $exporter
     */
    public function __construct(
        TouchQueryContainer $queryContainer,
        ExporterInterface $exporter
    ) {
        $this->queryContainer = $queryContainer;
        $this->exporter = $exporter;
    }

    /**
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface[]
     */
    public function exportForLocale(LocaleTransfer $locale, OutputInterface $output = null)
    {
        $types = $this->queryContainer->queryExportTypes()->find();

        $results = [];

        foreach ($types as $type) {
            $startTime = microtime(true);

            if (isset($output)) {
                $output->writeln('Started export for type: ' . $type);
            }

            $result = $this->exporter->exportByType($type, $locale);

            $this->handleResult($result);

            if (isset($output)) {
                $output->writeln('Finished export for type: ' . $type . ' after ' . number_format(microtime(true) - $startTime, 4) . ' s');
            }

            if ($result instanceof BatchResultInterface) {
                $results[$type] = $result;
            }
        }

        return $results;
    }

    /**
     * @param BatchResultInterface $result
     */
    protected function handleResult(BatchResultInterface $result)
    {
        if ($result->isFailed()) {
            throw new BatchResultException(
                sprintf(
                    'Processed %d from %d for locale %s, where %d were deleted and %d failed.',
                    $result->getProcessedCount(),
                    $result->getTotalCount(),
                    $result->getProcessedLocale(),
                    $result->getDeletedCount(),
                    $result->getFailedCount()
                )
            );
        }
    }

}
