<?php //CPYRGHT
/**
 * api.piratenpartei-bw.de
 * 
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author  Thomas Heidrich, Adrian Kummerländer
 * @copyright Copyright (c) 2012 Thomas Heidrich and other authors
 */
?><?php
/**
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
 * @since         CakePHP(tm) v 1.2.0.5550
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('SchemaShell', 'Console/Command');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('CakeSchema', 'Model');

/**
 * Autoschema doesn't ask the user whether a table schould be really dropped
 * or created. It simply modifies the create method.
 */
class AutoschemaShell extends SchemaShell {

/**
 * Create database from Schema object
 * Should be called via the run method
 *
 * @param CakeSchema $Schema
 * @param string $table
 * @return void
 */
    protected function _create($Schema, $table = null) {
        $db = ConnectionManager::getDataSource($this->Schema->connection);

        $drop = $create = array();

        if (!$table) {
            foreach ($Schema->tables as $table => $fields) {
                $drop[$table] = $db->dropSchema($Schema, $table);
                $create[$table] = $db->createSchema($Schema, $table);
            }
        } elseif (isset($Schema->tables[$table])) {
            $drop[$table] = $db->dropSchema($Schema, $table);
            $create[$table] = $db->createSchema($Schema, $table);
        }
        if (empty($drop) || empty($create)) {
            $this->out(__d('cake_console', 'Schema is up to date.'));
            $this->_stop();
        }

        $this->out("\n" . __d('cake_console', 'The following table(s) will be dropped.'));
        $this->out(array_keys($drop));

        $this->out(__d('cake_console', 'Dropping table(s).'));
        $this->_run($drop, 'drop', $Schema);

        $this->out("\n" . __d('cake_console', 'The following table(s) will be created.'));
        $this->out(array_keys($create));

        $this->out(__d('cake_console', 'Creating table(s).'));
        $this->_run($create, 'create', $Schema);
        $this->out(__d('cake_console', 'End create.'));
    }
}
