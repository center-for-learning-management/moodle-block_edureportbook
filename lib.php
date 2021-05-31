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

namespace block_edureportbook;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

class lib {
    /**
     * Adds the block to the course
     *
     * @return the block_instance-id from db->insert_record.
     */
    public static function add_block() {
        global $COURSE, $DB;
        if (!self::is_teacher()) return 0;
        // We remove any existing blocks in the course context.
        self::remove_block(0);
        // Now we create a new instance.
        $context = \context_course::instance($COURSE->id);
        $params = array(
            'blockname' => 'edureportbook',
            'parentcontextid' => $context->id,
            'showinsubcontexts' => 1,
            'requiredbytheme' => 0,
            'pagetypepattern' => '*',
            'defaultregion' => 'side-post',
            'defaultweight' => 0,
            'timecreated' => time(),
            'timemodified' => time()
        );
        return $DB->insert_record('block_instances', $params);
    }
    /**
     * Checks if there is already a grouping to collect groups created by this plugin. If not creates one.
     * @return grouping.
    **/
    public static function get_grouping() {
        global $CFG, $COURSE;
        require_once($CFG->dirroot . '/group/lib.php');
        $grouping = groups_get_grouping_by_idnumber($COURSE->id, 'edureportbook');
        if (empty($grouping)) {
            $grouping = (object) array('courseid' => $COURSE->id, 'name' => get_string('pluginname', 'block_edureportbook'), 'idnumber' => 'edureportbook', 'timecreated' => time(), 'description' => '');
            $grouping->id = groups_create_grouping($grouping);
        }
        return $grouping;
    }
    /**
     * Return a list of all legal guardians in this course.
     */
    public static function get_legalguardians() {
        return self::get_users(get_config('block_edureportbook', 'role_legalguardian'));
    }
    /**
     * Get the group for a particular student.
     * If the group does not exist it will be created.
     * This function also checks for the grouping.
     *
     * @return group object.
     */
    public static function get_student_group($userid, $courseid = 0) {
        global $CFG, $COURSE, $DB;
        if (empty($courseid)) {
            $courseid = $COURSE->id;
        }
        require_once($CFG->dirroot . '/group/lib.php');
        $group = \groups_get_group_by_idnumber($courseid, 'edureportbook-' . $userid);
        $user = !empty($userid) ? $DB->get_record('user', array('id' => $userid)) : (object) array('id' => $userid, 'GROUPNAME' => get_string('default_group_all', 'block_edureportbook'));
        if (empty($user->GROUPNAME)) {
            $user->GROUPNAME = $user->lastname . ' ' . $user->firstname;
        }
        if (empty($group->id)) {
            $group = (object) array('courseid' => $courseid, 'idnumber' => 'edureportbook-' . $user->id, 'name' => $user->GROUPNAME, 'timecreated' => time(), 'timemodified' => time());
            $group->id = \groups_create_group($group, false);
        } else {
            // Ensure group has the correct name.
            $group->name = $user->GROUPNAME;
            \groups_update_group($group, false);
        }
        // Ensure the student self is member of the group.
        if (!empty($user->id)) {
            \groups_add_member($group->id, $user->id);
        }
        // Ensure group is in correct grouping.
        $grouping = self::get_grouping();
        \groups_assign_grouping($grouping->id, $group->id);
        return $group;
    }
    /**
     * Get a list of all student-groups.
     *
     * @return array containing all group-objects.
     */
    public static function get_student_groups() {
        global $COURSE;
        $students = self::get_students();
        $groups = array();
        foreach($students AS $student) {
            $groups[] = self::get_student_group($student->id);
        }
        // Create a default_group_all
        $groups[] = self::get_student_group(0);
        return $groups;
    }
    /**
     * Get a list of all students in this course.
     */
    public static function get_students() {
        return self::get_users(get_config('block_edureportbook', 'role_student'));
    }
    /**
     * Returns all users with a specific roleid.
     * @param roleid to search for
     * @return array containing user-objects.
     */
    private static function get_users($roleid) {
        global $COURSE, $DB, $OUTPUT;
        $context = \context_course::instance($COURSE->id);
        $enrolled = $DB->get_records('role_assignments', array('contextid' => $context->id, 'roleid' => $roleid));
        $users = array();
        foreach($enrolled AS $enrol) {
            $user = $DB->get_record('user', array('id' => $enrol->userid));
            $user->picture = $OUTPUT->user_picture($user, array('size' => 30));
            $tmp = explode('img src="', $user->picture);
            if (count($tmp) > 0) {
                $tmp = explode('"', $tmp[1]);
                $user->picturenolink = $tmp[0];
            }

            $users[] = $user;
        }
        return $users;
    }
    /**
     * Checks if the current user has a specific role within the current course.
     * @param roleid to check for.
     * @return true if user has role in course.
     */
    private static function is_role($roleid) {
        global $COURSE, $DB, $USER;
        $context = \context_course::instance($COURSE->id);
        $enrolled = $DB->get_records('role_assignments', array('contextid' => $context->id, 'userid' => $USER->id, 'roleid' => $roleid));
        return !empty($enrolled->id);
    }
    /**
     * Checks if the current user is a legal guardian in the current course.
     * @return true if it is so.
     */
    public static function is_legalguardian() {
        return self::is_role(get_config('block_edureportbook', 'role_legalguardian'));
    }
    /**
     * Checks if the current user is a student in the current course.
     * @return true if it is so.
     */
    public static function is_student() {
        return self::is_role(get_config('block_edureportbook', 'role_student'));
    }
    /**
     * Checks if the current user is a teacher in the current course.
     * This is checked by the capability 'moodle/course:managegroups'.
     * @return true if it is so.
     */
    public static function is_teacher() {
        global $COURSE, $USER;
        $context = \context_course::instance($COURSE->id);
        return \has_capability('moodle/course:managegroups', $context);
    }
    /**
     * Removes the block from the course and optionally removes sections that
     * are used as tutorial. Such sections need to include the HTML-Comment
     * <!-- block_edureportbook delete me --> in their summary.
     *
     * @param checksections if 0 do not remove sections, default value is 1.
     * @return true if successful.
     */
    public static function remove_block($checksections = 1) {
        global $CFG, $COURSE, $DB;
        if (!self::is_teacher()) return 0;
        $context = \context_course::instance($COURSE->id);
        $params = array(
            'blockname' => 'edureportbook',
            'parentcontextid' => $context->id
        );
        $DB->delete_records('block_instances', $params);

        /*
         * We now remove sections that contain our magic pattern.
         */
        if ($checksections) {
            // course_delete_sections rebuilds the course cache, therefore
            // the numbering of sections changes.
            // Thus we always delete one section and make the query again.
            require_once($CFG->dirroot . '/course/lib.php');
            $sql = "SELECT *
                        FROM {course_sections}
                        WHERE course=?
                            AND summary LIKE (?)";
            $params = array($COURSE->id, '%<!-- block_edureportbook delete me -->%');
            do {
                $sections = $DB->get_records_sql($sql, $params);
                foreach ($sections AS $section) {
                    \course_delete_section($COURSE, $section, true, true);
                    break;
                }
            } while(count($sections) > 0);

        }
        return 1;
    }

}
