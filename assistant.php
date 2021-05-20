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
 * @copyright  2021 Zentrum für Lernmanagement (www.lernmanagement.at)
 * @author     Robert Schrenk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/edureportbook/lib.php');

$courseid = required_param('courseid', PARAM_INT);
$ctx = context_course::instance($courseid);
require_login($courseid);

$courseurl = new \moodle_url('/course/view.php', array('id' => $courseid));
$PAGE->set_url('/blocks/edureportbook/assistant.php', array('courseid' => $courseid));

$stages = [
    'progress_about', 'progress_users',
    'progress_privacy', 'progress_finish'
];
$stage = optional_param('stage', 0, PARAM_INT);
$stagenamed = $stages[$stage];
if (empty($stagenamed)) {
    throw new \moodle_exception('assistant_invalid_stage', 'block_edureportbook', $PAGE->url);
}

$role_legalguardian = get_config('block_edureportbook', 'role_legalguardian');
$role_student = get_config('block_edureportbook', 'role_student');
if (empty($role_legalguardian) && empty($role_student)) {
    throw new \moodle_exception('role_missing_configuration', 'block_edureportbook', $courseurl);
}


$PAGE->set_url('/blocks/edureportbook/assistant.php', array('courseid' => $courseid, 'stage' => $stage));

$PAGE->set_context($ctx);
$PAGE->set_title(get_string('pluginname', 'block_edureportbook'));
$PAGE->set_heading(get_string('pluginname', 'block_edureportbook'));
$PAGE->set_pagelayout('incourse');
$PAGE->requires->css('/blocks/edureportbook/style/ui.css');

$PAGE->navbar->add(get_string('pluginname', 'block_edureportbook'), $PAGE->url);

$course = \get_course($courseid);
$grouping = \block_edureportbook\lib::get_grouping();

$resolve = optional_param('resolve', '', PARAM_ALPHANUM);
switch ($resolve) {
    case 'coursegroupmode':
        $course->groupmode = 0;
        $course->groupmodeforce = 0;
        $DB->set_field('course', 'groupmode', $course->groupmode, array('id' => $courseid));
        $DB->set_field('course', 'groupmodeforce', $course->groupmodeforce, array('id' => $courseid));
        \rebuild_course_cache($courseid);
        \redirect($PAGE->url);
    break;
    case 'forcesubscribe':
        $forumid = required_param('forumid', PARAM_INT);
        $forum = $DB->get_record('forum', array('id' => $forumid));
        if ($forum->course != $courseid) {
            throw new moodle_exception('invalid_forum', 'block_edureportbook', $CFG->wwwroot);
        }
        $DB->set_field('forum', 'forcesubscribe', 1, array('id' => $forum->id));
        \rebuild_course_cache($courseid);
        \redirect($PAGE->url);
    break;
    case 'generalforum':
    case 'groupforum':
        require_once($CFG->dirroot . '/course/lib.php');
        $item = (object) array(
            'completion' => 0,
            'course' => $courseid,
            'groupingid' => ($resolve == 'groupforum') ? $grouping->id : 0,
            'groupmode' => ($resolve == 'groupforum') ? 1 : 0,
            'forcesubscribe' => 1,
            'introeditor' => [
                'text' => '<p>' . get_string('condition_' . $resolve . '_description', 'block_edureportbook') . '</p>',
                'format' => 1, // HTML
                'itemid' => 0, // file are
            ],
            'modulename' => 'forum',
            'name' => get_string('condition_' . $resolve . '_defaultname', 'block_edureportbook'),
            'section' => 0,
            'showdescription' => 1,
            'timemodified' => time(),
            'timecreated' => time(),
            'visible' => 1,
            'visibleoncoursepage' => 1,
        );
        $mod = \create_module($item);
        \rebuild_course_cache($courseid);
        \redirect($PAGE->url);
    break;
    case 'removeassistant':
        \block_edureportbook\lib::remove_block();
        \rebuild_course_cache($courseid);
        \redirect($PAGE->url);
    break;
}


echo $OUTPUT->header();

$params = [
    $stagenamed => 1,
];
for ($a = 0; $a < count($stages); $a++) {
    $params['link_' . $stages[$a]] = new \moodle_url('/blocks/edureportbook/assistant.php', array('courseid' => $courseid, 'stage' => $a));
}

