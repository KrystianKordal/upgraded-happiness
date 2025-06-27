<?php

namespace App\Commands;

use App\Libraries\ConsoleMonitor;
use Clue\React\Redis\RedisClient;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\Console;
use Config\Services;
use React\EventLoop\Loop;

class CoasterMonitorCommand extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'coaster:monitor';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = '';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'coaster:monitor [arguments] [options]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params): void
    {
        $monitor = Services::consoleMonitor();
        $coasterRepository = Services::coasterRepository();

        $redis = Services::redisAsync();
        $redis->subscribe('coaster:update');
        $redis->on('message', function ($channel, $message) use ($monitor, $coasterRepository) {
            $monitor->print($coasterRepository->getAll());
        });

        $monitor->print($coasterRepository->getAll());

        $loop = Loop::get();
        $loop->run();
    }
}
