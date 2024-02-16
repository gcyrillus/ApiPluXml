<?php
	if(!defined('PLX_ROOT')) exit;
	/**
	* Plugin 			ApiPluXml
	*
	* @CMS required		PluXml 
	* @page				config.php
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
	# Control du token du formulaire
	plxToken::validateFormToken($_POST);
	
	# Liste des langues disponibles et prises en charge par le plugin
	$aLangs = array($plxAdmin->aConf['default_lang']);	
	
	if(!empty($_POST)) {
	
	$plxPlugin->setParam('access', $_POST['access'], 'numeric');
	$plxPlugin->saveParams();	
	header("Location: parametres_plugin.php?p=".basename(__DIR__));
	exit;
	}
	
	# initialisation des variables	
	$var['access'] 	= $plxPlugin->getParam('access')=='' ? 0: $plxPlugin->getParam('access');
	
	# initialisation des variables propres à chaque lanque
	$langs = array();
	foreach($aLangs as $lang) {
	# chargement de chaque fichier de langue
	$langs[$lang] = $plxPlugin->loadLang(PLX_PLUGINS.'ApiPluXml/lang/'.$lang.'.php');
	$var[$lang]['mnuName'] =  $plxPlugin->getParam('mnuName_'.$lang)=='' ? $plxPlugin->getLang('L_DEFAULT_MENU_NAME') : $plxPlugin->getParam('mnuName_'.$lang);
	}
	
	
	?>
	<link rel="stylesheet" href="<?php echo PLX_PLUGINS."ApiPluXml/css/tabs.css" ?>" media="all" />
	<p>Une API pour un PluXml HeadLess</p>	
	<h2><?php $plxPlugin->lang("L_CONFIG") ?></h2>
	 
	<div id="tabContainer">
	<form action="parametres_plugin.php?p=<?= basename(__DIR__) ?>" method="post" >
	<div class="tabs">
	<ul>
	
	
	</ul>
	</div>
	<div class="tabscontent">
	<div class="tabpage" id="tabpage_Param">	
	<fieldset><legend><?= $plxPlugin->getLang('L_PARAMS_ACCESS') ?></legend>
					<p class="alert red">NON FONCTIONNEL ! pas de restriction possible</p>
					<p>
						<label for="id_access"><?php echo $plxPlugin->lang('L_ALLOW_ACCESS') ?>&nbsp;:</label>
						<?php plxUtils::printSelect('access',array('1'=>$plxPlugin->getLang('L_ALL'),'0'=>$plxPlugin->getLang('L_KEY')),$var['access']); ?>
					</p>	
		
	</fieldset>
	</div>
	
	<fieldset>
	<p class="in-action-bar">
	<?php echo plxToken::getTokenPostMethod() ?><br>
	<input type="submit" name="submit" value="<?= $plxPlugin->getLang('L_SAVE') ?>"/>
	</p>
	</fieldset>
	</form>
	</div>
	<script type="text/javascript" src="<?php echo PLX_PLUGINS."ApiPluXml/js/tabs.js" ?>"></script>