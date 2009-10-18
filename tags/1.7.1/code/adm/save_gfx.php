<?php

    if ($_POST['gdsr_action'] == 'save') {
        $ginc = array();

        $sizes = $_POST["gdsr_inc_size"];
        $new_stars = $_POST["gdsr_inc_star"];
        $new_sizes = array();
        foreach ($this->stars_sizes as $key => $size) {
            $new_sizes[$key] = in_array($key, $sizes) ? 1 : 0;
        }

        $ginc[] = $new_sizes;
        $ginc[] = $new_stars;

        $new_change = true;
        foreach ($new_sizes as $size => $value) {
            if ($ginc_sizes[$size] != $value) $new_change = false;
        }
        if (count($new_stars) != count($ginc_stars)) $new_change = false;
        else {
            foreach ($new_stars as $size => $value) {
                if ($ginc_stars[$size] != $value) $new_change = false;
            }
        }

        $sizes = $_POST["gdsr_inc_size_thumb"];
        $new_stars = $_POST["gdsr_inc_thumb"];
        $new_sizes = array();
        foreach ($this->thumb_sizes as $key => $size) {
            $new_sizes[$key] = in_array($key, $sizes) ? 1 : 0;
        }

        $ginc[] = $new_sizes;
        $ginc[] = $new_stars;

        if ($new_change) {
            foreach ($new_sizes as $size => $value) {
                if ($ginc_sizes_thumb[$size] != $value) $new_change = false;
            }
            if (count($new_stars) != count($ginc_stars_thumb)) $new_change = false;
            else {
                foreach ($new_stars as $size => $value) {
                    if ($ginc_stars_thumb[$size] != $value) $new_change = false;
                }
            }
        }

        update_option("gd-star-rating-inc", $ginc);

        if (!$new_change) {
            $gdsr_options["css_last_changed"] = time();
            update_option("gd-star-rating", $gdsr_options);
        }
    }

?>