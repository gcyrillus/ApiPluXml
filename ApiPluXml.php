<?php if(!defined('PLX_ROOT')) exit;
	/**
		* Plugin 			ApiPluXml
		*
		* @CMS required			PluXml 
		*
		* @version			1.0
		* @date				2024-02-16
		* @author 			G-Cyrillus
		░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░
		░       ░░  ░░░░░░░  ░░░░  ░  ░░░░  ░░      ░░       ░░░      ░░  ░░░░░░░        ░░      ░░░░░   ░░░  ░        ░        ░
		▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒  ▒▒  ▒▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒  ▒▒▒▒▒▒▒▒▒▒    ▒▒  ▒  ▒▒▒▒▒▒▒▒▒▒  ▒▒▒▒
		▓       ▓▓  ▓▓▓▓▓▓▓  ▓▓▓▓  ▓▓▓    ▓▓▓  ▓▓▓▓  ▓       ▓▓  ▓▓▓▓  ▓  ▓▓▓▓▓▓▓▓▓▓  ▓▓▓▓▓      ▓▓▓▓▓  ▓  ▓  ▓      ▓▓▓▓▓▓  ▓▓▓▓
		█  ███████  ███████  ████  ██  ██  ██  ████  █  ███████  ████  █  ██████████  ██████████  ████  ██    █  ██████████  ████
		█  ███████        ██      ██  ████  ██      ██  ████████      ██        █        ██      ██  █  ███   █        ████  ████
		█████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
	**/
	class ApiPluXml extends plxPlugin {
		
		
		
		const BEGIN_CODE = '<?php' . PHP_EOL;
		const END_CODE = PHP_EOL . '?>';
		public $lang = ''; 
		
		
		public function __construct($default_lang) {
			# appel du constructeur de la classe plxPlugin (obligatoire)
			parent::__construct($default_lang);
			
			
			
			# droits pour accèder à la page config.php du plugin
			$this->setConfigProfil(PROFIL_ADMIN, PROFIL_MANAGER);		
			
			
			# Declaration des hooks		
			$this->addHook('AdminTopBottom', 'AdminTopBottom');
			$this->addHook('ThemeEndHead', 'ThemeEndHead');
			$this->addHook('apiKey', 'apiKey');
			$this->addHook('Index', 'Index');
			$this->addHook('IndexBegin', 'IndexBegin');
			
			
		}
		
		# Activation / desactivation
		
		public function OnActivate() {
			# code à executer à l’activation du plugin
			//nowizards set
		}
		
		public function OnDeactivate() {
			# code à executer à la désactivation du plugin
		}	
		
		
		public function ThemeEndHead() {
			#gestion multilingue
			if(defined('PLX_MYMULTILINGUE')) {		
				$plxMML = is_array(PLX_MYMULTILINGUE) ? PLX_MYMULTILINGUE : unserialize(PLX_MYMULTILINGUE);
				$langues = empty($plxMML['langs']) ? array() : explode(',', $plxMML['langs']);
				$string = '';
				foreach($langues as $k=>$v)	{
					$url_lang="";
					if($_SESSION['default_lang'] != $v) $url_lang = $v.'/';
					$string .= 'echo "\\t<link rel=\\"alternate\\" hreflang=\\"'.$v.'\\" href=\\"".$plxMotor->urlRewrite("?'.$url_lang.$this->getParam('url').'")."\" />\\n";';
				}
				echo '<?php if($plxMotor->mode=="'.$this->getParam('url').'") { '.$string.'} ?>';
			}
			
			
			// ajouter ici vos propre codes (insertion balises link, script , ou autre)
		}
		
		/**
			* Méthode qui affiche un message si le plugin n'a pas la langue du site dans sa traduction
			* Ajout gestion du wizard si inclus au plugin
			* @return	stdio
			* @author	Stephane F
		**/
		public function AdminTopBottom() {
			
			echo '<?php
			$file = PLX_PLUGINS."'.$this->plug['name'].'/lang/".$plxAdmin->aConf["default_lang"].".php";
			if(!file_exists($file)) {
			echo "<p class=\\"warning\\">'.basename(__DIR__).'<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
			plxMsg::Display();
			}
			?>';
		}
		
		/** 
			* Méthode MyHapKey
			* 
			* Descrition	:
			* @author		: TheCrok
			* 
		**/
		public function apiKey() {
			# code à executer
			// faut-il limiter l'accés aux abonnés et ne pas laisser n'importe qui pomper le site ?
			
			
		}
		
		
		/** 
			* Méthode Index
			* 
			* Descrition	:
			* @author		: TheCrok
			* 
		**/
		public function Index() {
			# code à executer
			if(isset($_GET['apiPluxml'])) {
				echo self::BEGIN_CODE;
			?>
				$next='';
				if(count($_GET) ==1) {
					echo 'ApiPluXml - version 0.0 ';
					include(PLX_PLUGINS.'<?= basename(__DIR__) ?>/lang/fr-help.php');
				}
				if(count($_GET)>2) {
				$next=',';
				}
				if($next !="") echo '[';
			if(count($_GET) > 1)header("Content-Type:application/json");
				#configuration				
				if(isset($_GET['config'])) {
				$notAllowedConfListing=array('lostpassword','clef','custom_admincss_file','email_method','smtp_server','smtp_username','smtp_password','smtp_port','smtp_security','smtpOauth2_emailAdress','smtpOauth2_clientId','smtpOauth2_clientSecret','smtpOauth2_refreshToken');
					foreach($plxMotor->aConf as $conf => $val) {
						if(in_array($conf,$notAllowedConfListing)) {
							unset($plxMotor->aConf[$conf]) ;				
						}				
					}				
				echo json_encode($plxMotor->aConf, JSON_PRETTY_PRINT | true).$next;
				}
				
				#statics				
				if(isset($_GET['static'])) {
				echo json_encode($plxMotor->aStats, JSON_PRETTY_PRINT | true).$next;}

				
				#categories
				if(isset($_GET['categorie'])) {
				echo json_encode($plxMotor->aCats, JSON_PRETTY_PRINT | true).$next;}
				
				#tags
				if(isset($_GET['etiquette'])) {
				echo json_encode($plxMotor->aTags, JSON_PRETTY_PRINT | true).$next;}
				
				#users
				if(isset($_GET['authors'])) {
				$authors = $plxMotor->aUsers;
				foreach($authors as $user => $values) {
				 foreach($values as $key => $v) {
				 if($key !== 'name' and $key !== 'infos') {
					 unset($values[$key]);
					$authors[$user]=$values;
					 }
				 } 
				}
				echo json_encode($authors, JSON_PRETTY_PRINT | true).$next;
				}

				#fichiers articles
				if(isset($_GET['artFiles'])) {
				echo json_encode($plxMotor->plxGlob_arts->aFiles, JSON_PRETTY_PRINT | true).$next;}
				
				#commentaires
				if(isset($_GET['comFiles'])) {
				//var_dump($plxMotor->plxGlob_coms->aFiles);
				foreach($plxMotor->plxGlob_arts->aFiles as $art) {
				$comArray=array();
				$coms= substr($art, 0, 4);
				 $comArray=$plxMotor->getCommentaires('#^'.$coms.'\.\d{10}-\d+\.xml$#',$plxMotor->tri_coms);
					if($comArray ==true)  $comsArray[$coms]=$plxMotor->plxRecord_coms;
				}
				echo json_encode($comsArray, JSON_PRETTY_PRINT | true).$next;
				}
				
				
				<?php
					echo self::END_CODE;
				}
			}
			
			
			/** 
				* Méthode IndexBegin
				* 
				* Descrition	:
				* @author		: TheCrok
				* 
			**/
			public function IndexBegin() {
				
				if(isset($_GET['apiPluxml'])) {
					# code à executer
					# voir si on ne peut pas extraire les contenus des articles avant de charger $plxshow
					echo self::BEGIN_CODE;
				?>
				if(isset($_GET['article'])){
				echo json_encode($plxShow->plxMotor->plxRecord_arts, JSON_PRETTY_PRINT | true).$next;
				}
				if($next !="") echo '{}]';
				exit;//apiPluxml demandé, on s'arrete là
				<?php
					echo self::END_CODE;		
					
				}
			}
			
			
		}		