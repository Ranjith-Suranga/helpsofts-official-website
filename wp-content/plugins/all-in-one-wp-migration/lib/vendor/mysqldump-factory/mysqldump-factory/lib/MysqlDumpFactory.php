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

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MysqlDumpPDO.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MysqlDumpSQL.php';

class MysqlDumpFactory
{
	public static function makeMysqlDump($hostname = 'localhost', $username = '', $password = '', $database = '')
	{
		try {
			// Is PDO available?
			if (class_exists('PDO') && in_array('mysql', PDO::getAvailableDrivers())) {
				$client = new MysqlDumpPDO($hostname, $username, $password, $database);
			} else {
				$client = new MysqlDumpSQL($hostname, $username, $password, $database);
			}

			// Test connection
			$client->getConnection();
		} catch (Exception $e) {
			$client = new MysqlDumpSQL($hostname, $username, $password, $database);
		}

		return $client;
	}
}
