<?php

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
 * External Web Service Template
 *
 * @package    localtimelinemanager
 * @copyright  2016 SABER Tecnologias Educacionais e Sociais
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");

class local_timelinemanager_external extends external_api {

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_timeline_parameters() {        
        return new external_function_parameters(
                array()
        );
    }

    /**
     * Returns timeline object
     * @return JSON timeline object
     */
    public static function get_timeline() {
        global $USER, $DB, $CFG;
        
        //Context validation
        //OPTIONAL but in most web service it should present
        $context = get_context_instance(CONTEXT_USER, $USER->id);
        self::validate_context($context);        
        
        $result = $DB->get_records_sql("SELECT * from ".$CFG->prefix."timeline_manager where userid=$USER->id");

        return json_encode($result);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_timeline_returns() {
        return new external_value(PARAM_TEXT, 'The object containing timeline events for the given user.');
    }

}
