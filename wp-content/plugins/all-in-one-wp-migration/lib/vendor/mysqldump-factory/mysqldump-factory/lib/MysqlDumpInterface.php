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

interface MysqlDumpInterface
{
	/**
	 * Define MySQL credentials for the current connection
	 *
	 * @param  string $hostname MySQL Hostname
	 * @param  string $username MySQL Username
	 * @param  string $password MySQL Password
	 * @param  string $database MySQL Database
	 * @return void
	 */
	public function __construct($hostname = 'localhost', $username = '', $password = '', $database = '');

	/**
	 * Set old table prefix
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpInterface
	 */
	public function setOldTablePrefixes($prefixes);

	/**
	 * Get old table prefixes
	 *
	 * @return array
	 */
	public function getOldTablePrefixes();

	/**
	 * Set new table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpInterface
	 */
	public function setNewTablePrefixes($prefixes);

	/**
	 * Get new table prefixes
	 *
	 * @return array
	 */
	public function getNewTablePrefixes();

	/**
	 * Set old replace values
	 *
	 * @param  array $values List of values
	 * @return MysqlDumpInterface
	 */
	public function setOldReplaceValues($values);

	/**
	 * Get old replace values
	 *
	 * @return array
	 */
	public function getOldReplaceValues();

	/**
	 * Set new replace values
	 *
	 * @param  array $values List of values
	 * @return MysqlDumpInterface
	 */
	public function setNewReplaceValues($values);

	/**
	 * Get new replace values
	 *
	 * @return array
	 */
	public function getNewReplaceValues();

	/**
	 * Set query clauses
	 *
	 * @param  array $clauses List of SQL query clauses
	 * @return MysqlDumpInterface
	 */
	public function setQueryClauses($clauses);

	/**
	 * Get query clauses
	 *
	 * @return array
	 */
	public function getQueryClauses();

	/**
	 * Set table prefix columns
	 *
	 * @param  string $table   Table name
	 * @param  array  $columns Table columns
	 * @return MysqlDumpInterface
	 */
	public function setTablePrefixColumns($table, $columns);

	/**
	 * Get table prefix columns
	 *
	 * @param  string $table Table name
	 * @return array
	 */
	public function getTablePrefixColumns($table);

	/**
	 * Set exclude table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpInterface
	 */
	public function setExcludeTablePrefixes($prefixes);

	/**
	 * Get exclude table prefixes
	 *
	 * @return array
	 */
	public function getExcludeTablePrefixes();

	/**
	 * Get tables
	 *
	 * @return array
	 */
	public function getTables();

	/**
	 * Get MySQL connection (lazy loading)
	 *
	 * @return resource
	 */
	public function getConnection();

	/**
	 * Run MySQL query
	 *
	 * @param  string   $query SQL query
	 * @return resource
	 */
	public function query($query);

	/**
	 * Export database into a file
	 *
	 * @param  string $fileName Name of file
	 * @return bool
	 */
	public function export($fileName);

	/**
	 * Import database from file
	 *
	 * @param  string $fileName Name of file
	 * @return bool
	 */
	public function import($fileName);

	/**
	 * Flush database
	 *
	 * @return void
	 */
	public function flush();
}
