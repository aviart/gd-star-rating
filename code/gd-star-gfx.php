<?php

class GDgfxBase
{
    var $name = "";
    var $folder = "";
    var $type = "png";
    var $author = "";
    var $email = "";
    var $url = "";
    var $design = "";

    var $info_file = "stars";
    var $gfx_path = "";
    var $gfx_url = "";

    function GDgfxBase($folder, $primary = true) {
        $this->folder = $folder;
        if ($primary) {
            $this->gfx_path = STARRATING_PATH.$folder."/";
            $this->gfx_url = STARRATING_URL.$folder."/";
        }
        else {
            $this->gfx_path = STARRATING_XTRA_PATH.$folder."/";
            $this->gfx_url = STARRATING_XTRA_URL.$folder."/";
        }
        $this->import();
    }
    
    function import() {
        $data = $this->load_info_file();
        $this->name = $data["name"];
        if (isset($data["type"])) $this->type = $data["type"];
        if (isset($data["author"])) $this->author = $data["author"];
        if (isset($data["email"])) $this->email = $data["email"];
        if (isset($data["url"])) $this->url = $data["url"];
        if (isset($data["design"])) $this->design = $data["design"];
        return $data;
    }
    
    function load_info_file() {
        $path = $this->gfx_path.$this->info_file.".gdsr";
        $contents = file($path);
        $data = array();
        foreach ($contents as $line) {
            $key = trim(substr($line, 0, 8));
            $key = substr($key, 0, strlen($key) - 1);
            $value = trim(substr($line, 8));
            $data[$key] = $value;
        }
        return $data;
    }
}

class GDgfxStar extends GDgfxBase
{
    function GDgfxStar($folder, $primary = true) {
        parent::GDgfxBase($folder, $primary);
    }
}

class GDgfxTrend extends GDgfxBase
{
    var $size = 16;
    
    function GDgfxTrend($folder, $primary = true) {
        parent::GDgfxBase($folder, $primary);
        $this->info_file = "trend";
    }
    
    function import() {
        $data = parent::import();
        if (isset($data["size"])) $this->size = $data["size"];
    }
}

?>