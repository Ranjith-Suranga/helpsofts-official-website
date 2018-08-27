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

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MysqlDumpInterface.php';
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'MysqlDumpUtility.php';

class MysqlDumpSQL implements MysqlDumpInterface
{
	protected $hostname             = null;

	protected $username             = null;

	protected $password             = null;

	protected $database             = null;

	protected $connection           = null;

	protected $oldTablePrefixes     = null;

	protected $newTablePrefixes     = null;

	protected $oldReplaceValues     = array();

	protected $newReplaceValues     = array();

	protected $queryClauses         = array();

	protected $tablePrefixColumns   = array();

	protected $includeTablePrefixes = array();

	protected $excludeTablePrefixes = array();

	/**
	 * Define MySQL credentials for the current connection
	 *
	 * @param  string $hostname MySQL Hostname
	 * @param  string $username MySQL Username
	 * @param  string $password MySQL Password
	 * @param  string $database MySQL Database
	 * @return void
	 */
	public function __construct($hostname = 'localhost', $username = '', $password = '', $database = '')
	{
		// Set MySQL credentials
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	/**
	 * Set old table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpSQL
	 */
	public function setOldTablePrefixes($prefixes)
	{
		$this->oldTablePrefixes = $prefixes;

		return $this;
	}

	/**
	 * Get old table prefixes
	 *
	 * @return array
	 */
	public function getOldTablePrefixes()
	{
		return $this->oldTablePrefixes;
	}

	/**
	 * Set new table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpSQL
	 */
	public function setNewTablePrefixes($prefixes)
	{
		$this->newTablePrefixes = $prefixes;

		return $this;
	}

	/**
	 * Get new table prefixes
	 *
	 * @return array
	 */
	public function getNewTablePrefixes()
	{
		return $this->newTablePrefixes;
	}

	/**
	 * Set old replace values
	 *
	 * @param  array $values List of values
	 * @return MysqlDumpSQL
	 */
	public function setOldReplaceValues($values)
	{
		$this->oldReplaceValues = $values;

		return $this;
	}

	/**
	 * Get old replace values
	 *
	 * @return array
	 */
	public function getOldReplaceValues()
	{
		return $this->oldReplaceValues;
	}

	/**
	 * Set new replace values
	 *
	 * @param  array $values List of values
	 * @return MysqlDumpSQL
	 */
	public function setNewReplaceValues($values)
	{
		$this->newReplaceValues = $values;

		return $this;
	}

	/**
	 * Get new replace values
	 *
	 * @return array
	 */
	public function getNewReplaceValues()
	{
		return $this->newReplaceValues;
	}

	/**
	 * Set query clauses
	 *
	 * @param  array $clauses List of SQL query clauses
	 * @return MysqlDumpSQL
	 */
	public function setQueryClauses($clauses)
	{
		$this->queryClauses = $clauses;

		return $this;
	}

	/**
	 * Get query clauses
	 *
	 * @return array
	 */
	public function getQueryClauses()
	{
		return $this->queryClauses;
	}

	/**
	 * Set table prefix columns
	 *
	 * @param  string $table   Table name
	 * @param  array  $columns Table columns
	 * @return MysqlDumpSQL
	 */
	public function setTablePrefixColumns($table, $columns)
	{
		foreach ($columns as $column) {
			$this->tablePrefixColumns[$table][$column] = true;
		}

		return $this;
	}

	/**
	 * Get table prefix columns
	 *
	 * @param  string $table Table name
	 * @return array
	 */
	public function getTablePrefixColumns($table)
	{
		if (isset($this->tablePrefixColumns[$table])) {
			return $this->tablePrefixColumns[$table];
		}

		return array();
	}

	/**
	 * Set include table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpSQL
	 */
	public function setIncludeTablePrefixes($prefixes)
	{
		$this->includeTablePrefixes = $prefixes;

		return $this;
	}

	/**
	 * Get include table prefixes
	 *
	 * @return array
	 */
	public function getIncludeTablePrefixes()
	{
		return $this->includeTablePrefixes;
	}

	/**
	 * Set exclude table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpSQL
	 */
	public function setExcludeTablePrefixes($prefixes)
	{
		$this->excludeTablePrefixes = $prefixes;

		return $this;
	}

	/**
	 * Get exclude table prefixes
	 *
	 * @return array
	 */
	public function getExcludeTablePrefixes()
	{
		return $this->excludeTablePrefixes;
	}

	/**
	 * Get tables
	 *
	 * @return array
	 */
	public function getTables()
	{
		$tables = array();

		// Get list of tables
		$result = mysql_unbuffered_query(
			"SELECT TABLE_NAME AS TableName FROM `INFORMATION_SCHEMA`.`TABLES` WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->database}'",
			$this->getConnection()
		);

		if ($result) {
			while ($row = mysql_fetch_assoc($result)) {
				if (isset($row['TableName']) && ($tableName = $row['TableName'])) {

					// Include table prefixes
					if ($this->getIncludeTablePrefixes()) {
						$include = false;

						// Check table prefixes
						foreach ($this->getIncludeTablePrefixes() as $prefix) {
							if (strpos($tableName, $prefix) === 0) {
								$include = true;
								break;
							}
						}

						// Skip current table
						if ($include === false) {
							continue;
						}
					}

					// Exclude table prefixes
					if ($this->getExcludeTablePrefixes()) {
						$exclude = false;

						// Check table prefixes
						foreach ($this->getExcludeTablePrefixes() as $prefix) {
							if (strpos($tableName, $prefix) === 0) {
								$exclude = true;
								break;
							}
						}

						// Skip current table
						if ($exclude === true) {
							continue;
						}
					}

					// Add table name
					$tables[] = $tableName;
				}
			}
		} else {
			$result = mysql_unbuffered_query("SHOW TABLES FROM `{$this->database}`", $this->getConnection());
			while ($row = mysql_fetch_row($result)) {
				if (isset($row[0]) && ($tableName = $row[0])) {

					// Include table prefixes
					if ($this->getIncludeTablePrefixes()) {
						$include = false;

						// Check table prefixes
						foreach ($this->getIncludeTablePrefixes() as $prefix) {
							if (strpos($tableName, $prefix) === 0) {
								$include = true;
								break;
							}
						}

						// Skip current table
						if ($include === false) {
							continue;
						}
					}

					// Exclude table prefixes
					if ($this->getExcludeTablePrefixes()) {
						$exclude = false;

						// Check table prefixes
						foreach ($this->getExcludeTablePrefixes() as $prefix) {
							if (strpos($tableName, $prefix) === 0) {
								$exclude = true;
								break;
							}
						}

						// Skip current table
						if ($exclude === true) {
							continue;
						}
					}

					// Add table name
					$tables[] = $tableName;
				}
			}
		}

		return $tables;
	}

	/**
	 * Get MySQL connection (lazy loading)
	 *
	 * @return resource
	 */
	public function getConnection()
	{
		if ($this->connection === null) {
			// Make connection (Socket)
			$this->connection = $this->makeConnection();

			if ($this->connection === false) {
				// Make connection (TCP)
				$this->connection = $this->makeConnection(false);

				// Unable to connect to MySQL database server
				if ($this->connection === false) {
					throw new Exception('Unable to connect to MySQL database server: ' . mysql_error($this->connection));
				}
			}
		}

		return $this->connection;
	}

	/**
	 * Run MySQL query
	 *
	 * @param  string   $query SQL query
	 * @return resource
	 */
	public function query($query)
	{
		return mysql_unbuffered_query($query, $this->getConnection());
	}

	/**
	 * Export database into a file
	 *
	 * @param  string $fileName Name of file
	 * @return bool
	 */
	public function export($fileName)
	{
		// Set file handler
		$fileHandler = fopen($fileName, 'wb');
		if ($fileHandler === false) {
			throw new Exception('Unable to open database file');
		}

		// Write headers
		if (fwrite($fileHandler, $this->getHeader()) === false) {
			throw new Exception('Unable to write database header information');
		}

		// Export tables
		foreach ($this->getTables() as $tableName) {

			// Replace table name prefixes
			$newTableName = $this->replaceTablePrefixes($tableName, true);

			// Get table structure
			$structure = mysql_unbuffered_query("SHOW CREATE TABLE `$tableName`", $this->getConnection());
			$table = mysql_fetch_assoc($structure);
			if (isset($table['Create Table'])) {

				// Write table drop statement
				$dropTable = "DROP TABLE IF EXISTS `$newTableName`;\n";

				// Write table statement
				if (fwrite($fileHandler, $dropTable) === false) {
					throw new Exception('Unable to write database table statement');
				}

				// Replace create table prefixes
				$createTable = $this->replaceTablePrefixes($table['Create Table'], true);

				// Strip table constraints
				$createTable = $this->stripTableConstraints($createTable);

				// Write table structure
				if (fwrite($fileHandler, $createTable) === false) {
					throw new Exception('Unable to write database table structure');
				}

				// Write end of statement
				if (fwrite($fileHandler, ";\n\n") === false) {
					throw new Exception('Unable to write database end of statement');
				}

				// Close structure cursor
				mysql_free_result($structure);
			} else {
				// Close structure cursor
				mysql_free_result($structure);

				break;
			}

			// Set query
			$query = "SELECT * FROM `$tableName` ";

			// Apply additional query clauses
			$clauses = $this->getQueryClauses();
			if (isset($clauses[$tableName]) && ($queryClause = $clauses[$tableName])) {
				$query .= $queryClause;
			}

			// Apply additional table prefix columns
			$columns = $this->getTablePrefixColumns($tableName);

			// Get results
			$result = mysql_unbuffered_query($query, $this->getConnection());

			// Generate insert statements
			while ($row = mysql_fetch_assoc($result)) {
				$items = array();
				foreach ($row as $key => $value) {
					// Replace table prefix columns
					if (isset($columns[$key])) {
						$value = $this->replaceTablePrefixes($value, true, 0);
					}

					// Replace table values
					$items[] = is_null($value) ? 'NULL' : "'" . mysql_real_escape_string($this->replaceTableValues($value), $this->getConnection()) . "'";
				}

				// Set table values
				$tableValues = implode(',', $items);

				// Set insert statement
				$tableInsert = "INSERT INTO `$newTableName` VALUES ($tableValues);\n";

				// Write insert statement
				if (fwrite($fileHandler, $tableInsert) === false) {
					throw new Exception('Unable to write database insert statement');
				}
			}

			// Write end of statements
			if (fwrite($fileHandler, "\n") === false) {
				throw new Exception('Unable to write database end of statement');
			}

			// Close result cursor
			mysql_free_result($result);
		}

		// Close file handler
		fclose($fileHandler);

		return true;
	}

	/**
	 * Import database from a file
	 *
	 * @param  string $fileName Name of file
	 * @return bool
	 */
	public function import($fileName)
	{
		// Set collation name
		$collation = $this->getCollation('utf8mb4_general_ci');

		// Set max allowed packet
		$maxAllowedPacket = $this->getMaxAllowedPacket();

		// Set file handler
		$fileHandler = fopen($fileName, 'r');
		if ($fileHandler === false) {
			throw new Exception('Unable to open database file');
		}

		$passed = 0;
		$failed = 0;
		$query  = null;

		// Read database file line by line
		while (($line = fgets($fileHandler)) !== false) {
			$query .= $line;

			// End of query
			if (preg_match('/;\s*$/', $query)) {

				// Check max allowed packet
				if (strlen($query) <= $maxAllowedPacket) {

					// Replace table prefixes
					$query = $this->replaceTablePrefixes($query);

					// Replace table values
					$query = $this->replaceTableValues($query, true);

					// Replace table collation
					if (empty($collation)) {
						$query = $this->replaceTableCollation($query);
					}

					try {
						// Run SQL query
						$result = mysql_unbuffered_query($query, $this->getConnection());
						if ($result === false) {
							throw new Exception(var_export(array(mysql_errno($this->getConnection()), mysql_error($this->getConnection()))));
						} else {
							$passed++;
						}

					} catch (Exception $e) {
						$failed++;
					}

				} else {
					$failed++;
				}

				$query = null;
			}
		}

		// Close file handler
		fclose($fileHandler);

		// Check failed queries
		if ((($failed / $passed) * 100) > 2) {
			return false;
		}

		return true;
	}

	/**
	 * Flush database
	 *
	 * @return void
	 */
	public function flush()
	{
		$dropTables = array();
		foreach ($this->getTables() as $tableName) {
			$dropTables[] = "DROP TABLE IF EXISTS `$tableName`";
		}

		// Drop tables
		foreach ($dropTables as $dropQuery) {
			mysql_unbuffered_query($dropQuery, $this->getConnection());
		}
	}

	/**
	 * Get MySQL version
	 *
	 * @return string
	 */
	protected function getVersion() {
		if (($result = mysql_unbuffered_query("SELECT @@version AS VersionName", $this->getConnection()))) {
			while ($row = mysql_fetch_assoc($result)) {
				if (isset($row['VersionName'])) {
					return $row['VersionName'];
				}
			}
		} else {
			$result = mysql_unbuffered_query("SHOW VARIABLES LIKE 'version'", $this->getConnection());
			while ($row = mysql_fetch_row($result)) {
				if (isset($row[1])) {
					return $row[1];
				}
			}
		}
	}

	/**
	 * Get MySQL max allowed packaet
	 *
	 * @return integer
	 */
	protected function getMaxAllowedPacket() {
		if (($result = mysql_unbuffered_query("SELECT @@max_allowed_packet AS MaxAllowedPacket", $this->getConnection()))) {
			while ($row = mysql_fetch_assoc($result)) {
				if (isset($row['MaxAllowedPacket'])) {
					return $row['MaxAllowedPacket'];
				}
			}
		} else {
			$result = mysql_unbuffered_query("SHOW VARIABLES LIKE 'max_allowed_packet'", $this->getConnection());
			while ($row = mysql_fetch_row($result)) {
				if (isset($row[1])) {
					return $row[1];
				}
			}
		}
	}

	/**
	 * Get MySQL collation name
	 *
	 * @param  string $collationName Collation name
	 * @return string
	 */
	protected function getCollation($collationName) {
		// Get collation name
		$result = mysql_unbuffered_query(
			"SELECT COLLATION_NAME AS CollationName FROM `INFORMATION_SCHEMA`.`COLLATIONS` WHERE COLLATION_NAME = '$collationName'",
			$this->getConnection()
		);

		if ($result) {
			while ($row = mysql_fetch_assoc($result)) {
				if (isset($row['CollationName'])) {
					return $row['CollationName'];
				}
			}
		} else {
			$result = mysql_unbuffered_query("SHOW COLLATION LIKE '$collationName'", $this->getConnection());
			while ($row = mysql_fetch_row($result)) {
				if (isset($row[0])) {
					return $row[0];
				}
			}
		}
	}

	/**
	 * Replace table prefixes
	 *
	 * @param  string  $input    Table value
	 * @param  boolean $first    Replace first occurrence at any position
	 * @param  boolean $position Replace first occurrence at a specified position
	 * @return string
	 */
	protected function replaceTablePrefixes($input, $first = false, $position = false)
	{
		// Get table prefixes
		$search = $this->getOldTablePrefixes();
		$replace = $this->getNewTablePrefixes();

		// Replace table prefixes
		if ($first) {

			// Replace first occurance at a specified position
			if ($position !== false) {
				for ($i = 0; $i < count($search); $i++) {
					$current = strpos($input, $search[$i]);
					if ($current === $position) {
						$input = substr_replace($input, $replace[$i], $current, strlen($search[$i]));
					}
				}

				return $input;
			}

			// Replace first occurance at any position
			for ($i = 0; $i < count($search); $i++) {
				$current = strpos($input, $search[$i]);
				if ($current !== $position) {
					$input = substr_replace($input, $replace[$i], $current, strlen($search[$i]));
				}
			}

			return $input;
		}

		// Replace all occurrences
		return str_replace($search, $replace, $input);
	}

	/**
	 * Replace table values
	 *
	 * @param  string  $input Table value
	 * @param  boolean $parse Parse value
	 * @return string
	 */
	protected function replaceTableValues($input, $parse = false)
	{
		// Get replace values
		$old = $this->getOldReplaceValues();
		$new = $this->getNewReplaceValues();

		$oldValues = array();
		$newValues = array();

		// Prepare replace values
		for ($i = 0; $i < count($old); $i++) {
			if (strpos($input, $old[$i]) !== false) {
				$oldValues[] = $old[$i];
				$newValues[] = $new[$i];
			}
		}

		// Do replace values
		if ($oldValues) {
			if ($parse) {
				// Parse and replace serialized values
				$input = $this->parseSerializedValues($input);

				// Replace values
				return MysqlDumpUtility::replaceValues($oldValues, $newValues, $input);
			}

			return MysqlDumpUtility::replaceSerializedValues($oldValues, $newValues, $input);
		}

		return $input;
	}

	/**
	 * Parse serialized values
	 *
	 * @param  string $input Table value
	 * @return string
	 */
	protected function parseSerializedValues($input)
	{
		// Serialization format
		$array  = '(a:\d+:{.*?})';
		$string = '(s:\d+:".*?")';
		$object = '(O:\d+:".+":\d+:{.*})';

		// Replace serialized values
		return preg_replace_callback("/'($array|$string|$object)'/", array($this, 'replaceSerializedValues'), $input);
	}

	/**
	 * Replace serialized values (callback)
	 *
	 * @param  array  $matches List of matches
	 * @return string
	 */
	protected function replaceSerializedValues($matches)
	{
		// Unescape MySQL special characters
		$input = MysqlDumpUtility::unescapeMysql($matches[1]);

		// Replace serialized values
		$input = MysqlDumpUtility::replaceSerializedValues($this->getOldReplaceValues(), $this->getNewReplaceValues(), $input);

		// Prepare query values
		return "'" . mysql_real_escape_string($input, $this->getConnection()) . "'";
	}

	/**
	 * Replace table collation
	 *
	 * @param  string $input SQL statement
	 * @return string
	 */
	protected function replaceTableCollation($input)
	{
		return str_replace('utf8mb4', 'utf8', $input);
	}

	/**
	 * Strip table constraints
	 *
	 * @param  string $input SQL statement
	 * @return string
	 */
	protected function stripTableConstraints($input)
	{
		$pattern = array(
			'/\s+CONSTRAINT(.+)REFERENCES(.+),/i',
			'/,\s+CONSTRAINT(.+)REFERENCES(.+)/i',
		);
		$replace = '';

		return preg_replace($pattern, $replace, $input);
	}

	/**
	 * Make MySQL connection
	 *
	 * @param  bool $useSocket Use socket or TCP connection
	 * @return resource
	 */
	protected function makeConnection($useSocket = true)
	{
		// Use Socket or TCP
		$hostname = ($useSocket ? $this->hostname : gethostbyname($this->hostname));

		// Make connection
		$connection = @mysql_pconnect($hostname, $this->username, $this->password);

		// Select database and set default encoding
		if ($connection) {
			if (mysql_select_db($this->database, $connection)) {
				// Set default encoding
				mysql_unbuffered_query("SET NAMES 'utf8'", $connection);

				// Set foreign key
				mysql_unbuffered_query("SET FOREIGN_KEY_CHECKS = 0", $connection);
			} else {
				throw new Exception('Could not select MySQL database: ' . mysql_error($connection));
			}
		}

		return $connection;
	}

	/**
	 * Returns header for dump file
	 *
	 * @return string
	 */
	protected function getHeader()
	{
		// Some info about software, source and time
		$header = "-- All In One WP Migration SQL Dump\n" .
				"-- http://servmask.com/\n" .
				"--\n" .
				"-- Host: {$this->hostname}\n" .
				"-- Generation Time: " . date('r') . "\n" .
				"-- MySQL Extension\n\n" .
				"--\n" .
				"-- Database: `{$this->database}`\n" .
				"--\n\n";

		return $header;
	}
}
