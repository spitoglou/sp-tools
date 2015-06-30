<?php
/**
     * index.php.
     * @author Stavros Pitoglou <s.pitoglou@csl.gr>
     */

require_once 'config/config.inc';

echo '<pre>';

$route=$_GET['route'];


switch ($route) {
    case 'femigr':
        require "src/fe/fe_migration.php";
    break;

    case 'kintest':
        require "src/test/kintest.php";
    break;

    case 'queue':
        require "src/fe/queue.php";
    break;
    default:
        echo "No valid route selected";
    break;
}

