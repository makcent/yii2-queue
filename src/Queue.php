<?php

namespace makcent\queue;
use Yii;
use yii\base\InvalidConfigException;
use yii\redis\Connection;
use makcent\queue\Hendle;
use makcent\queue\Job;

class Queue extends Handle
{

    /**
     * @var string Default redis component name
     */
    public $redis = 'redis';
    /**
     * Class initialization logic
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (is_string($this->redis)) {
            $this->redis = Yii::$app->get($this->redis);
        } elseif (is_array($this->redis)) {
            $this->redis = Yii::createObject($this->redis);
        }
        if (!$this->redis instanceof Connection) {
            throw new InvalidConfigException("Queue::redis must be either a Redis connection instance or the application component ID of a Redis connection.");
        }
    }
    protected function pushInternal($payload, $queue = null)
    {
        $this->redis->rpush($this->getQueue($queue), $payload);
        $payload = json_decode($payload, true);
        return $payload['id'];
    }
    public function popInternal($queue = null)
    {
        $payload = $this->redis->lpop($this->getQueue($queue));
        if ($payload) {
            return new Job($this, $payload, $queue);
        }
        return null;
    }
}