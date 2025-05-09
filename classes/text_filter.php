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
 * @package     filter_searchmarks
 * @category    filter
 * @author      valery.fremaux <valery.fremaux@gmail.com>
 * @copyright   2010 onwards Valery Fremaux (http://www.mylearningfactory.com)
 */
namespace filter_searchmarks;

/**
 * Implementation of the Moodle filter API for the searchmarks filter.
 * @package    filter_searchmarks
 */
class text_filter extends \core_filters\text_filter {

    /**
     * @var array global configuration for this filter
     *
     * This might be eventually moved into parent class if we found it
     * useful for other filters, too.
     */
    protected static $globalconfig;

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {
        global $SESSION;

        if (!empty($SESSION->lastsearch)) {
            $parts = preg_split('/(<.*?>)/i', $text, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $out = '';
            foreach ($parts as $part) {
                $part = preg_replace('/^+/', '', $part); // Remove "required" sign.
                if (in_array($part, ['AND', 'OR', '||', '&&'])) {
                    continue;
                }
                if (substr($part, 0, 1) != '<') {
                    $out .= preg_replace('/('.$SESSION->lastsearch.')/i', '<mark>\\1</mark>', $part);
                } else {
                    $out .= $part;
                }
            }

            return $out;
        }

        return $text;
    }

}