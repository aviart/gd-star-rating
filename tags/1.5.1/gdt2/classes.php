<?php

/*
Name:    gdTemplatesT2
Library: Classes
Version: 2.1.0
Author:  Milan Petrovic
Email:   milan@gdragon.info
Website: http://www.gdragon.info/
*/

class gdTemplateDB {
    function get_templates($section = '', $default_sort = false, $only_default = false) {
        global $wpdb, $table_prefix;
        if ($section != '') $section = sprintf(" WHERE section = '%s'", $section);
        $default_sort = $default_sort ? "`default` desc, preinstalled desc, " : "";
        $default_limit = $only_default ? " LIMIT 0, 1" : "";

        $sql = sprintf("select * from %s%s%s order by %stemplate_id asc%s", $table_prefix, STARRATING_TPLT2_TABLE, $section, $default_sort, $default_limit);
        if ($only_default) return $wpdb->get_row($sql);
        return $wpdb->get_results($sql);
    }

    function get_template($id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("SELECT * FROM %s%s WHERE `template_id` = %s",
            $table_prefix, STARRATING_TPLT2_TABLE, $id);
        return $wpdb->get_row($sql);
    }

    function get_templates_paged($section = '', $start = 0, $limit = 20) {
        global $wpdb, $table_prefix;
        if ($section != '') $section = sprintf(" WHERE section = '%s'", $section);

        $sql = sprintf("select * from %s%s%s limit %s, %s", $table_prefix, STARRATING_TPLT2_TABLE, $section, $start, $limit);
         return $wpdb->get_results($sql);
    }

    function get_templates_count($section = '') {
        global $wpdb, $table_prefix;
        if ($section != '') $section = sprintf(" WHERE section = '%s'", $section);

        $sql = sprintf("select count(*) from %s%s%s", $table_prefix, STARRATING_TPLT2_TABLE, $section);
        return $wpdb->get_var($sql);
    }

    function set_templates_defaults($post) {
        global $wpdb, $table_prefix;

        foreach ($post as $code => $value) {
            $sql = sprintf("update %s%s set `default` = '0' where section = '%s'", $table_prefix, STARRATING_TPLT2_TABLE, $code);
            $wpdb->query($sql);
            $sql = sprintf("update %s%s set `default` = '1' where template_id = %s", $table_prefix, STARRATING_TPLT2_TABLE, $value);
            $wpdb->query($sql);
        }
    }

    function edit_template($general, $elements) {
        global $wpdb, $table_prefix;
        $sql = sprintf("UPDATE %s%s SET `section` = '%s', `name` = '%s', `description` = '%s', `elements` = '%s', `dependencies` = '%s', `preinstalled` = '%s' WHERE `template_id` = %s",
            $table_prefix, STARRATING_TPLT2_TABLE, $general["section"], $general["name"], $general["description"], serialize($elements), serialize($general["dependencies"]), $general["preinstalled"], $general["id"]);
        $wpdb->query($sql);
        return $general["id"];
    }

    function delete_template($id) {
        global $wpdb, $table_prefix;
        $sql = sprintf("DELETE FROM %s%s WHERE `template_id` = %s",
            $table_prefix, STARRATING_TPLT2_TABLE, $id);
        return $wpdb->query($sql);
    }

    function add_template($general, $elements) {
        global $wpdb, $table_prefix;
        $sql = sprintf("INSERT INTO %s%s (`section`, `name`, `description`, `elements`, `dependencies`, `preinstalled`, `default`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '0')",
            $table_prefix, STARRATING_TPLT2_TABLE, $general["section"], $general["name"], $general["description"], serialize($elements), serialize($general["dependencies"]), $general["preinstalled"]);
        $wpdb->query($sql);
        return $wpdb->insert_id;
    }
}

