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
 * @copyright  2018 Digital Education Society (http://www.dibig.at)
 * @author     Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . "/externallib.php");

class block_edureportbook_external extends external_api {
    /**
     * More or less the same as in pages/assignstudents.php
    **/
    public static function participantsform_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'courseid')
        ));
    }
    public static function participantsform($courseid) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::participantsform_parameters(), array('courseid' => $courseid));
        $courseid = $params['courseid'];
        if (!empty($courseid)) {
            require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
            require_login($courseid);
            $context = context_course::instance($courseid);
            $PAGE->set_context($context);
            $mform = block_edureportbook_lib::handle_participants_form();
            return $mform->render();
        } else {
            return '';
        }
    }
    public static function participantsform_returns() {
        return new external_value(PARAM_RAW, 'Returns the html code of the form.');
    }

    /**
     * Store the results of participantsform
    **/
    public static function participantsstore_parameters() {
        return new external_function_parameters(array(
            'data' => new external_value(PARAM_RAW, 'JSON encoded form data'),
        ));
    }
    public static function participantsstore($data) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::participantsstore_parameters(), array('data' => $data));
        $data = json_decode($params['data']);
        $courseid = $data->id;
        if (!empty($courseid)) {
            require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
            require_login($courseid);
            $context = context_course::instance($courseid);
            $PAGE->set_context($context);
            return block_edureportbook_lib::handle_participants_store($data) ? 1 : 0;
        } else {
            return 0;
        }
    }
    public static function participantsstore_returns() {
        return new external_value(PARAM_INT, 'Returns 1 if saving was successful, 0 if not.');
    }

    /**
     * Remove the block from course scope.
    **/
    public static function removeblock_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'The courseid'),
        ));
    }
    public static function removeblock($courseid) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::removeblock_parameters(), array('courseid' => $courseid));
        $courseid = $params['courseid'];
        if (!empty($courseid)) {
            require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
            require_login($courseid);
            $context = context_course::instance($courseid);
            $PAGE->set_context($context);
            return block_edureportbook_lib::remove_block();
        } else {
            return 0;
        }
    }
    public static function removeblock_returns() {
        return new external_value(PARAM_INT, 'Returns 1 if successful, otherwise 0.');
    }

    /**
     * More or less the same as in pages/separate.php
    **/
    public static function separateform_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'courseid')
        ));
    }
    public static function separateform($courseid) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::separateform_parameters(), array('courseid' => $courseid));
        $courseid = $params['courseid'];
        if (!empty($courseid)) {
            require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
            require_login($courseid);
            $context = context_course::instance($courseid);
            $PAGE->set_context($context);
            $mform = block_edureportbook_lib::handle_separate_form();
            return $mform->render();
        } else {
            return '';
        }
    }
    public static function separateform_returns() {
        return new external_value(PARAM_RAW, 'Returns the html code of the form.');
    }

    /**
     * Store the results of separateform
    **/
    public static function separatestore_parameters() {
        return new external_function_parameters(array(
            'data' => new external_value(PARAM_RAW, 'JSON encoded form data'),
        ));
    }
    public static function separatestore($data) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::separatestore_parameters(), array('data' => $data));
        $data = json_decode($params['data']);
        $courseid = $data->id;
        if (!empty($courseid)) {
            require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
            require_login($courseid);
            $context = context_course::instance($courseid);
            $PAGE->set_context($context);
            return block_edureportbook_lib::handle_separate_store($data) ? 1 : 0;
        } else {
            return 0;
        }
    }
    public static function separatestore_returns() {
        return new external_value(PARAM_INT, 'Returns 1 if saving was successful, 0 if not.');
    }
}
