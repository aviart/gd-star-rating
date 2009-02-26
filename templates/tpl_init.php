<?php

class gdTemplateElement {
    var $tag;
    var $description;

    function gdTemplateElement($t, $d) {
        $this->tag = $t;
        $this->description = $d;
    }
}

class gdTemplatePart {
    var $name;
    var $code;
    var $description;
    var $elements;
    var $size;

    function gdTemplatePart($n, $c, $d, $s = "single") {
        $this->name = $n;
        $this->code = $c;
        $this->description = $d;
        $this->size = $s;
        $this->elements = array();
    }
}

class gdTemplate {
    var $code;
    var $section;
    var $elements;
    var $parts;

    function gdTemplate($c, $s) {
        $this->code = $c;
        $this->section = $s;
        $this->elements = array();
        $this->parts = array();
    }

    function add_element($t, $d) {
        $this->elements[] = new gdTemplateElement($t, $d);
    }
    
    function add_part($n, $c, $d, $parts = array(), $s = "single") {
        $part = new gdTemplatePart($n, $c, $d, $s);
        $part->elements = $parts;
        $this->parts[] = $part;
    }
}

class gdTemplates {
    var $tpls;

    function gdTemplates() {
        $this->tpls = array();
    }

    function add_template($t) {
        $this->tpls[] = $t;
    }

    function get_list($section) {
        foreach ($this->tpls as $t) {
            if ($t->code == $section) return $t;
        }
        return null;
    }

    function list_sections() {
        $sections = array();
        $listed = array();
        foreach ($this->tpls as $t) {
            $code = $t->code;
            $name = $t->section;
            if (!in_array($code, $listed)) {
                $listed[] = $code;
                $sections[] = array("code" => $code, "name" => $name);
            }
        }
        return $sections;
    }

    function list_sections_assoc() {
        $sections = array();
        $listed = array();
        foreach ($this->tpls as $t) {
            $code = $t->code;
            $name = $t->section;
            if (!in_array($code, $listed)) {
                $listed[] = $code;
                $sections[$code] = $name;
            }
        }
        return $sections;
    }
}

?>
