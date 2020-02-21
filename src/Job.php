<?php
namespace makcent\queue;
use Yii;

class Job
{
    protected $queueObject;
    protected $payload;
    protected $queueName;
    public function __construct($queueObject, $payload, $queueName)
    {
        $this->queueObject = $queueObject;
        $this->payload = $payload;
        $this->queueName = $queueName;
    }
    public function run()
    {
        $this->resolveAndRun(json_decode($this->payload, true));
    }
    public function getQueueObject()
    {
        return $this->queueObject;
    }
    protected function resolveAndRun(array $payload)
    {
        $instance = unserialize($payload['job']);
        $instance->execute();
    }
}