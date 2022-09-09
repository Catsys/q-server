<?php

namespace QServer\Storages;

class StorageFile implements StorageInterface {

    private $dir = 'data';
    private $DBfilename = 'job_';

    public function save(array $data) : bool {
        $this->createDir();
        file_put_contents($this->getFulPathToFile($this->DBfilename.$data['created'].'_'.$data['id']), json_encode($data).PHP_EOL);
        return true;
    }

    public function getRow() {
    
        if (!$files = \glob($this->getFulPathToFile($this->DBfilename.'*'))) {
            return [];
        }
        
        $fileName = $files[array_rand($files)];

        if (!$data = \json_decode(file_get_contents($fileName), true)) {
            return $this->getRow();
        }
        
        return $data;
    }

    public function delete($id) {
        if (!$files = \glob($this->getFulPathToFile($this->DBfilename.'*_'.$id))) {
            return false;
        }

        return unlink(current($files));
    }


    private function createDir() {
        @mkdir('data');
    }

    private function getFulPathToFile($file) {
        return __PROJECT_ROOT__."/{$this->dir}/{$file}";
    }

}