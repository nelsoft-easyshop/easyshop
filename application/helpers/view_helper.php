<?php



/**
 * Include specified scripts.
 * 
 * @param string|array $scripts js to include
 */
if (! function_exists('include_javascripts')) {
    function include_javascripts($scripts) {
        $javascriptIncluder = get_instance()->kernel->serviceContainer['javacsript_includer'];
        $javascriptIncluder->includeScripts($scripts);
    }
}
