<?php


function ex_css()
{
    ?>
    <style type="text/css">
        <?php include 'css/admin.css'; ?>
    </style>
    <?php
}

add_action('acf/input/admin_head', 'ex_css');
