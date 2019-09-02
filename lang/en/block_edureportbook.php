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
 * @package   block_edureportbook
 * @copyright 2019 Digital Education Society (http://www.dibig.at)
 * @author    Robert Schrenk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'eduReportbook';
$string['privacy:metadata'] = 'This plugin does not store any personal data';
$string['edureportbook:addinstance'] = 'Add eduReportbook';
$string['edureportbook:myaddinstance'] = 'Add eduReportbook';

$string['assignstudents'] = 'Assign students';
$string['assignstudents:description'] = 'On the left side you see the students in your course. Please select the appropriate legal guardians for each student!';
$string['assignstudents:generalgroup:description'] = 'The following group is meant to connect everybody within this reportbook as a general means of exchange. It is recommended to select everybody for this group, but of course you can make a selection on your own!';

$string['default_group_all'] = 'General group for all';

$string['edureportbook:manage'] = 'Manage';
$string['missing_configuration'] = 'Missing appropriate configuration by Moodle-Admins';

$string['role_legalguardian'] = 'Legal guardian';
$string['role_legalguardian:description'] = 'Select the role a legal guardian will have within a course.';
$string['role_student'] = 'Student';
$string['role_student:description'] = 'Select the role a student will have within a course.';

$string['separate'] = 'Separation';
$string['separate:description'] = 'If you want to have private communication channels to the legal guardians of each of your students you can enable the groupmode. It is recommended to choose "separated groups" to ensure, that private communication will not be visible to others. What ever you choose here will be mandatory for the whole course!';
$string['separate:error'] = 'Could not set up privacy mode';
$string['separate:success'] = 'Successfully set privacy mode';

$string['step_enrol'] = 'Enrol users';
$string['step_enrol_legalguardians'] = 'Enrol legal guardians';
$string['step_remove_block'] = 'Remove this block';
$string['step_separate_groups'] = 'Enable separation';
$string['step_studentassign'] = 'Manage relations';
