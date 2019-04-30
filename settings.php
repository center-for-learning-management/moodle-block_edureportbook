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
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

defined('MOODLE_INTERNAL') || die;

$roles = $DB->get_records_sql('SELECT r.* FROM {role} AS r, {role_context_levels} AS rcl WHERE r.id=rcl.roleid  AND rcl.contextlevel = 50 ORDER BY r.name ASC', array());
$options = array();
foreach($roles AS $role) {
    $options[$role->id] = (!empty($role->name) ? $role->name : $role->shortname);
}

$settings->add(new admin_setting_configselect('block_edureportbook/role_legalguardian', get_string('role_legalguardian', 'block_edureportbook'),
                   get_string('role_legalguardian:description', 'block_edureportbook'), get_config('block_legalguardian', 'role_legalguadian'), $options));

$settings->add(new admin_setting_configselect('block_edureportbook/role_student', get_string('role_student', 'block_edureportbook'),
                  get_string('role_student:description', 'block_edureportbook'), get_config('block_legalguardian', 'role_student'), $options));