echo $OUTPUT->render_from_template('block_edureportbook/assistant_progress', $params);

switch ($stagenamed) {
    case 'progress_about':
        echo get_string('assistant_descr_about', 'block_edureportbook');
    break;
    case 'progress_users':
        $xparams = [
            'courseid' => $courseid,
            'role_legalguardian' => $role_legalguardian,
            'role_student' => $role_student,
            'rolename_legalguardian' => '',
            'rolename_student' => '',
            'wwwroot' => $CFG->wwwroot,
        ];

        $role = $DB->get_record('role', array('id' => $xparams['role_student']));
        $xparams['rolename_student'] = (!empty($role->name)) ? $role->name : $role->shortname;

        $role = $DB->get_record('role', array('id' => $xparams['role_legalguardian']));
        $xparams['rolename_legalguardian'] = (!empty($role->name)) ? $role->name : $role->shortname;

        $xparams['students'] = \block_edureportbook\lib::get_students();
        $xparams['legalguardians'] = \block_edureportbook\lib::get_legalguardians();

        $xparams['firststudentid'] = $xparams['students'][0]->id;

        echo $OUTPUT->render_from_template('block_edureportbook/assistant_users', $xparams);
    break;
    case 'progress_privacy':
        $xparams = [
            'courseid' => $courseid,
            'condition_coursegroupmode' => ($course->groupmode == 0 && $course->groupmodeforce == 0) ? 1 : 0,
            'groupmode' => $course->groupmode,
            'groupmodeforce' => $course->groupmodeforce,
            'stage' => $stage,
            'wwwroot' => $CFG->wwwroot,
        ];

        $sql = "SELECT f.id,f.name,f.forcesubscribe,cm.id cmid,cm.groupmode,cm.groupingid
                    FROM {forum} f, {course_modules} cm
                    WHERE cm.course = :courseid
                        AND f.id = cm.instance
                        AND f.type = 'general'
                    ORDER BY name ASC";
        $forums = array_values($DB->get_records_sql($sql, array('courseid' => $courseid)));
        foreach ($forums as $forum) {
            $forum->forcedsubscription = ($forum->forcesubscribe == 1) ? 1 : 0;
            if ($forum->groupmode == 0) {
                $xparams['condition_generalforum'] = 1;
                $xparams['condition_generalforums'][] = $forum;
            }
            if ($forum->groupmode == 1 && $forum->groupingid == $grouping->id) {
                $xparams['condition_groupforum'] = 1;
                $xparams['condition_groupforums'][] = $forum;
            }
        }

        echo $OUTPUT->render_from_template('block_edureportbook/assistant_privacy', $xparams);
    break;
    case 'progress_finish':
        $blockinstance = $DB->get_record('block_instances', array('blockname' => 'edureportbook', 'parentcontextid' => $ctx->id));
        $xparams = [
            'blockisactive' => !empty($blockinstance->id) ? 1 : 0,
            'courseid' => $courseid,
            'stage' => $stage,
            'wwwroot' => $CFG->wwwroot,
        ];
        echo $OUTPUT->render_from_template('block_edureportbook/assistant_finish', $xparams);
    break;
}

$proceedlabel = get_string('proceed_to_next_step', 'block_edureportbook');
$backlabel = get_string('proceed_to_last_step', 'block_edureportbook');

switch ($stagenamed) {
    case 'progress_about':
        $backurl = '';
        $proceedurl = $params['link_progress_users'];
    break;
    case 'progress_users':
        $backurl = $params['link_progress_about'];
        $proceedurl = $params['link_progress_privacy'];
    break;
    case 'progress_privacy':
        $backurl = $params['link_progress_users'];
        $proceedurl = $params['link_progress_finish'];
    break;
    case 'progress_finish':
        $backurl = '';
        $proceedurl = new \moodle_url('/course/view.php', array('id' => $courseid));
        $proceedlabel = get_string('proceed_to_course', 'block_edureportbook');
    break;
}

$params = [
    'backlabel' => $backlabel,
    'backurl' => $backurl,
    'proceedlabel' => $proceedlabel,
    'proceedurl' => $proceedurl,
];

echo $OUTPUT->render_from_template('block_edureportbook/assistant_progress_step', $params);


echo $OUTPUT->footer();
