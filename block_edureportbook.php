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
            'footer' => array(),
        );
        $options = array();

        if (\block_edureportbook\lib::is_legalguardian()) {
            return;
        }
        if (\block_edureportbook\lib::is_student()) {
            return;
        }
        if (\block_edureportbook\lib::is_teacher()) {
            $url = new \moodle_url('/blocks/edureportbook/assistant.php', array('courseid' => $COURSE->id));
            $btnlabel = get_string('edureportbook:addinstance', 'block_edureportbook');
            $btn = [
                "<a href=\"$url\" class=\"btn btn-primary btn-block\">",
                "<i class=\"fa fa-wrench\"></i>",
                $btnlabel,
                "</a>",
            ];
            $this->content->text .= implode(" ",$btn);
        }

        if (is_array($this->content->footer)) {
            $this->content->footer = implode('', $this->content->footer);
        }
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
