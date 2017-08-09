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
 * This file contains the functions related to the timeline manager plugin.
 * 
 *
 * @package   local_timelinemanager
 * @copyright 2016 SABER Tecnologias Educacionais e Sociais
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Persists information to be shown in timeline 
 *
 */
function insert_in_timeline($event) {
    global $DB;    

    $event_name = get_class($event);
    $event_data = $event->get_data();
    if (isset($event_name)) {
        $event_name = explode('\\', $event_name);
        $event_name = array_pop($event_name);

        $timelineevents = $DB->get_records_select('timeline_events_types', "eventname = '$event_name'", null, 'eventname, triggerfunction');
        
        if (count($timelineevents) > 0) {
            $triggers = array();
            foreach ($timelineevents as $te) {
                if (isset($te->triggerfunction)) {
                    array_push($triggers, $te->triggerfunction);
                }
            }                

            //If the event doesn't have trigger information, it won't be needed to check it - it means that this event triggering is unique 
            $showintimeline = (count($triggers) == 0)? true : in_array($event_data['other']['triggeredfrom'], $triggers);
        }
        else
            $showintimeline = false;                   

        if ($showintimeline) { //Inserting triggered event in table
            $newtimelineentry = array();
            $newtimelineentry['timecreated'] = $event->__get('timecreated');
            $newtimelineentry['objectid'] = $event->__get('objectid');
            $newtimelineentry['objecttable'] = $event->__get('objecttable');
            $newtimelineentry['courseid'] = $event->__get('courseid');
            $newtimelineentry['userid'] = $event->__get('userid');
            $newtimelineentry['relateduserid'] = $event->__get('relateduserid');

            $other = array_map('escape_content',$event->__get('other'));  
            $newtimelineentry['additionalinfo'] = json_encode($other);
                          
            $DB->insert_record('timeline_manager', (object) $newtimelineentry);
        }
    }         
}

function escape_content($content) {            
    return (is_string($content))? htmlentities(utf8_encode($content)) : $content;          
}   