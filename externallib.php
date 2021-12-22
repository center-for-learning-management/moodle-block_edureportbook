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
    public static function relation_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'courseid'),
            'studentid' => new external_value(PARAM_INT, 'studentid'),
            'parentid' => new external_value(PARAM_INT, 'parentid'),
            'to' => new external_value(PARAM_INT, 'to'),
        ));
    }
    public static function relation($courseid, $studentid, $parentid, $to) {
        global $CFG, $PAGE;
        $params = self::validate_parameters(self::relation_parameters(), array('courseid' => $courseid, 'studentid' => $studentid, 'parentid' => $parentid, 'to' => $to));

        $ctx = \context_course::instance($params['courseid']);
        require_capability('moodle/course:managegroups', $ctx);
        require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
        $group = \block_edureportbook\lib::get_student_group($params['studentid'], $params['courseid']);
        require_once($CFG->dirroot . '/group/lib.php');

        if ($to == 1) {
            \groups_add_member($group->id, $params['parentid']);
        } else {
            \groups_remove_member($group->id, $params['parentid']);
        }
        return $to;
    }
    public static function relation_returns() {
        return new external_value(PARAM_INT, 'Returns the new state of relation');
    }

    public static function relations_parameters() {
        return new external_function_parameters(array(
            'courseid' => new external_value(PARAM_INT, 'courseid'),
            'studentid' => new external_value(PARAM_INT, 'studentid'),
        ));
    }
    public static function relations($courseid, $studentid) {
        global $CFG;
        $params = self::validate_parameters(self::relations_parameters(), array('courseid' => $courseid, 'studentid' => $studentid));

        $ctx = \context_course::instance($params['courseid']);
        require_capability('moodle/course:managegroups', $ctx);
        require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');
        $group = \block_edureportbook\lib::get_student_group($params['studentid'], $params['courseid']);
        require_once($CFG->dirroot . '/group/lib.php');
        $members = \groups_get_members($group->id);
        $parentids = [];
        foreach ($members as $member) {
            $parentids[] = $member->id;
        }
        return implode(",", $parentids);
    }
    public static function relations_returns() {
        return new external_value(PARAM_RAW, 'All relations as JSON-string');
    }
}