class gdTemplateHelper {
    function render_templates_section($section, $name, $selected = "0", $width = 205) {
        $templates = gdTemplateDB::get_templates($section, true);
        ?>
<select style="width: <?php echo $width ?>px;" name="<?php echo $name; ?>" id="<?php echo $name; ?>">
        <?php
        foreach ($templates as $t) {
            if ($t->template_id == $selected) $select = ' selected="selected"';
            else $select = '';
            echo sprintf('<option value="%s"%s>%s</option>', $t->template_id, $select, $t->name);
        }
        ?>
</select>
        <?php
    }

    function render_templates_sections($name, $section, $empty = true, $selected = "") {
        ?>
<select name="<?php echo $name; ?>" id="<?php echo $name; ?>">
<?php if ($empty) { ?><option value=""<?php echo $selected == '' ? ' selected="selected"' : ''; ?>>All Sections</option><?php } ?>
        <?php
            foreach ($section as $s) {
                echo sprintf('<option value="%s"%s>%s</option>', $s["code"], ($selected == $s["code"] ? ' selected="selected"' : ''),  $s["name"]);
            }
        ?>
</select>
        <?php
    }
}

class gdTemplateRender {
    var $tpl;
    var $dep;
    var $elm;
    var $tag;

    function gdTemplateRender($id, $section) {
        $this->tpl = wp_gdtpl_get_template($id);
        if (!is_object($this->tpl) || $this->tpl->section != $section) {
            $t = gdTemplateDB::get_templates($section, true, true);
            $id = $t->template_id;
            $this->tpl = wp_gdtpl_get_template($id);
        }
        $this->dep = array();

        $dependencies = unserialize($this->tpl->dependencies);
        if (is_array($dependencies)) {
            foreach ($dependencies as $key => $value) $this->dep[$key] = new gdTemplateRender($value, $key);
        }
        $this->elm = unserialize($this->tpl->elements);
        if (is_array($this->elm)) {
            foreach($this->elm as $key => $value) {
                preg_match_all('(%.+?%)', $value, $matches, PREG_PATTERN_ORDER);
                $this->tag[$key] = $matches[0];
            }
        }
    }
}

class gdTemplateElement {
    var $tag;
    var $description;
    var $tpl;

    function gdTemplateElement($t, $d) {
        $this->tag = $t;
        $this->description = $d;
        $this->tpl = -1;
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

class gdTemplateTpl {
    var $code;
    var $tag;
    
    function gdTemplateTpl($c, $t) {
        $this->code = $c;
        $this->tag = $t;
    }
}

class gdTemplate {
    var $code;
    var $section;
    var $elements;
    var $parts;
    var $tag;
    var $tpls;
    var $tpls_tags;

    function gdTemplate($c, $s, $t = "") {
        $this->code = $c;
        $this->section = $s;
        $this->tag = $t;
        $this->elements = array();
        $this->parts = array();
        $this->tpls = array();
        $this->tpls_tags = array();
    }

    function add_template($c, $t) {
        $this->tpls[] = new gdTemplateTpl($c, $t);
        $this->tpls_tags[] = $t;
    }

    function add_element($t, $d) {
        $tpl = new gdTemplateElement($t, $d);
        if (in_array($t, $this->tpls_tags)) {
            $k = array_keys($this->tpls_tags, $t);
            if (count($k) == 1) $tpl->tpl = $k[0];
        }
        $this->elements[] = $tpl;
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

    function find_template_tag($code) {
        $tag = "";
        foreach ($this->tpls as $t) {
            if ($t->code == $code) {
                $tag = $t->tag;
                break;
            }
        }
        return $tag;
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

function wp_gdtpl_get_template($template_id) {
    global $gdsr_cache_templates;

    $tpl = $gdsr_cache_templates->get($template_id);
    if (!is_null($tpl)) return $tpl;
    else {
        $tpl = gdTemplateDB::get_template($template_id);
        $gdsr_cache_templates->set($template_id, $tpl);
        return $tpl;
    }
}

?>
