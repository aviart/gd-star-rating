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
    var $id;
    var $class;
    var $name;
    var $description;
    var $elements;
    var $default = false;
    
    function gdTemplate($i, $c, $n, $d, $dfl = false) {
        $this->id = $i;
        $this->class = $c;
        $this->name = $n;
        $this->description = $d;
        $this->default = $dfl;
        $elements = array();
    }

    function add_element($t, $d) {
        $this->elements[] = new gdTemplateElement($t, $d);
    }
}

class gdTemplates {
    var $index;
    var $tpls;

    function gdTemplates() {
        $this->tpls = array();
        $this->index = 1;
    }

    function add_template($t) {
        $this->tpls[] = $t;
        $this->index++;
    }

    function get_classes() {

    }
}

$tpls = new gdTemplates();

$t = new gdTemplate($tpls->index, __("Standard Ratings"), __("Default"), __(""), true);
$t->add_element("%RATING%", __(""));
$tpls->add_template($t);

?>
