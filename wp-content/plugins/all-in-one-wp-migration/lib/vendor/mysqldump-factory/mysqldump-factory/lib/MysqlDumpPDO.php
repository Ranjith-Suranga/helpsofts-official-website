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

class MysqlDumpPDO implements MysqlDumpInterface
{
	protected $hostname             = null;

	protected $port                 = null;

	protected $socket               = null;

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
		$dsn = $this->parseDSN($hostname);

		// Set MySQL credentials
		$this->hostname = $dsn['host'];
		$this->port     = $dsn['port'];
		$this->socket   = $dsn['socket'];
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	/**
	 * Set old table prefixes
	 *
	 * @param  array $prefixes List of table prefixes
	 * @return MysqlDumpPDO
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
	 * @param  array $prefix List of table prefixes
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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
	 * @return MysqlDumpPDO
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

		try {
			$result = $this->getConnection()->query(
				"SELECT TABLE_NAME AS TableName FROM `INFORMATION_SCHEMA`.`TABLES` WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA = '{$this->database}'"
			);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
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
		} catch (Exception $e) {
			$result = $this->getConnection()->query("SHOW TABLES FROM `{$this->database}`");
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
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
	 * @return PDO
	 */
	public function getConnection()
	{
		if ($this->connection === null) {
			try {
				// Make connection (Socket)
				$this->connection = $this->makeConnection();
			} catch (Exception $e) {
				try {
					// Make connection (TCP)
					$this->connection = $this->makeConnection(false);
				} catch (Exception $e) {
					throw new Exception('Unable to connect to MySQL database server: ' . $e->getMessage());
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
		return $this->getConnection()->query($query);
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
			$structure = $this->getConnection()->query("SHOW CREATE TABLE `$tableName`");
			$table = $structure->fetch(PDO::FETCH_ASSOC);
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
				$structure->closeCursor();
			} else {
				// Close structure cursor
				$structure->closeCursor();

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
			$result = $this->getConnection()->query($query);

			// Generate insert statements
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				$items = array();
				foreach ($row as $key => $value) {
					// Replace table prefix columns
					if (isset($columns[$key])) {
						$value = $this->replaceTablePrefixes($value, true, 0);
					}

					// Replace table values
					$items[] = is_null($value) ? 'NULL' : $this->getConnection()->quote($this->replaceTableValues($value));
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
			$result->closeCursor();
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
						$result = $this->getConnection()->query($query);
						if ($result === false) {
							throw new PDOException(var_export($this->getConnection()->errorinfo(), true));
						} else {
							$passed++;
						}

					} catch (PDOException $e) {
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
			$this->getConnection()->query($dropQuery);
		}
	}

	/**
	 * Get MySQL version
	 *
	 * @return string
	 */
	protected function getVersion() {
		try {
			$result = $this->getConnection()->query("SELECT @@version AS VersionName");
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if (isset($row['VersionName'])) {
					return $row['VersionName'];
				}
			}
		} catch (Exception $e) {
			$result = $this->getConnection()->query("SHOW VARIABLES LIKE 'version'");
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
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
		try {
			$result = $this->getConnection()->query("SELECT @@max_allowed_packet AS MaxAllowedPacket");
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if (isset($row['MaxAllowedPacket'])) {
					return $row['MaxAllowedPacket'];
				}
			}
		} catch (Exception $e) {
			$result = $this->getConnection()->query("SHOW VARIABLES LIKE 'max_allowed_packet'");
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
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
		try {
			$result = $this->getConnection()->query(
				"SELECT COLLATION_NAME AS CollationName FROM `INFORMATION_SCHEMA`.`COLLATIONS` WHERE COLLATION_NAME = '$collationName'"
			);
			while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
				if (isset($row['CollationName'])) {
					return $row['CollationName'];
				}
			}
		} catch (Exception $e) {
			$result = $this->getConnection()->query("SHOW COLLATION LIKE '$collationName'");
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
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
		return $this->getConnection()->quote($input);
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
	 * @return PDO
	 */
	protected function makeConnection($useSocket = true)
	{
		// Use Socket or TCP
		$hostname = ($useSocket ? $this->hostname : gethostbyname($this->hostname));

		// Use default or custom port
		if ($this->port === 3306 || empty($this->port)) {
			$dsn = sprintf('mysql:host=%s;dbname=%s', $hostname, $this->database);
		} else if (!empty($this->socket)) {
			$dsn = sprintf('mysql:host=%s;unix_socket=%s;dbname=%s', $hostname, $this->socket, $this->database);
		} else {
			$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s', $hostname, $this->port, $this->database);
		}

		// Make connection
		$connection = new PDO(
			$dsn,
			$this->username,
			$this->password,
			array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			)
		);

		// Set additional connection attributes
		$connection->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
		$connection->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

		// Set default encoding
		$connection->exec("SET NAMES 'utf8'");

		// Set foreign key
		$connection->exec("SET FOREIGN_KEY_CHECKS = 0");

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
				"-- PDO Extension\n\n" .
				"--\n" .
				"-- Database: `{$this->database}`\n" .
				"--\n\n";

		return $header;
	}

	/**
	 * Parse data source name
	 *
	 * @param  string $input Data source name
	 * @return array         List of host, port and socket
	 */
	protected function parseDSN($input) {
		$data = explode(':', $input);

		// Set hostname
		$host = 'localhost';
		if (!empty($data[0])) {
			$host = $data[0];
		}

		// Set port and socket
		$port = $socket = null;
		if (!empty($data[1])) {
			if (is_numeric($data[1])) {
				$port = $data[1];
			} else {
				$socket = $data[1];
			}
		}

		return array(
			'host'   => $host,
			'port'   => $port,
			'socket' => $socket,
		);
	}
}
