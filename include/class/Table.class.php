<?php
	abstract class Table
	{
		protected $data = array();
		private $query_update = array();
		
		// Fonction magique __get
		public function __get($name)
		{
			if(isset($this->data[$name]))
			{
				if(is_array($this->data[$name]))
				{
					return $this->data[$name];
				}
				else
				{
					if(strpos($this->data[$name], '#') == FALSE)
						return $this->data[$name];
					return explode('#', $this->data[$name]);
				}
			}
			else
			{
				/*
				$trace = debug_backtrace();
				echo 'Propriété non-définie : data[\'' . $name .
					'\'] dans ' . $trace[0]['file'] .
					' à la ligne ' . $trace[0]['line'] . '<br />';
				*/
				return null;
			}
		}
		
		// Fonction magique __isset
		public function __isset($name)
		{
			return isset($this->data[$name]);
		}
		
		// Fonction magique __set
		public function __set($property, $value)
		{
			//include_once('../../include/functions.php');
			
			if(isset($this->data[$property]))
			{
				if(is_array($value))
					$value = implode('#', $value);
				$this->data[$property] = $value;
				$this->query_update[$property] = $this->data[$property];
			}
			else
			{
				$trace = debug_backtrace();
				trigger_error(
					'Propriété non-définie via __set(): [\'' . $property .
					'\'] dans ' . $trace[0]['file'] .
					' à la ligne ' . $trace[0]['line'],
					E_USER_NOTICE);
			}
		}
		
		// Fonction update, mis à jour dans la bdd
		public function update($name)
		{
			$bdd = MySQL::getInstance();
			
			// Requête
			$update = 'UPDATE ' . $name . ' SET ';
			$first = true;
			foreach ($this->query_update as $champ => $value)
			{
				($first) ? $first = false : $update .= ', ';
				$update .= $champ . ' = "' . $value . '"';
			}
			$update .= ' WHERE id = ' . $this->data['id'];
		
			$update_user = $bdd->query($update);
		}
	}
	
	class Insert
	{
		private $query_insert = array();
		private $name;
		
		public function __construct($name)
		{
			$this->name = $name;
		}
		
		// Fonction magique __set
		public function __set($property, $value)
		{
			// include_once('../functions.php');
			if(is_array($value))
				$value = implode('#', $value);
			$this->query_insert[$property] = MySQL::getInstance()->quote($value);
		}
		
		// Fonction insert
		public function execute()
		{
			$bdd = MySQL::getInstance();
			
			// Requête
			
			$insert = 'INSERT INTO ' . $this->name . '(';
			$a = true;
			foreach ($this->query_insert as $i => $j)
			{
				($a) ? $a = false : $insert .= ', ';
				$insert .= $i;
			}
			$insert .= ') VALUES(';
			
			$a = true;
			foreach ($this->query_insert as $i)
			{
				($a) ? $a = false : $insert .= ', ';
				$insert .= $i;
			}
			$insert .= ')';
			
			$insert_user = $bdd->query($insert);
			
			$this->query_insert = array();

			return $bdd->lastInsertId();
		}
	}

	class Select
	{
		private static $flag;
		private static $news;
		private static $competition;
		private static $opponent;
		private static $match2;
		private static $image;
		private static $game;
		private static $user;
		private static $comment;
		private static $picture;
		private static $video;
		private static $user_team;
		private static $team;
		private static $tournament_match;
		private static $groups;

		public function __construct() {}

		public static function all($table)
		{
			if(!isset(self::$$table))
			{
				self::$$table = MySQL::selectAll($table);
			}
			
			return self::$$table;
		}

		public static function withId($table, $id)
		{
			$vars = self::all($table);
			return @$vars[$id];
		}
	}