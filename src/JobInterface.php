<?php


namespace makcent\queue;


interface JobInterface {
    public function execute();
}