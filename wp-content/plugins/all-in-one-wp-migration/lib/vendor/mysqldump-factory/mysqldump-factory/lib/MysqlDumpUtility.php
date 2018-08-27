<?php

/**
 * Copyright (C) 2014 ServMask Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * ███████╗███████╗██████╗ ██╗   ██╗███╗   ███╗ █████╗ ███████╗██╗  ██╗
 * ██╔════╝██╔════╝██╔══██╗██║   ██║████╗ ████║██╔══██╗██╔════╝██║ ██╔╝
 * ███████╗█████╗  ██████╔╝██║   ██║██╔████╔██║███████║███████╗█████╔╝
 * ╚════██║██╔══╝  ██╔══██╗╚██╗ ██╔╝██║╚██╔╝██║██╔══██║╚════██║██╔═██╗
 * ███████║███████╗██║  ██║ ╚████╔╝ ██║ ╚═╝ ██║██║  ██║███████║██║  ██╗
 * ╚══════╝╚══════╝╚═╝  ╚═╝  ╚═══╝  ╚═╝     ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝
 */

class MysqlDumpUtility
{
	/**
	 * Replace all occurrences of the search string with the replacement string.
	 * This function is case-sensitive.
	 *
	 * @param  array  $from List of string we're looking to replace.
	 * @param  array  $to   What we want it to be replaced with.
	 * @param  string $data Data to replace.
	 * @return mixed        The original string with all elements replaced as needed.
	 */
	public static function replaceValues($from = array(), $to = array(), $data = '')
	{
		return str_replace($from, $to, $data);
	}

	/**
	 * Take a serialized array and unserialize it replacing elements as needed and
	 * unserializing any subordinate arrays and performing the replace on those too.
	 * This function is case-sensitive.
	 *
	 * @param  array $from       List of string we're looking to replace.
	 * @param  array $to         What we want it to be replaced with.
	 * @param  mixed $data       Used to pass any subordinate arrays back to in.
	 * @param  bool  $serialized Does the array passed via $data need serializing.
	 * @return mixed             The original array with all elements replaced as needed.
	 */
	public static function replaceSerializedValues($from = array(), $to = array(), $data = '', $serialized = false)
	{
		// Some unserialized data cannot be re-serialized eg. SimpleXMLElements
		try {

			if (is_string($data) && ($unserialized = @unserialize($data)) !== false) {
				$data = self::replaceSerializedValues($from, $to, $unserialized, true);
			} else if (is_array($data)) {
				$tmp = array();
				foreach ($data as $key => $value) {
					$tmp[$key] = self::replaceSerializedValues($from, $to, $value, false);
				}

				$data = $tmp;
				unset($tmp);
			} elseif (is_object($data)) {
				$tmp = $data;
				$props = get_object_vars($data);
				foreach ($props as $key => $value) {
					$tmp->$key = self::replaceSerializedValues($from, $to, $value, false);
				}

				$data = $tmp;
				unset($tmp);
			} else {
				if (is_string($data)) {
					$data = str_replace($from, $to, $data);
				}
			}

			if ($serialized) {
				return serialize($data);
			}

		} catch (Exception $e) {
			// pass
		}

		return $data;
	}

	/**
	 * Unescape MySQL special characters
	 *
	 * @param  string $data Data to replace.
	 * @return string
	 */
	public static function unescapeMysql($data) {
		return str_replace(
			array('\\\\', '\\0', "\\n", "\\r", '\Z', "\'", '\"'),
			array('\\', '\0', "\n", "\r", "\x1a", "'", '"'),
			$data
		);
	}
	/**
	 * Unescape quote characters
	 *
	 * @param  string $data Data to replace.
	 * @return string
	 */
	public static function unescapeQuotes($data) {
		return str_replace('\"', '"', $data);
	}
}
