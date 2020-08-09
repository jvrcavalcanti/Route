<?php

namespace Accolon\Route\Traits;

use Accolon\Route\Files\File;
use Accolon\Route\Files\Image;

trait Files
{
    private function convertFileType(array $file)
    {
        if (strpos($file['type'], "image") !== false) {
            return new Image($file);
        }

        return new File($file);
    }

    private function convertFilesArrayToObject(array $files)
    {
        $new = [];
        foreach ($files as $name => $file) {
            if (isset($file[0])) {
                foreach ($file as $key => $array) {
                    $new[$name . "[{$key}]"] = $this->convertFileType($array);
                }
                continue;
            }

            $new[$name] = $this->convertFileType($file);
        }
        return $new;
    }

    private function parseFiles(array $files)
    {
        $new = [];
        foreach ($files as $name => $file) {
            if (is_array($file['name'])) {
                foreach ($file as $attr => $array) {
                    $j = 0;
                    foreach ($array as $value) {
                        $new[$name][$j][$attr] = $value;
                        $j ++;
                    }
                    $j = 0;
                }
                continue;
            }
            foreach ($file as $attr => $value) {
                $new[$name][$attr] = $value;
            }
        }
        return $new;
    }
}
