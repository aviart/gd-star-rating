<?php

class gdTemplateElement {
    var $tag;
    var $description;

    function gdTemplateElement($t, $d) {
        $this->tag = $t;
        $this->description = $d;
    }
}

class gdTemplate {
    var $section;
    var $code;
    var $elements;

    function gdTemplate($c, $s) {
        $this->code = $c;
        $this->section = $s;
        $elements = array();
    }

    function add_element($t, $d) {
        $this->elements[] = new gdTemplateElement($t, $d);
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
}

?>
