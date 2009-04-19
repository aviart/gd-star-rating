<?php

if (class_exists("WP_Widget")) {
    class gdsrWidgetTop extends WP_Widget {
        function gdsrWidgetTop() {
            $widget_ops = array('classname' => 'widget_gdstarrating_top', 'description' => __("Overall blog rating results.", "gd-star-rating"));
            $control_ops = array('width' => 440);
            $this->WP_Widget('gdstartop', 'GD Blog Rating', $widget_ops, $control_ops);
        }

        function widget($args, $instance) {
            global $gdsr, $userdata;
            extract($args, EXTR_SKIP);

            if ($instance["display"] == "hide" || ($instance["display"] == "users" && $userdata->ID == 0) || ($instance["display"] == "visitors" && $userdata->ID > 0)) return;

            echo $before_widget.$before_title.$instance['title'].$after_title;
            echo $gdsr->render_top_widget($instance);
            echo $after_widget;
        }

        function update($new_instance, $old_instance) {
            $instance = $old_instance;

            $instance['title'] = strip_tags(stripslashes($new_instance['title']));
            $instance['display'] = $new_instance['display'];
            $instance['select'] = $new_instance['select'];
            $instance['show'] = $new_instance['show'];
            $instance['div_template'] = $new_instance['div_template'];
            $instance['div_filter'] = $new_instance['div_filter'];
            $instance['div_elements'] = $new_instance['div_elements'];
            $instance['template'] = stripslashes(htmlentities($new_instance['template'], ENT_QUOTES, STARRATING_ENCODING));

            return $instance;
        }

        function form($instance) {
            global $gdsr;
            $instance = wp_parse_args((array)$instance, $gdsr->default_widget_top);

            include(STARRATING_PATH.'widgets/top_28/part_basic.php');
            include(STARRATING_PATH.'widgets/top_28/part_filter.php');
            include(STARRATING_PATH.'widgets/top_28/part_template.php');
        }
    }
}

?>
