<?php

namespace Satriotol\Fastcrud\Logging;

use Monolog\Logger;

class FastcrudTelegramLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('telegram');
        
        $logger->pushHandler(new FastcrudTelegramHandler(
            $config['token'],
            $config['chat_id'],
            $config['level'] ?? Logger::ERROR
        ));

        return $logger;
    }
}