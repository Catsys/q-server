<?php

namespace QServer\Storages;

/**
 *  Storage interface for queue data storage
 */
interface StorageInterface {

    /**
     * Get one row with serialize data for \QServer\Jobs\JobInterface::fillFromArray.
     * The method must itself determine the next row(job) to be run by the worker and return
     * @return array
     */
    public function getRow() : array;

    /**
     * Get all rows with serialize data for \QServer\Jobs\JobInterface::fillFromArray.
     * @return array
     */
    public function getAllRows() : array;

    /**
     * Delete Job by ID
     *
     * @param $id
     * @return bool
     */
    public function delete($id) : bool;

    /**
     * Saving data to storage
     *
     * @param array $data Job data for saving to storage
     * @return bool
     */
    public function save(array $data) : bool;
}