<?php
	function id_rand($id) { return $id.'-'.id(6); }

	class Form
	{
		private $trad;
		private $error;
		
		// Constructeur
		
		public function __construct()
		{
			
		}
		
		/* Fonctions de créations du formulaire */
		
		public function initialize($id, $style = '', $action = '#', $method = 'post', $others = '')
		{
			$name = $id;
			$id = id_rand($id);
			echo '<form id="form_'.$id.'" name="form_'.$name.'" action="' . $action . '" method="'.$method.'" style="margin:auto;overflow:visible;' . $style . '" ' . $others . '>
			<p>
			';
		}
		public function initializeImg($id, $style = '', $action = '#', $method = 'post')
		{
			echo $this->initialize($id, $style, $action, $method, 'enctype="multipart/form-data"');
		}
		
		
		// fonction pour afficher le bouton de validation
		public function end($submit = '', $type = 'submit', $url = '', $style = '')
		{
			// include_once($_SERVER['DOCUMENT_ROOT'] . '/include/functions.php');

			if(empty($_SESSION['jeton']))
				$_SESSION['jeton'] = id(8);
				
			if($type == 'submit')
				$submit = '<input type="submit" style="' . $style . '" class="button" value="' . $submit . '" />';
			elseif($type == 'link')
				$submit = '<input type="submit" style="' . $style . '" class="link" value="' . $submit . '" />';
			else
				$submit = '<input type="image" src="' . $url . '" style="border:none;' . $style . '" alt="Validate" />';
			
			echo '<input type="hidden" name="jeton" value="' . $_SESSION['jeton'] . '" />
				' . $submit . '
			</form>';
		}
		
		public function label($id, $label, $class = 'label')
		{
			if(!empty($label))
				return '<label for="' . $id . '" class="' . $class . '">' . $label . ' </label>';
			else
				return '';
		}
		
		public function input($type = '', $label = '', $id,  $value = '', $others = '')
		{
			if($type == 'flag' OR $type == 'image' OR $type == 'date')
			{
				$this->{'input_'.$type}($label, $id, $value, $others);
				return;
			}

			$name = $id;
			$id = id_rand($id);
				
			// Si le formulaire à déjà été posté on garde la valeur précédente
			if(!empty($_POST[$name]))
				$value = $_POST[$name];
			
			// si la valeur n'est pas nulle on l'affiche
			if(!empty($value))
				$value = 'value="' . $value . '" ';
			else
			$value = '';
			echo $this->label($id, $label, 'label') . '
			<input type="' . $type . '" id="' . $id . '" class="input" name="' . $name . '" ' . $value . $others . ' /><br />
			';
		}
		public function input_flag($label = '', $id,  $value = '', $others = '')
		{
			$name = $id;
			$id = id_rand($id);

			$id3 = 'select_flag_' . $id;
			$id4 = 'img_flag_' . $id;

			// Si le formulaire à déjà été posté on garde la valeur précédente
			if(!empty($_POST[$name]))
				$value = $_POST[$name];
			
			if(empty($value))
				$value = 68;

			$flags = Select::all('flag');

			echo $this->label($id, $label) . '
			<select name="' . $name . '" id="' . $id3 .'" onchange="change_flag_form(\''.$id3.'\', \''.$id4.'\');" >
			';
			foreach($flags as $flag) {
				echo '<option value="'.$flag['id'].'" ';
				if($flag['id'] == $value)
					echo 'selected ';
				echo '>'.$flag['name'].'</option>';
			}
			echo '</select> <img id="'.$id4.'" src="include/img/flag/'.$flags[$value]['name'].'.png" /><br />
			';
		}
		public function verify_flag($name)
		{
			if(MySQL::exist('flag', 'id', @$_POST[$name]))
			{
				return $_POST[$name];
			}
			else
			{
				$this->error('Flag '.$_POST[$name].'doesn\'t exist.');
				return 0;
			}
		}
		public function input_image($label = '', $id,  $value = '', $others = '')
		{
			$max_size = 2*1024*1024; // 2 Mo

			$name = $id;
			$id = id_rand($id);

			$this->hidden('MAX_FILE_SIZE', $max_size);

			echo $this->label($id, $label) . '<input type="file" class="input" id="'.$id.'" name="'.$name.'" /><br />
			';
		}
		public function verify_image($name, $min_size = 0)
		{
			$folder = $_SERVER['DOCUMENT_ROOT'] . '/include/img/upload/';
			$max_size = 4*1024*1024; // 2MB
			$file = basename($_FILES[$name]['name']);
			$size = filesize($_FILES[$name]['tmp_name']);
			$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.PNG', '.GIF', '.JPG', '.JPEG');
			$extension = strrchr($_FILES[$name]['name'], '.');
			if(!$file)
				return 0;
			
			$file = substr_replace($file, '', strrpos(basename($_FILES[$name]['name']), $extension), strlen($extension));
			$file = id(6).'-'.getCanonical($file);
			$file_min = $file.'_min'.$extension;
			$file = $file.$extension;

			//Début des vérifications de sécurité...
			if(!in_array($extension, $extensions)) // Si l'extension n'est pas dans le tableau
			{
			    $error = 'The file extension must be .png, .gif, .jpg or jpeg.';
			}
			if($size>$max_size)
			{
			    $error = 'The file size is too big ('.round($size/1000000, 2).'MB). It must be less than '.round($max_size/1000000, 2).'MB. Please reduce it.';
			}
			if(empty($error)) //S'il n'y a pas d'erreur, on upload
			{
				if(move_uploaded_file($_FILES[$name]['tmp_name'], $folder . $file)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				{
					imagethumb($folder . $file, $folder . $file, 800);
					$insert_file = new Insert('image');
					$insert_file->url = $file;
					if($min_size > 0)
					{
						imagethumb($folder . $file, $folder . $file_min, $min_size);
						$insert_file->url_min = $file_min;
					}
					$file_id = $insert_file->execute();
				}
				else //Sinon (la fonction renvoie FALSE).
				{
					$error = 'An error occured, please try again.';
				}
			}

			if(!empty($error))
			{
				$this->error($error);
				return 0;
			}
			else
			{
				return $file_id;
			}
		}
		public function input_date($label = '', $id,  $value = '', $others = '')
		{
			$name = $id;
			$id = id_rand($id);

			$year_begin = 2012;
			$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
							'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

			if(empty($value))
			{
				$value_day = idate('d');
				$value_month = idate('m');
				$value_year = idate('Y');
			}

			// Si le formulaire à déjà été posté on garde la valeur précédente
			if(!empty($_POST['day_'.$name]))
				$value_day = $_POST['day_'.$name];
			if(!empty($_POST['month_'.$name]))
				$value_month = $_POST['month_'.$name];
			if(!empty($_POST['year_'.$name]))
				$value_year = $_POST['year_'.$name];

			// Valeur par défaut
			if(empty($value_day))
				$value_day = idate('d', strtotime($value));
			if(empty($value_month))
				$value_month = idate('m', strtotime($value));
			if(empty($value_year))
				$value_year = idate('Y', strtotime($value));
			
			$this->select('day_'.$name, $label);
			for($i = 1; $i <= 31; $i++)
			{
				$others = ($i == $value_day) ? 'selected' : '';
				$this->option($i, $i, $others);
			}
			$this->end_select(false);
			$this->select('month_'.$name);
			for($i = 1; $i <= 12; $i++)
			{
				$others = ($i == $value_month) ? 'selected' : '';
				$this->option($i, $months[$i-1], $others);
			}
			$this->end_select(false);
			$this->select('year_'.$name);
			for($i = $year_begin; $i <= idate('Y'); $i++)
			{
				$others = ($i == $value_year) ? 'selected' : '';
				$this->option($i, $i, $others);
			}
			$this->end_select();
		}
		public function verify_date($name)
		{
			$day = @$_POST['day_'.$name];
			$month = @$_POST['month_'.$name];
			$year = @$_POST['year_'.$name];

			if(checkdate($month, $day, $year))
			{
				return $year.'-'.$month.'-'.$day;
			}
			else
			{
				$this->error("Date isn't valid.");
				return 0;
			}
		}
		public function select_table($table, $champ, $label = '', $id,  $value = '', $others = '')
		{
			$name = $id;
			$id = id_rand($id);

			// Si le formulaire à déjà été posté on garde la valeur précédente
			if(!empty($_POST[$name]))
				$value = $_POST[$name];

			$elements = Select::all($table);
			
			$this->select($name, $label);
			foreach($elements as $element)
			{
				$others = ($value == $element['id']) ? 'selected' : '';
				$this->option($element['id'], $element[$champ], $others);
			}
			$this->end_select();
		}
		public function verify_table($table, $name)
		{
			$elem_id = @$_POST[$name];

			if(MySQL::exist($table, 'id', $elem_id))
			{
				return $elem_id;
			}
			else
			{
				$this->error("Id $elem_id dosn't exist in table $table");
				return 0;
			}
		}
		
		public function hidden($id, $value = '')
		{
			$name = $id;
			$id = id_rand($id);

			echo '<input type="hidden" id="' . $id. '" name="' . $name. '" value="' . $value . '" />
			';
		}
		
		public function textarea($id, $label, $value = '', $cols = '', $rows = '', $others = '')
		{
			$name = $id;
			$id = id_rand($id);

			if(!empty($_POST[$name]))
				$value = $_POST[$name];
				
			echo $this->label($id, $label, 'label') . '<br />
			<textarea id="' . $id . '" name="' . $name . '" cols="' . $cols . '" rows="' . $rows .'" ' . $others . '>' . $value . '</textarea>
			<br />';
		}
		
				
		public function radio($id, $label = '', $name = '', $others = '', $class_label = 'label')
		{
			echo '<input type="radio" id="' . $id . '" name="' . $name .'" ' . $others . ' value="' . $id . '"/>
			' . $this->label($id, $label, $class_label) . '<br />
			';
		}
		
		public function optgroup($id, $label = '', $class = '', $class_label = 'label')
		{
			echo '
			<optgroup label="' . $label . '" name="' . $id . '" id="' . $id .'" class="' . $class . '">
			';
		}	
		
		public function select($id, $label = '', $others = '')
		{
			$name = $id;
			$id = id_rand($id);

			echo $this->label($id, $label, 'label') . '
			<select name="' . $name . '" id="' . $id .'" ' . $others . '>
			';
		}
		
		public function option($value, $label, $others = '')
		{
			echo '<option value="' . $value . '" ' . $others . '>' . $label . '</option>
			';
		}
		
		public function end_select($br = true)
		{
			echo '</select>';
			if($br)
				echo '<br />
			';
		}
		
		public function end_optgroup()
		{
			echo '</optgroup>
			';
		}
		
		// fonction pour afficher une case à cocher
		public function checkbox($id, $label = '', $name = '', $class = '', $class_label = 'label')
		{
			if(!empty($_POST[$id]))
				$checked = 'checked';
			else
				$checked = '';

			echo '<input type="checkbox" id="' . $id . '" name="' . $id . '" ' . $checked . ' class="' . $class . '" /> ' . $this->label($id, $label, $class_label) . '<br />';
		}
		
		/* Fonctions de vérification du formulaire */
		
		// fonction pour vérifier les erreurs et les afficher
		public function error($error = '')
		{
			if(!empty($this->error))
				$this->error .= '<br />' . $error;
			else
				$this->error = $error;
				
			$_SESSION['error'] = $this->error;
		}


		public function __get($name)
		{
			if($name == 'error')
				return $this->error;
		}
		
		 /* Vérifications */

		// fonction pour vérifier que le jeton est le bon
		public function verify_jeton($jeton = '')
		{
			// retro-compatibilité
			if(empty($jeton) AND isset($_POST['jeton']))
				$jeton = $_POST['jeton'];
				
			// s'il y a une erreur dans les jetons
			if(empty($jeton) OR $_SESSION['jeton'] != $jeton)
			{
				// redirection
				exit();
			}
			else
				$_SESSION['jeton'] = '';
			
		}
		
		// fonction pour vérifier qu'un pseudo existe bien
		public function verify_pseudo($pseudo = '')
		{
			$bdd = MySQL::getInstance();
			
			$request_pseudo = $bdd->prepare('SELECT * FROM user WHERE pseudo = ?');
			$request_pseudo->bindValue(1, $pseudo, PDO::PARAM_STR);
			$request_pseudo->execute();
			
			if($request_pseudo->rowCount() == 0) // si le pseudo n'a pas été trouvée
				return 0;
			else
				return 1;
		}		
		
		// fonction pour vérifier qu'un id existe bien
		public function verify_id($id = '')
		{
			$bdd = MySQL::getInstance();
			
			$request_id = $bdd->prepare('SELECT * FROM user WHERE id = ?');
			$request_id->bindValue(1, $id, PDO::PARAM_STR);
			$request_id->execute();
			
			if($request_id->rowCount() == 0) // si l'id n'a pas été trouvé
				return 0;
			else
				return 1;
		}		
		
		// fonction pour vérifier qu'une adresse mail existe
		public function verify_exist_email($email = '')
		{
			$bdd = MySQL::getInstance();
			
			$request_email = $bdd->prepare('SELECT * FROM user WHERE email = ?');
			$request_email->bindValue(1, $email, PDO::PARAM_STR);
			$request_email->execute();
			
			if($request_email->rowCount() == 0) // si l'adresse mail n'a pas été trouvée
				return 0;
			else
				return 1;
		}
		
		// fonction pour vérifier qu'une adresse mail est correcte
		public function verify_email($email = '')
		{
			if(!preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $email))
				return $this->error($this->trad->get('invalid_email'));;
			
		}
		
		// fonction pour vérifier qu'un nouveau pseudo est valide
		public function new_pseudo($pseudo = '')
		{
			$bdd = MySQL::getInstance();
			
			$request_pseudo = $bdd->prepare('SELECT * FROM user WHERE pseudo = ?');
			$request_pseudo->bindValue(1, $pseudo, PDO::PARAM_STR);
			$request_pseudo->execute();
			
			if($request_pseudo->rowCount() != 0 OR !preg_match("#^[a-zA-Z0-9.@_-]{0,20}$#",$pseudo))
				$this->error($this->trad->get('invalid_pseudo'));
		}
		
		// fonction pour vérifier qu'un nombre est bien valide
		public function verify_number($number = '', $min = 0, $max = '', $name='invalid_field')
		{
			if(!preg_match("#^-?[0-9]+$#",$number) AND $this->invalid_field == NULL)
			{
				$this->invalid_field = 1; // on évite d'afficher plusieurs fois le même message
				
				if($this->error == NULL) // si c'est la seule erreur
					$this->error('Erreur dans un nombre entré.');
				else // sinon c'est une autre erreur
					$this->error($this->trad->get('invalid_fields'));
			}
			elseif((preg_match("#^[0-9]+$#",$max) AND $number > $max) OR (isset($min) AND $number < $min))
				$this->error($this->trad->get($name));
		
			
		}
			
		
	}