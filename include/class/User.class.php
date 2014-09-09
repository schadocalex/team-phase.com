<?php

	function getIp()
	{
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_CLIENT_IP']))
			return $_SERVER['HTTP_CLIENT_IP'];
		else
			return $_SERVER['REMOTE_ADDR'];
	}
	
	class User extends Table
	{
		private $table;
		private $module_name;
		private $module_number;
		// Fonctions privées
		
		// Constructeur
		
		public function __construct($id = 0, $username = '', $module = '') 
		{
			$bdd = MySQL::getInstance();
			
			$this->module_name = array();
			$this->module_number = 0;
			
			if(empty($id) AND empty($username))
			{
				if(empty($_SESSION['username']) OR empty($_SESSION['password']))
				{
					if(!empty($_COOKIE['username']) AND !empty($_COOKIE['password']))
					{
						$_SESSION['username'] = $_COOKIE['username'];
						$_SESSION['password'] = $_COOKIE['password'];
					}
					else
					{
						$_SESSION['username'] = 'Visitor';
						$_SESSION['password'] = '';
					}
				}
				$username = $_SESSION['username'];
				$password = $_SESSION['password'];
				
				$request_user = $bdd->prepare('SELECT * FROM user WHERE username = ? AND password = ?');
				$request_user->bindValue(1, $username, PDO::PARAM_STR);
				$request_user->bindValue(2, $password, PDO::PARAM_STR);
				$request_user->execute();
				
				// number of connected
				if($request_user->rowCount() > 0)
				{
					$request_connected = $bdd->prepare('UPDATE user SET last_visit = :last_visit, ip = :ip WHERE username = :username');
					$request_connected->bindValue('last_visit',  date('Y-m-d H:i:s'), PDO::PARAM_STR);
					$request_connected->bindValue('ip', getIp(), PDO::PARAM_STR);
					$request_connected->bindValue('username', $username, PDO::PARAM_STR);
					$request_connected->execute();
				}
			}
			elseif(!empty($username))
			{
				$request_user = $bdd->prepare('SELECT * FROM user WHERE username = ?');
				$request_user->bindValue(1, $username, PDO::PARAM_STR);
				$request_user->execute();
			}
			else
			{
				$request_user = $bdd->prepare('SELECT * FROM user WHERE id = ?');
				$request_user->bindValue(1, $id, PDO::PARAM_INT);
				$request_user->execute();
				
			}
			
			if($request_user->rowCount() > 0)
			{
				while($request_user2 = $request_user->fetch())
				{
					$number_column = $request_user->columnCount();
					for($i = 0; $i < $number_column; $i++)
					{
						$array = $request_user->getColumnMeta($i);
						// $fields[] = $array['name'];
						$this->data[$array['name']] = $request_user2[$array['name']];
						if(empty($this->data[$array['name']]))
							$this->data[$array['name']] = '';
					}
				}
			
			}
			else
			{
				$this->data['username'] = 'Visiteur';
				$this->data['rank'] = 1;
			}
			
			// si le joueur n'a pas d'avatar : on en met un par défaut
			/* TODO
			if(empty($this->data['url_avatar'])) 
				$this->data['url_avatar'] = 'include/images/site/no_avatar.png';
			*/
			
			if(!empty($module))
				$this->addModule($module);
			
			$request_user->closeCursor();
		}
		
				
		public function addModule($name)
		{
			$this->module_name[$this->module_number] = $name;
			
			$module = $this->module_name[0];
			$Module = ucfirst($module);
			$Name = ucfirst($name);
			include_once($_SERVER['DOCUMENT_ROOT'] . '/modules/' . $module . '/' . $Module . '.class.php');
			$this->table[$this->module_number] = new $Name($this->data['id']);
			
			$this->module_number++;
		}
		
		// get
		
		public function __get($name)
		{
			foreach($this->module_name as $i => $j)
			{
				if($name == $j)
					return $this->table[$i];
			}
			return parent::__get($name);
		}
		
		// Affichage
		
		public function username()
		{
			$username = '<a href="Profile-'.$this->id.'-'.getCanonical($this->username).'">' . dispFlag($this->data['country_id']) . ' '. $this->data['username'] . '</a>';
			
			return $username;
		}
		
		// Redirection
		
		public function redirect($url)
		{
			header('location:' . $url);
			exit();
		}
		
		// Fonction update
		
		public function update($name = 'user')
		{
			$this->id = $this->id;
			return parent::update($name);
		}	

		public function is($s)
		{
			/*
			*	VISITOR = 1
			*	MEMBER = 2
			*	ADMIN = 3
			*	ADMIN_NEWS = 4
			*	ADMIN_MEMBERS = 5
			*	ADMIN_RESULTS = 6
			*	ADMIN_MEDIAS = 7
			*	ADMIN_OTHERS = 8
			*	ADMIN_ALL = 9
			*	SUPER_ADMIN = 10
			*	DEV = 42
			*/

			if($s == 'VISITOR')
				$rank = array(1);
			if($s == 'MEMBER')
				$rank = array(2,3,4,5,6,7,8,9,10,42);
			if($s == 'ADMIN')
				$rank = array(3,4,5,6,7,8,9,10,42);
			if($s == 'ADMIN_NEWS')
				$rank = array(4,9,10,42);
			if($s == 'ADMIN_MEMBERS')
				$rank = array(5,9,10,42);
			if($s == 'ADMIN_RESULTS')
				$rank = array(6,9,10,42);
			if($s == 'ADMIN_MEDIAS')
				$rank = array(7,9,10,42);
			if($s == 'ADMIN_OTHERS')
				$rank = array(8,9,10,42);
			if($s == 'SUPER_ADMIN')
				$rank = array(10,42);
			if($s == 'DEV')
				$rank = array(42);

			if(isset($rank))
				return in_array($this->data['rank'], $rank);
			else
			{
				echo "Role $s not defined in $user->is() function<br />";
				return false;
			}
		}
		public function accessRight($s)
		{
			if(!$this->is($s))
			{
				$_SESSION['error'] = "You don't have the access right '$s'.<br/>";
				if($this->is('VISITOR'))
				{
					$_SESSION['error'] .= "Try to login before.<br />";
					$this->redirect('Login');
				}
				else
				{
					// TO DO : page erreur "access denied"
					$this->redirect('Access-Denied');
				}
			}
		}
	}