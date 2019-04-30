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
 * @package    block_edusupport
 * @copyright  2019 Digital Education Society (http://www.dibig.at)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
**/

include_once($CFG->dirroot . "/enrol/renderer.php");

class block_edureportbook_renderer extends core_enrol_renderer {
    /**
     * Renderers the enrol_user_button.
     *
     * @param enrol_user_button $button
     * @return string XHTML
     */
    protected function render_enrol_user_button(enrol_user_button $button) {
        //$button->formid = 'edureportbook-' . $button->formid;
        $attributes = array('type'     => 'submit',
                            'value'    => $button->label,
                            'disabled' => $button->disabled ? 'disabled' : null,
                            'title'    => $button->tooltip,
                            'style'    => 'display: none;',
                            'class'    => 'btn btn-secondary m-y-1');
        if ($button->actions) {
            $id = html_writer::random_id('single_button');
            $attributes['id'] = $id;
            foreach ($button->actions as $action) {
                $this->add_action_handler($action, $id);
            }
        }
        $button->initialise_js($this->page);
        // first the input element
        $output = html_writer::empty_tag('input', $attributes);
        // then hidden fields
        $params = $button->url->params();
        if ($button->method === 'post') {
            $params['sesskey'] = sesskey();
        }
        foreach ($params as $var => $val) {
            $output .= html_writer::empty_tag('input', array('type' => 'hidden', 'name' => $var, 'value' => $val));
        }
        // then div wrapper for xhtml strictness
        $output = html_writer::tag('div', $output);
        // now the form itself around it
        if ($button->method === 'get') {
            $url = $button->url->out_omit_querystring(true); // url without params, the anchor part allowed
        } else {
            $url = $button->url->out_omit_querystring();     // url without params, the anchor part not allowed
        }
        if ($url === '') {
            $url = '#'; // there has to be always some action
        }
        $attributes = array('method' => $button->method,
                            'action' => $url,
                            'id'     => $button->formid);
        $output = html_writer::tag('form', $output, $attributes);
        // and finally one more wrapper with class
        return $button->formid . '/' . html_writer::tag('div', $output, array('class' => $button->class));
    }
}
