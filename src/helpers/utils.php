<?php

namespace ex;

trait utils 
{

    /**
     * Check class exists and instantiate it.
     */
    use utils\class_instantiate;

    /**
     * Check a class exists.
     */
    use utils\class_exists;

    /**
     * Convert a string interpretation of WP_Query into an array.
     */
    use utils\string_to_array;
    
    /**
     * Convert array / string to UTF string.
     */
    use utils\to_utf8_string;

    /**
     * Convert array / string / object to a nice JSON format (for outputting to screen).
     */
    use utils\to_pretty_JSON;

    /**
     * Convert array / string / object to a nice printable format (for outputting to screen).
     */
    use utils\to_print_r;

    /**
     * Return true / false if section is disabled or not.
     */
    use utils\is_disabled;

    /**
     * Flattens a multidimensional associateive array.
     */
    use utils\array_flat;

    /**
     * Does a REGEX on all keys in an array and returns an array with just those elements.
     */
    use utils\array_regex_keys;

    
}