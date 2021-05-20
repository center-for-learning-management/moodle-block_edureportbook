<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_edureportbook
 * @copyright  2019 Digital Education Society (http://www.dibig.at)
 * @author     Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// We define the web service functions to install.
$functions = array(
    'block_edureportbook_relation' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'relation',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Set relation of a user',
        'type'        => 'write',
        'ajax'        => 1,
    ),
    'block_edureportbook_relations' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'relations',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Get relations of a user',
        'type'        => 'read',
        'ajax'        => 1,
    ),
);
