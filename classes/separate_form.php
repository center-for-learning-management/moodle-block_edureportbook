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

class block_edureportbook_separate_form extends moodleform {
    function definition() {
        global $COURSE, $DB;

        $mform = $this->_form;
        $mform->addElement('header', 'header', get_string('separate', 'block_edureportbook'));
        $mform->addElement('html', '<p>' . get_string('separate:description', 'block_edureportbook') . '</p>');

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_INT);

        $options = array(
            '0' => get_string('groupsnone'),
            '1' => get_string('groupsseparate'),
            '2' => get_string('groupsvisible'),
        );
        $mform->addElement('select', 'groupmode', get_string('groupmode'), $options);
        $mform->setDefault('groupmode', 1);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        $errors = array();
        return $errors;
    }
}
