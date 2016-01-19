<?php

namespace Lucaszz\TestsWithDatabaseExamples\Component\Logger;

use Doctrine\DBAL\Logging\SQLLogger;
use Psr\Log\LoggerInterface;

class Logger implements SQLLogger
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /** {@inheritdoc} */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->log($sql, (array) $params);
    }

    /** {@inheritdoc} */
    public function stopQuery()
    {
    }

    private function log($message, array $params)
    {
        $this->logger->debug($message, $params);
    }
}
