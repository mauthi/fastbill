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
    public function create(array $data);
    public function update($id, array $data);
}