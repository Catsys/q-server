<?php

namespace QServer\Storages;

/**
 *  Storage implementation. Storage in files
 */
class StorageFile implements StorageInterface {

    private $dir = 'data';
    private $DBfilename = 'job_';

    /**
     * @inheritdoc
     */
    public function save(array $data) : bool {
        $this->createDir();
        file_put_contents($this->getFulPathToFile($this->DBfilename.$data['created'].'_'.$data['id']), json_encode($data).PHP_EOL);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function getRow() : array {
    
        if (!$files = \glob($this->getFulPathToFile($this->DBfilename.'*'))) {
            return [];
        }
        
        $fileName = $files[array_rand($files)];

        if (!$data = \json_decode(file_get_contents($fileName), true)) {
            unlink($fileName);
            return $this->getRow();
        }
        
        return $data;
    }

    /**
     * @inheritdoc
     */
    public function getAllRows(): array {

        if (!$files = \glob($this->getFulPathToFile($this->DBfilename.'*'))) {
            return [];
        }

        $result = [];

        foreach ($files as $fileName) {
            if ($data = \json_decode(file_get_contents($fileName), true)) {
                $result[] = $data;
            }
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function countRows(): int {
        return \count(\glob($this->getFulPathToFile($this->DBfilename.'*')));
    }

    /**
     * @inheritdoc
     */
    public function delete($id) : bool {
        if (!$files = \glob($this->getFulPathToFile($this->DBfilename.'*_'.$id))) {
            return false;
        }

        return unlink(current($files));
    }


    /**
     * Create dir for storage
     *
     * @return void
     */
    private function createDir() {
        @mkdir('data');
    }

    /**
     * @param $file
     * @return string
     */
    private function getFulPathToFile($file) {
        return __PROJECT_ROOT__."/{$this->dir}/{$file}";
    }

}