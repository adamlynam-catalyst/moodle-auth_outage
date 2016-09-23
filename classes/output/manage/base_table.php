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

namespace auth_outage\output\manage;

use auth_outage\local\outage;
use flexible_table;
use html_writer;
use moodle_url;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/tablelib.php');

/**
 * Manage outages table base.
 *
 * @package    auth_outage
 * @author     Daniel Thee Roperto <danielroperto@catalyst-au.net>
 * @copyright  2016 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class base_table extends flexible_table {
    private static $autoid = 0;

    /**
     * Constructor
     * @param string|null $id to be used by the table, autogenerated if null.
     */
    public function __construct($id = null) {
        global $PAGE;

        $id = (is_null($id) ? self::$autoid++ : $id);
        parent::__construct('auth_outage_manage_'.$id);

        $this->define_baseurl($PAGE->url);
        $this->set_attribute('class', 'generaltable admintable');
    }

    /**
     * Create the action buttons HTML code for a specific outage.
     * @param outage $outage The outage to generate the buttons.
     * @param bool $editdelete If it should display the edit and delete button.
     * @return string The HTML code of the action buttons.
     */
    protected function set_data_buttons(outage $outage, $editdelete) {
        global $OUTPUT;
        $buttons = '';

        // View button.
        $buttons .= html_writer::link(
            new moodle_url('/auth/outage/info.php', ['id' => $outage->id]),
            html_writer::empty_tag('img', [
                'src' => $OUTPUT->pix_url('t/preview'),
                'alt' => get_string('view'),
                'class' => 'iconsmall',

            ]),
            [
                'title' => get_string('view'),
                'target' => '_blank',
            ]
        );

        // Edit button if required.
        if ($editdelete) {
            $buttons .= html_writer::link(
                new moodle_url('/auth/outage/edit.php', ['id' => $outage->id]),
                html_writer::empty_tag('img', [
                    'src' => $OUTPUT->pix_url('t/edit'),
                    'alt' => get_string('edit'),
                    'class' => 'iconsmall',
                ]),
                ['title' => get_string('edit')]
            );
        }

        // Clone button.
        $buttons .= html_writer::link(
            new moodle_url('/auth/outage/clone.php', ['id' => $outage->id]),
            html_writer::empty_tag('img', [
                'src' => $OUTPUT->pix_url('t/copy'),
                'alt' => get_string('clone', 'auth_outage'),
                'class' => 'iconsmall',

            ]),
            ['title' => get_string('clone', 'auth_outage')]
        );

        // Finish button if ongoing.
        if ($outage->is_ongoing()) {
            $buttons .= html_writer::link(
                new moodle_url('/auth/outage/finish.php', ['id' => $outage->id]),
                html_writer::empty_tag('img', [
                    'src' => $OUTPUT->pix_url('t/check'),
                    'alt' => get_string('finish', 'auth_outage'),
                    'class' => 'iconsmall',
                ]),
                ['title' => get_string('finish', 'auth_outage')]
            );
        }

        // Delete button if required.
        if ($editdelete) {
            $buttons .= html_writer::link(
                new moodle_url('/auth/outage/delete.php', ['id' => $outage->id]),
                html_writer::empty_tag('img', [
                    'src' => $OUTPUT->pix_url('t/delete'),
                    'alt' => get_string('delete'),
                    'class' => 'iconsmall',
                ]),
                ['title' => get_string('delete')]
            );
        }

        return '<nobr>'.$buttons.'</nobr>';
    }
}
