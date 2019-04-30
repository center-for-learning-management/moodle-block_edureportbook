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

require_once('../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
require_once($CFG->dirroot . '/blocks/edureportbook/classes/separate_form.php');

$id = required_param('id', PARAM_INT); // This is the courseid
$course = get_course($id);
$context = context_course::instance($id);
// Must pass login
$PAGE->set_url('/blocks/edureportbook/pages/separate.php?id=' . $id);
require_login($course->id);
$PAGE->set_context($context);
$PAGE->set_title(get_string('step_separate_groups', 'block_edureportbook'));
$PAGE->set_heading(get_string('step_separate_groups', 'block_edureportbook'));
$PAGE->set_pagelayout('incourse');

echo $OUTPUT->header();

$mform = block_edureportbook_lib::handle_participants_form($id);
$mform->add_action_buttons();
$mform->display();

echo $OUTPUT->footer();
