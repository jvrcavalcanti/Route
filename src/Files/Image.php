<?php

namespace Accolon\Route\Files;

class Image extends File
{
    protected string $imageType;
    protected $image;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $type = explode("/", $this->type)[1];
        $this->imageType = $type;

        $this->load();
    }

    public function getWidth()
    {
        return imagesx($this->image);
    }

    public function getHeight()
    {
        return imagesy($this->image);
    }

    public function resizeHeight($height)
    {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    public function resizeWidth($width)
    {
        $ratio = $width / $this->getWidth();
        $height = $this->getHeight() * $ratio;
        $this->resize($width, $height);
    }

    public function scale($scale)
    {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    public function output($image_type = IMAGETYPE_JPEG)
    {
 
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        }
        
        if ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        }
        
        if ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    public function resize($width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $newImage,
            $this->image,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $this->getWidth(),
            $this->getHeight()
        );
        $this->image = $newImage;
    }

    public function load()
    {
        $name = $this->tmp_name;
        $imageInfo = getimagesize($this->tmp_name);
        $type = $imageInfo[2];

        if ($type === IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($name);
        }

        if ($type === IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($name);
        }

        if ($type === IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($name);
        }
    }

    public function save(?string $name = null, string $path = "./", array $options = [])
    {
        if (is_null($name)) {
            $name = md5(microtime(true));
        }

        $type = $options['type'] ?? IMAGETYPE_JPEG;
        $compression = $options['compression'] ?? 75;

        $newName = $path . $name . "." . $this->imageType;

        if ($type === IMAGETYPE_JPEG) {
            return imagejpeg($this->image, $newName, $compression);
        }

        if ($type === IMAGETYPE_GIF) {
            return imagegif($this->image, $newName);
        }

        if ($type === IMAGETYPE_PNG) {
            return imagepng($this->image, $newName);
        }
    }
}
