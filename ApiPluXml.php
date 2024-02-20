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
		public $apiDatas= array(/*'artFiles',*/'authors','categorie','commentaires','config','etiquette','static');
		public $ended = false;
		public $apiKeyConfig;
		public $apiKeys = array();
		public $apiKeyCalling  ='';
		
		
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
		
		#gestion du multilingue
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
				echo "<p class=\\"warning\\">'.__CLASS__.'<br />".sprintf("'.$this->getLang('L_LANG_UNAVAILABLE').'", $file)."</p>";
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
			# initialisation des variables	
			$this->apiKeyConfig 	= $this->getParam('access')=='' ? 1: $this->getParam('access');
			if($this->apiKeyConfig == '1') {
				# pas de restrictions
				$this->apiKeys['apiPluXml'] = 'apiPluXml';
				$this->apiKeys = array_values($this->apiKeys);
				$this->apiKeyCalling = 'apiPluXml';
			}
			if (!function_exists('getallheaders')) {
				function getallheaders() {
					$headers = [];
					foreach ($_SERVER as $name => $value) {
						if (substr($name, 0, 5) == 'HTTP_') {
							$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
						}
					}
					return $headers;
				}
			}
			else {
				$headers= getallheaders();
			}
			
			if(isset($headers['apiKey'])) $this->apiKeyCalling = $headers['apiKey'];
			
			# restrictions : A developper plus tard;) 
		}
		
		
		/** 
			* Méthode Index
			* 
			* Descrition	: ecoute  si apiPluxml est demandé et fait le service
			* @author		: TheCrok
			* 
		**/
		public function Index() {
			if(isset($_GET['apiPluxml'])) {
				$this->apiKey();				
				if(!in_array($this->apiKeyCalling, $this->apiKeys)) {
					header( 'HTTP/1.1 401 Forbidden' );
					$this->lang('L_INVALID_KEY');
					exit;
				}
				echo self::BEGIN_CODE;
			?>
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Headers: apiKey");
			header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Pragma: no-cache");
			$plxMotor = plxMotor::getInstance();
			$plugin = $plxMotor->plxPlugins->getInstance('<?= __CLASS__ ?>');
			$plugin->getInfos();
			$plugin->version =$plugin->getInfo('version');
			$next='';
			if(count($_GET) ==1) {
				$plugin->apiHelp();
			}
			if(count($_GET)>2) {
				//$next=',';
			}
			if($next !="") echo '[';
			if(count($_GET) > 1)
			foreach($_GET as $getKey => $getVal) {
				if(in_array($getKey,$plugin->apiDatas)){
					header("Content-Type:application/json");
					switch ($getKey) {
						case 'config':
							$notAllowedConfListing=array('lostpassword','clef','custom_admincss_file','email_method','smtp_server','smtp_username','smtp_password','smtp_port','smtp_security','smtpOauth2_emailAdress','smtpOauth2_clientId','smtpOauth2_clientSecret','smtpOauth2_refreshToken');
							foreach($plxMotor->aConf as $conf => $val) {
								if(in_array($conf,$notAllowedConfListing)) {
									unset($plxMotor->aConf[$conf]) ;				
								}				
							}				
							echo json_encode($plxMotor->aConf, JSON_PRETTY_PRINT | true).$next;	
						break;	
						
						case 'static':
							header("Content-Type:application/json");
							echo json_encode($plxMotor->aStats, JSON_PRETTY_PRINT | true).$next;
						break;
						
						case 'categorie':
							echo json_encode($plxMotor->aCats, JSON_PRETTY_PRINT | true).$next;
						break;
						
						case 'etiquette':
							echo json_encode($plxMotor->aTags, JSON_PRETTY_PRINT | true).$next;
						break;
						
						case 'authors':
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
						break;
						
						case 'commentaires':
							foreach($plxMotor->plxGlob_arts->aFiles as $art) {
								$comArray=array();
								$coms= substr($art, 0, 4);
								$comArray=$plxMotor->getCommentaires('#^'.$coms.'\.\d{10}-\d+\.xml$#',$plxMotor->tri_coms);
								if($comArray ==true)  $comsArray[$coms]=$plxMotor->plxRecord_coms;
							}
							echo json_encode($comsArray, JSON_PRETTY_PRINT | true).$next;
						break;
						
						#fichiers articles ?? est ce utile ?? et les médias ?? et le café ... avec ou sans sucre? ;)
						/*	case 'artFiles':
						echo json_encode($plxMotor->plxGlob_arts->aFiles, JSON_PRETTY_PRINT | true).$next;
						break;
						*/	
						
						# jamais utilisé. pour mémoire	
						default:
						$plugin->apiHelp();				
					}
					#on ne prend qu'une requete à la fois
					exit;  
				}
			}
			

			<?php
			echo self::END_CODE;
			}
		}
		
		public function apiHelp() {
		$this->getInfos();
		
		header("Content-Type: text/html");
		echo'<!DOCTYPE html>
	<html lang="fr">
		<head>
		<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0">
			<title>ApiPluXml  - version  '.$this->getInfo('version').'  - date  '. $this->getInfo('date').'</title>
		</head>
	<body>';
		echo '<p style="text-align:center"><b>ApiPluXml</b> - version <b>'.$this->getInfo('version').'</b> - date <b>'. $this->getInfo('date').'</b></p>' ;
		include(PLX_PLUGINS.__CLASS__.'/lang/fr-help.php');				
		echo '	</body>
</html>';				
		} 
		
		/** 
		* Méthode IndexBegin
		* 
		* Descrition	: données articles ou errur sur requete inconnue
		* @author		: TheCrok
		* 
		**/
		public function IndexBegin() {
		// Contenus article: comme ces infos ne semblent disponibles qu'à partir d'ici ...
			if(isset($_GET['apiPluxml'])) {
				# code à executer
				# voir si on ne peut pas extraire les contenus des articles avant de charger $plxshow
				echo self::BEGIN_CODE;
				?>
				if(isset($_GET['article'])){
					header("Content-Type:application/json");
					echo json_encode($plxShow->plxMotor->plxRecord_arts, JSON_PRETTY_PRINT | true).$next;
				}
				#demande inconnue
				elseif( count($_GET) > 1){ 
					header( 'HTTP/1.1 400 BAD REQUEST' );
					$plugin = $plxShow->plxMotor->plxPlugins->getInstance('<?= __CLASS__ ?>');
					$plugin->apiHelp();
				}
				#apiPluxml s'arrete là!
				exit;
				<?php
				echo self::END_CODE;		
				
			}
		}
		
		
		}				
