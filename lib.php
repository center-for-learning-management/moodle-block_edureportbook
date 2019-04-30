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

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/blocks/moodleblock.class.php');

class block_edureportbook_lib {
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
        $context = context_course::instance($COURSE->id);
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
     * Checks if enrolment
     * @return true if the version.php of enrol/eduvidual exists.
    **/
    public static function can_enrol_eduvidual() {
        global $CFG;
        return file_exists($CFG->dirroot . '/enrol/eduvidual/version.php');
    }

    /**
     * Checks if there is already a grouping to collect groups created by this plugin. If not creates one.
     * @return grouping.
    **/
    public static function get_grouping() {
        global $COURSE;
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
     * Ensures that there is an instance for manual enrolment.
     * @return Instance for manual enrolment in that course.
     */
    public static function get_manual_enrol_instance() {
        global $CFG, $COURSE, $PAGE;
        require_once($CFG->dirroot . '/enrol/locallib.php');
        $manager = new course_enrolment_manager($PAGE, $COURSE);
        $instances = $manager->get_enrolment_instances(true);
        foreach($instances AS $instance) {
            if ($instance->enrol == 'manual') return $instance;
        }
        // If we are here than there is no manual enrolinstance.
        require_once($CFG->dirroot . '/enrol/manual/lib.php');
        $emp = new enrol_manual_plugin();
        return $emp->add_default_instance($COURSE);
    }
    /**
     * Get the group for a particular student.
     * If the group does not exist it will be created.
     * This function also checks for the grouping.
     *
     * @return group object.
     */
    public static function get_student_group($userid) {
        global $CFG, $COURSE, $DB;
        require_once($CFG->dirroot . '/group/lib.php');
        $group = groups_get_group_by_idnumber($COURSE->id, 'edureportbook-' . $userid);
        $user = !empty($userid) ? $DB->get_record('user', array('id' => $userid)) : (object) array('id' => $userid, 'GROUPNAME' => get_string('default_group_all', 'block_edureportbook'));
        if (empty($user->GROUPNAME)) {
            $user->GROUPNAME = $user->lastname . ' ' . $user->firstname;
        }
        if (empty($group->id)) {
            $group = (object) array('courseid' => $COURSE->id, 'idnumber' => 'edureportbook-' . $user->id, 'name' => $user->GROUPNAME, 'timecreated' => time(), 'timemodified' => time());
            $group->id = groups_create_group($group, false);
        } else {
            // Ensure group has the correct name.
            $group->name = $user->GROUPNAME;
            groups_update_group($group, false);
        }
        // Ensure the student self is member of the group.
        if (!empty($user->id)) {
            groups_add_member($group->id, $user->id);
        }
        // Ensure group is in correct grouping.
        $grouping = self::get_grouping();
        groups_assign_grouping($grouping->id, $group->id);
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
        global $COURSE, $DB;
        $context = context_course::instance($COURSE->id);
        $enrolled = $DB->get_records('role_assignments', array('contextid' => $context->id, 'roleid' => $roleid));
        $users = array();
        foreach($enrolled AS $enrol) {
            $users[] = $DB->get_record('user', array('id' => $enrol->userid));
        }
        return $users;
    }
    /**
     * Creates the participants-form that connects
     * students to their legal guardians.
     * @return form Moodle-Form Object.
     */
    public static function handle_participants_form() {
        global $CFG, $COURSE;
        if (!self::is_teacher()) return '';
        require_once($CFG->dirroot . '/blocks/edureportbook/classes/participants_form.php');
        $mform = new block_edureportbook_participants_form();
        if ($data = $mform->get_data()) {
            self::handle_participants_store($data);
        }
        $legalguardians = self::get_legalguardians();
        $legalguardianids = array_keys($legalguardians);
        $students = self::get_students();
        $studentids = array_keys($students);
        $studentgroups = self::get_student_groups();
        $formdata = array('id' => $COURSE->id);
        foreach($studentgroups AS $studentgroup) {
            $formdata['group-' . $studentgroup->id] = array();
            $members = groups_get_members($studentgroup->id);
            if (!empty($members)) {
                foreach($members AS $member) {
                    $formdata['group-' . $studentgroup->id][] = $member->id;
                }
            }
        }

        $mform->set_data($formdata);
        return $mform;
    }
    /**
     * Handles the input from the participants-form that connects
     * students to their legal guardians.
     * @param data array containing all the data.
     * @return true if everything was successful.
     */
    public static function handle_participants_store($data) {
        if (!self::is_teacher()) return false;
        $legalguardians = self::get_legalguardians();
        $legalguardianids = array();
        foreach($legalguardians AS $lg) { $legalguardianids[] = $lg->id; }
        $students = self::get_students();
        $studentids = array();
        foreach($students AS $st) { $studentids[] = $st->id; }
        $studentgroups = self::get_student_groups();
        foreach($studentgroups AS $studentgroup) {
            $currentusers = groups_get_members($studentgroup->id);
            $wantedusers = !empty($data->{'group-' . $studentgroup->id}) ? $data->{'group-' . $studentgroup->id} : array();
            foreach($currentusers AS $currentuser) {
                if (!in_array($currentuser->id, $studentids) && !in_array($currentuser->id, $wantedusers)) {
                    groups_remove_member($studentgroup->id, $currentuser->id);
                }
            }
            foreach($wantedusers AS $addmember) {
                if (!empty($addmember)) {
                    groups_add_member($studentgroup->id, $addmember);
                }
            }
            $groupforstudent = substr($studentgroup->idnumber, strpos($studentgroup->idnumber, '-') + 1);
            if ($groupforstudent == '0') {
                // Enrol all students.
                foreach($students AS $student) {
                    if (!empty($student->id)) {
                        groups_add_member($studentgroup->id, $student->id);
                    }
                }
            } else {
                groups_add_member($studentgroup->id, $groupforstudent);
            }
        }
        return true;
    }
    /**
     * Create the form for group separation.
     * @return Moodle-Form object.
     */
    public static function handle_separate_form() {
        global $CFG, $COURSE;
        require_once($CFG->dirroot . '/blocks/edureportbook/classes/separate_form.php');
        $mform = new block_edureportbook_separate_form();
        if ($data = $mform->get_data()) {
            self::handle_separate_store($data);
        }
        $course = get_course($COURSE->id);
        $formdata = array('id' => $COURSE->id, 'groupmode' => $course->groupmode);
        $mform->set_data($formdata);
        return $mform;
    }
    /**
     * Handles the input from the separate-form.
     * Sets groupmode and groupmodeforce on the course,
     * as well as the defaultgroupingid.
     * @param data Data from the form.
     * @return true if everything was successful.
     */
    public static function handle_separate_store($data) {
        global $COURSE, $DB;
        if (!self::is_teacher()) return false;
        $course = $DB->get_record('course', array('id' => $COURSE->id));
        $course->groupmode = $data->groupmode;
        $course->groupmodeforce = 1;
        $grouping = self::get_grouping();
        $course->defaultgroupingid = $grouping->id;
        $DB->update_record('course', $course);
        rebuild_course_cache($course->id, true);
        return true;
    }
    /**
     * Checks if the current user has a specific role within the current course.
     * @param roleid to check for.
     * @return true if user has role in course.
     */
    private static function is_role($roleid) {
        global $COURSE, $DB, $USER;
        $context = context_course::instance($COURSE->id);
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
        $context = context_course::instance($COURSE->id);
        return has_capability('moodle/course:managegroups', $context);
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
        $context = context_course::instance($COURSE->id);
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
                    course_delete_section($COURSE, $section, true, true);
                    break;
                }
            } while(count($sections) > 0);

        }
        return 1;
    }

}
