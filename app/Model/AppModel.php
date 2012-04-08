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
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2011, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
    
    /**
     * Start Lock - All other processes trying to lock, will wait
     * for at least seven seconds.
     * see: http://dev.mysql.com/doc/refman/5.1/de/miscellaneous-functions.html
     * @param string $name The name of the lock to aquire.
     * @return 0 if the lock couldn't be aquired, 1 otherwise.
     */
    public function lock($name = 'SPERRE'){
        $result = $this->query('SELECT GET_LOCK ("'.$name.'", 7)');
        return $result[0][0][key($result[0][0])];
    }
    
    /**
     * Checks whether the lock is free or not.
     * @param string $name The name of the lock to check.
     */
    public function isFreeLock($name = 'SPERRE'){
        $result = $this->query('SELECT IS_FREE_LOCK("'.$name.'")');
        return $result[0][0][key($result[0][0])];
    }
    
    /**
     * Releases the lock so that other processes can aquire it.
     * @param string $name The name of the lock to release.
     */
    public function unlock($name = 'SPERRE'){
        $dbo = $this->query('SELECT RELEASE_LOCK("'.$name.'")');
    }
}
