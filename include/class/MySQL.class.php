<?php

	class MySQL extends PDO
	{
		private static $_instance;

		public function __construct() {}

		public static function getInstance()
		{
			if (!isset(self::$_instance)) // Si on est pas connecté à la base de données
			{
				try
				{
					$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
					if($_SERVER['HTTP_HOST'] == 'localhost')
						self::$_instance = new PDO('mysql:host=localhost;dbname=phasev3', 'root', '', $pdo_options);
					else //if($_SERVER['HTTP_HOST'] == '127.0.0.1')
						self::$_instance = new PDO('mysql:host=localhost;dbname=phasev3', 'root', 'root', $pdo_options);
				}
				catch (PDOException $e)
				{
					echo 'Erreur : ' . $e->getMessage() . '<br />';
				}
			}
			
			return self::$_instance;
		}

		public static function exist($table, $champ, $value)
		{
			$bdd = self::getInstance();

			$requete = $bdd->prepare('SELECT * FROM '.$table.' WHERE '.$champ.' = ?');
			$requete->bindValue(1, $value);
			$requete->execute();
			if($requete->rowCount() > 0)
				return true;
			else
				return false;
		}

		public static function selectAll($table, $where = '1', $limit = '')
		{
			$bdd = self::getInstance();
			if(!empty($limit))
				$limit = 'LIMIT '.$limit;

			$champs2 = $bdd->query('SELECT '.getSelectWhat($table).' FROM '.$table.' WHERE '.$where.' '.getSelectOrder($table).' '.$limit)->fetchAll();

			$champs = array();
			foreach($champs2 as $champ)
				$champs[$champ['id']] = $champ;

			return $champs;
		}

		public static function selectLast($table, $where = '1')
		{
			$bdd = self::getInstance();
			$limit = "LIMIT 1";
			$champs = $bdd->query('SELECT '.getSelectWhat($table).' FROM '.$table.' WHERE '.$where.' '.getSelectOrder($table).' '.$limit)->fetchAll();

			return $champs[0];
		}
	}