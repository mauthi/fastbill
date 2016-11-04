<?php

namespace Fastbill\Resources;

/**
 * Interface ResourceInterface
 *
 * @namespace    Fastbill\Resources
 * @author     Mauthi <mauthi@gmail.com>
 */
interface ResourceInterface
{
    public function getAll();
    // public function getInactive();
    // public function getActive();
    public function create();
    public function update();
}