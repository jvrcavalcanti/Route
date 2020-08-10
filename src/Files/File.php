<?php

namespace Accolon\Route\Files;

class File
{
    protected string $name;
    protected string $type;
    protected string $tmp_name;
    protected int $error;
    protected int $size;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getError()
    {
        return $this->name;
    }

    public function getSize()
    {
        return $this->name;
    }

    public function save(?string $name = null, string $path = "./")
    {
        if (is_null($name)) {
            $name = md5(microtime(true));
        }

        $type = explode("/", $this->type)[1];

        $newName = $path . $name . ".{$type}";

        return move_uploaded_file($this->tmp_name, $newName);
    }
}
