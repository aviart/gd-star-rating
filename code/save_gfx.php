<?php

    if ($_POST['gdsr_action'] == 'save') {
        $sizes = $_POST["gdsr_inc_size"];
        $new_stars = $_POST["gdsr_inc_star"];
        $new_sizes = array();
        foreach ($this->stars_sizes as $key => $size) {
            $new_sizes[$key] = in_array($key, $sizes) ? 1 : 0;
        }

        $ginc = array();
        $ginc[] = $new_sizes;
        $ginc[] = $new_stars;
        update_option("gd-star-rating-inc", $ginc);

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

        if (!$new_change) {
            $gdsr_options["css_last_changed"] = time();
            update_option("gd-star-rating", $gdsr_options);
        }
    }

?>
