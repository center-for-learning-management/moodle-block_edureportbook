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
    'block_edureportbook_participantsform' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'participantsform',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Load the participants-form of a course',
        'type'        => 'read',
        'ajax'        => 1,
    ),
    'block_edureportbook_participantsstore' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'participantsstore',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Store the participants of a course',
        'type'        => 'write',
        'ajax'        => 1,
    ),
    'block_edureportbook_removeblock' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'removeblock',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Remove the block of this plugin',
        'type'        => 'write',
        'ajax'        => 1,
    ),
    'block_edureportbook_separateform' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'separateform',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Load the separate-form of a course',
        'type'        => 'read',
        'ajax'        => 1,
    ),
    'block_edureportbook_separatestore' => array(
        'classname'   => 'block_edureportbook_external',
        'methodname'  => 'separatestore',
        'classpath'   => 'blocks/edureportbook/externallib.php',
        'description' => 'Store the separate-form of a course',
        'type'        => 'write',
        'ajax'        => 1,
    ),
);
