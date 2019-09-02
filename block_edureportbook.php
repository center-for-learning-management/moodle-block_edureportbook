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
require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');

class block_edureportbook extends block_base {
    // NON STATIC AREA
    public function init() {
        $this->title = get_string('pluginname', 'block_edureportbook');
    }
    public function get_content() {
        if ($this->content !== null) {
          return $this->content;
        }
        global $CFG, $COURSE, $DB, $PAGE, $USER;

        $this->content = (object) array(
            'text' => '',
            'footer' => ''
        );
        $options = array();

        if (block_edureportbook_lib::is_legalguardian()) {
            return;
        }
        if (block_edureportbook_lib::is_student()) {
            return;
        }
        if (block_edureportbook_lib::is_teacher()) {
            $counter = 1;
            $role_legalguardian = get_config('block_edureportbook', 'role_legalguardian');
            $role_student = get_config('block_edureportbook', 'role_student');
            if (block_edureportbook_lib::can_enrol_eduvidual()) {
                if (strpos($_SERVER["SCRIPT_FILENAME"], '/user/index.php') > 0) {
                    $singleid = 'enrolusersbutton-1';
                } else {
                    // We only insert this to the footer if we are not already on the enrolments-page.
                    // Otherwise the button would exist twice.
                    require_once($CFG->dirroot . '/enrol/locallib.php');
                    require_once($CFG->dirroot . '/enrol/manual/lib.php');
                    $manager = new course_enrolment_manager($PAGE, $COURSE);
                    $emp = new enrol_manual_plugin();
                    $enrolbutton = $emp->get_manual_enrol_button($manager);
                    $enrolrenderer = $PAGE->get_renderer('block_edureportbook');
                    $strbutton = $enrolrenderer->render($enrolbutton);
                    $singleid = substr($strbutton, 0, strpos($strbutton, '/'));
                    $strbutton = substr($strbutton, strpos($strbutton, '/') + 1);
                    $this->content->footer[] = $strbutton;
                }

                $enrolinstance = block_edureportbook_lib::get_manual_enrol_instance();
                $options[] = array(
                    "id" => 'block_edureportbook_stepbtn-1',
                    "title" => $counter++ . '.) ' . get_string('step_enrol', 'block_edureportbook'),
                    "href" => $CFG->wwwroot . '/enrol/manual/manage.php?enrolid=' . $enrolinstance->id .  '&id=' . $COURSE->id,
                    "onclick" => "$('#" . $singleid . " input:first-child').click(); require(['block_edureportbook/main'], function(MAIN){ MAIN.enrolmentrole(" . $role_student . "); }); return false;",
                    "icon" => '/pix/i/assignroles.svg',
                );
                $options[] = array(
                    "id" => 'block_edureportbook_stepbtn-2',
                    "title" => $counter++ . '.) ' . get_string('step_enrol_legalguardians', 'block_edureportbook'),
                    "href" => $CFG->wwwroot . '/enrol/manual/manage.php?enrolid=' . $enrolinstance->id .  '&id=' . $COURSE->id,
                    "onclick" => "$('#" . $singleid . " input:first-child').click(); require(['block_edureportbook/main'], function(MAIN){ MAIN.enrolmentrole(" . $role_legalguardian . "); }); return false;",
                    "icon" => '/pix/i/checkpermissions.svg',
                );
            } else {
                $options[] = array(
                    "id" => 'block_edureportbook_stepbtn-1',
                    "title" => $counter++ . '.) ' . get_string('step_enrol', 'block_edureportbook'),
                    "href" => '#',
                    //"onclick" => 'r',
                    "icon" => '/pix/i/assignroles.svg',
                );
                $options[] = array(
                    "id" => 'block_edureportbook_stepbtn-2',
                    "title" => $counter++ . '.) ' . get_string('step_enrol_legalguardians', 'block_edureportbook'),
                    "href" => '#',
                    //"onclick" => 'r',
                    "icon" => '/pix/i/checkpermissions.svg',
                );
            }

            $options[] = array(
                "id" => 'block_edureportbook_stepbtn-3',
                "title" => ($counter++) . '.) ' . get_string('step_studentassign', 'block_edureportbook'),
                "href" => $CFG->wwwroot . '/blocks/edureportbook/pages/assignstudents.php?id=' . $COURSE->id,
                "onclick" => "require(['block_edureportbook/main'], function(MAIN){ MAIN.participantsform(" . $COURSE->id . "); }); return false;",
                "icon" => '/pix/i/users.svg',
            );
            $options[] = array(
                "id" => 'block_edureportbook_stepbtn-4',
                "title" => ($counter++) . '.) ' . get_string('step_separate_groups', 'block_edureportbook'),
                "href" => $CFG->wwwroot . '/blocks/edureportbook/pages/separate.php?id=' . $COURSE->id,
                "onclick" => "require(['block_edureportbook/main'], function(MAIN){ MAIN.separateform(" . $COURSE->id . "); }); return false;",
                "icon" => '/pix/i/permissions.svg',
            );
            $options[] = array(
                "id" => 'block_edureportbook_stepbtn-5',
                "title" => ($counter++) . '.) ' . get_string('step_remove_block', 'block_edureportbook'),
                "href" => $CFG->wwwroot . '/blocks/edureportbook/pages/removeblock.php?id=' . $COURSE->id,
                "onclick" => "require(['block_edureportbook/main'], function(MAIN){ MAIN.removeblock(" . $COURSE->id . "); }); return false;",
                "icon" => '/pix/i/completion-auto-enabled.svg',
            );
        }

        foreach($options AS $option) {
            $tx = $option["title"];
            if (!empty($option["icon"])) $tx = "<img src='" . $CFG->wwwroot . '/' . $option["icon"] . "' class='icon'>" . $tx;
            if (!empty($option["href"])) $tx = "
                <a href='" . $option["href"] . "' " . ((!empty($option["onclick"])) ? " onclick=\"" . $option["onclick"] . "\"" : "") . "
                   " . ((!empty($option["target"])) ? " target=\"" . $option["target"] . "\"" : "") . "'>" . $tx . "</a>";
            else  $tx = "<a>" . $tx . "</a>";
            $this->content->text .= $tx . "<br />";
        }

        $this->content->footer = implode('', $this->content->footer);
        return $this->content;
    }
    public function hide_header() {
        return false;
    }
    public function has_config() {
        return true;
    }
    public function instance_allow_multiple() {
        return false;
    }
}
