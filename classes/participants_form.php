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

require_once($CFG->libdir . "/formslib.php");
require_once($CFG->dirroot . "/blocks/edureportbook/lib.php");

class block_edureportbook_participants_form extends moodleform {
    function definition() {
        global $COURSE, $DB;

        $role_legalguardian = get_config('block_edureportbook', 'role_legalguardian');
        $role_student = get_config('block_edureportbook', 'role_student');

        $mform = $this->_form;
        $mform->addElement('header', 'header', get_string('assignstudents', 'block_edureportbook'));
        $mform->addElement('html', '<p>' . get_string('assignstudents:description', 'block_edureportbook') . '</p>');

        if (empty($role_legalguardian) || empty($role_student)) {
            $mform->addElement('text', 'missingconfig', get_string('missing_configuration', 'block_edureportbook'), array('class' => 'alert alert-warning'));
            return;
        }

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $_legalguardians = block_edureportbook_lib::get_legalguardians();
        $legalguardians = array('0' => get_string('none'));
        foreach($_legalguardians AS $legalguardian) {
            $legalguardians[$legalguardian->id] = $legalguardian->lastname . ' ' . $legalguardian->firstname;
        }

        $studentgroups = block_edureportbook_lib::get_student_groups();
        foreach($studentgroups AS $studentgroup) {
            if ($studentgroup->idnumber == 'edureportbook-0') {
                $mform->addElement('html', '<p>' . get_string('assignstudents:generalgroup:description', 'block_edureportbook') . '</p>');
            }
            $select = $mform->addElement('select', 'group-' . $studentgroup->id, $studentgroup->name, $legalguardians);
            $select->setMultiple(true);
        }
    }

    //Custom validation should be added here
    function validation($data, $files) {
        $errors = array();
        return $errors;
    }
}
