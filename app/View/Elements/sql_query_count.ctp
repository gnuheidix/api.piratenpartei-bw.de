<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
/**
 * SQL Query Count element.  Dumps out the overall SQL query count.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Elements
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
if (!class_exists('ConnectionManager')) {
    return false;
}
$noLogs = !isset($logs);
if ($noLogs):
    $sources = ConnectionManager::sourceList();

    $logs = array();
    foreach ($sources as $source):
        $db = ConnectionManager::getDataSource($source);
        if (!method_exists($db, 'getLog')):
            continue;
        endif;
        $logs[$source] = $db->getLog();
    endforeach;
endif;

$queryCount = 0;
if ($noLogs || isset($_forced_from_dbo_)){
    foreach ($logs as $source => $logInfo){
        $queryCount += $logInfo['count'];
    }
}

echo $queryCount;