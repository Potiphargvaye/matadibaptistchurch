<?php

namespace Better_Payment\Lite\Contracts;

/**
 * The Page render interface
 * 
 * @since 0.0.1
 */

 interface Pageable {
         
         /**
        * Page content
        *
        * @return string
        * @since 0.0.1
        */
         public function render_page();
 }