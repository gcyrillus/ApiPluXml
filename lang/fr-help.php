<style>div#apiplx  {color:#333;width:90ch;min-width:30em;max-width:100%;padding:1em;;margin:auto;border:solid 1px;border-radius:5px;background:ivory;}
#apiplx p{text-indent: 1em;}
#apiplx dt{font-weight: bold;color:purple}
#apiplx dd {padding-bottom: .5em;color:orangered}
#apiplx a {color:darkblue}
#apiplx dl {width:max-content;margin:auto;max-width:85%;}
html:not(:has(section)) {min-height:100%;display:grid;place-content:center}
body:not(:has(section)) b {color:cornflowerblue;font-weight: bolder}
#apiplx h1 {text-align: center;text-decoration:underlin;color:#5CAE5Fe}
#apiplx :is(h2,h3,h4,h5,h6) {color:#5CAE5F;border-bottom:solid 1px;width:max-content;min-width:72.5%;max-width:95%;text-indent:1rem;;border-radius:0 0 0 .5rem;}
#apiplx :is(h3,pre) + p {color:darkcyan}
#apiplx pre>b:first-child{background:wheat;border-bottom:solid 1px #999;display:block;margin:-.25em -.5em 0;padding-bottom:.15em}
#apiplx pre {max-width:90%;margin:auto;overflow:auto;padding:.25em .5em;border:solid 1px;color:darkslateblue;background:whitesmoke;font-weight: bold}
#apiplx .apiEnd {margin:1em;border-radius:0.1em;border:solid 1px;padding:.25em;background:lightgreen;font-size:1.3em;color:#036;}
#apiplx .apiEnd a {color:crimson;}</style>
<div id="apiplx">
	<h1>Aide du plugin ApiPluXml</h1>
	<p>aide redigé</p>
	<p>Hook >aucun -|_ Options: GET uniquement -|- Clé de connexion  (actuellement optionnelle)</p>
	<h2>Utilisation</h2>
	<p>Activer le plugin - Les données publiques de votre site sont alors aussi consultables via l'Api.</p>
	<p>acceder à votre site par son adresse en ajoutant à l'url les données que vous voulez utiliser</p>
	<h2>Tableaux des données disponibles:</h2>
	<h3>Affichage brut</h3>
	<p>Le format d'affichage brut au format json est lisible à l'écran. C'est aussi un format standard accessible par de nombreux programmes et scripts. </p>
	<dl>
	<dt>Pour accéder a l'aide</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml" target="_blank"><code>?apiPluxml</code></a></dd>
	<dt>Pour obtenir les données des catégories au format json:</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&categorie" target="_blank"><code>?apiPluxml&categorie</code></a></dd>
	<dt>Pour obtenir les données des pages statiques au format json:</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&static" target="_blank"><code>?apiPluxml&static</code></a></dd>
	<dt>Pour obtenir les données des mots clés au format json:</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&etiquette" target="_blank"><code>?apiPluxml&etiquette</code></a></dd>
	<dt>Pour obtenir les données des articles au format json:</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&article" target="_blank"><code>?apiPluxml&article</code></a></dd>
	<dt>Pour obtenir les données des commentaires au format json:</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&commentaires" target="_blank"><code>?apiPluxml&commentaires</code></a></dd>
	<dt>Pour obtenir des données de configuration au format json:(données sensibles filtrées)</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&config" target="_blank"><code>?apiPluxml&config</code></a></dd>
	<dt>Pour obtenir les données des Auteurs au format json:(données sensibles filtrées)</dt>
	<dd>Taper l'adresse de votre site suivi de <a href="<?= PLX_ROOT ?>?apiPluxml&authors" target="_blank"><code>?apiPluxml&authors</code></a></dd>
	</dl><p><strong>En installant ce plugin, ce sont toutes ces données qui peuvent être consultée à distance sans ouvrir vos pages.</strong></p>
	<h3>Affichage Personnalisé</h3>
	<p>Les données fournies peuvent-être traitées par differents langages et réutilisées de differentes manieres.
	Le plugin vous propose un fichier javacript doté de plusieurs fonctions d'affichages et options de configurations
	pour traiter les données renvoyer par un site où le plugin apiPluXml est activé.Le site peut-être distant ou être le site lui même.</p>
	<p>Voici le début du fichier JavaScript avec ces options de configaration à regler à votre convenance</p>
	<pre><b>Extrait de apiCalling.js</b><code>/*/Config/*/
const apiKey = 'apiPluXml'; // Dans le futur, ce sera votre clé personnel à demander au responsable du site duquel vous souhaitez utiliser les ressources.
const ProtocolHTTP = 'https';/* anything or http */
const apiPluXmlSite = 'pluxopolis.net/crashnewstest';/* pluxml site domain name  where to fetch datas examples: [pluxopolis.net/crashnewstest] (without brackets)   */
const apibypage=''; /* default value is 5*/  
let artcontent= false ; /* afficher uniquement le chapo des articles distants | pour voir tout l'article : mettre à true */
/*/End Config/*/</code></pre>
<p>Pour un fonctionement optimale, il est necessaire que le site sollicité et le site demandeur se connectent 
et échangent via le protocol sécurisé HTTPS. </p>
	<p>Avec ce fichier JavaScript vient un fichier HTML d'exemple d'utilisation de l'unique fonction pour interrogé l'API 
	et un conteneur HTML qui sert de receptacle pour l'affichage.Voici un aperçu de ce fichier :</p>
	<pre><b>Fichier apiCalling.html</b><code>&lt;script>
////fonctions d'appels et d'affichage html
//========================================
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml') ; // aide descriptif
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&static','static') ;
	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&article&page_number=1&bypage=5','article') ;
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&categorie','categorie') ;
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&etiquette','etiquette') ;

////fonction d'appels , retourne un objet json
//============================================
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&commentaires','commentaires') 
//	getPlxApiResult(apiPluXmlSite+'/?apiPluxml&authors','authors') 
&lt;/script>
&lt;div id="results">&lt;!-- La requête s'affiche ici -->&lt;/div>	
	</code></pre>
	<p>décommenter les lignes que vous voulez utiliser</p>	
	<p>Ce ne sont bien entendue que quelques exemples d'usage possibles.</p>
	<p class="apiEnd">Pour plus d'aide ou remonter un dysfonctionement , il ya le <a href="https://forum.pluxml.org" target="_blank">forum de pluxml</a>
	et/ou <a href="https://github.com/gcyrillus/ApiPluXml" target="_blank">son repo github</a> 
	pour y trouver la derniere version, proposer des corrections ou notifier des défauts.</p>
</div>