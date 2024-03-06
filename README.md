# ApiPluXml
Ebauche d'une API pour le CMS PluXml

Extraction de données depuis PluXml renvoyées au format json

<h2>Exemple</h2>
<p><b>Affiche une liste de lien des pages statiques d'un site PluXml distant.</b></p>
<h3>Code à inserer dans le corps HTML de votre page</h3>
<pre><code>&lt;div id="results"><!-- La liste s'affiche ici -->&lt;/div>
<script>
/*/Config/*/
// Votre clé
const apiKey = 'apiPluXml';

// protocol HTTP du site (preference https (connexion sécurisé)| http non garantie )
// connexion https => https : OK | connexion http => http OK | connexions https => http  BLOCKED ! |  http => https OK
const ProtocolHTTP = 'https';/* or http */

// nom du domaine de l'API suivit d'un / et d'un ? si l'url rewriting n'est pas activé sur le site OluXml distant.
const apiPluXmlSite = 'pluxthemes.com/';/*  exemple: 'pluxopolis.net/crashnewstest/' ou 'pluxthemes.com/?'   */

// nombre d'article par page
const apibypage=''; /* rien = la config du site distant */  

// afficher l'article en entier ?
let artcontent= false ; /* pour voir tout l'article : mettre a  true */
/*/End Config/*/

// Création et appel du fichier javascript distant.
let scpt = document.createElement('script');
scpt.setAttribute('id','apiCall');
scpt.setAttribute('async','');
scpt.setAttribute('src', ProtocolHTTP+'://'+apiPluXmlSite.replace(/\?$/, '')+'plugins/ApiPluXml/js/apiCalling.js');
document.querySelector('#results').appendChild(scpt);

  var script = document.querySelector('#apiCall');
  script.addEventListener('load', function() {
	////fonctions d'appels et d'affichage html
	//========================================
	//	getPlxApiResult(apiPluXmlSite+'apiPluxml') ; // aide descriptif
	//	getPlxApiResult(apiPluXmlSite+'apiPluxml&static','static') ;
		getPlxApiResult(apiPluXmlSite+'apiPluxml&article&page_number=1&bypage=5','article') ;
	//	getPlxApiResult(apiPluXmlSite+'apiPluxml&categorie','categorie') ;
	//	getPlxApiResult(apiPluXmlSite+'apiPluxml&etiquette','etiquette') ;

	////fonction d'appels , retourne un objet json
	//============================================
	//	getPlxApiResult(apiPluXmlSite+apiPluxml&commentaires','commentaires') 
	//	getPlxApiResult(apiPluXmlSite+apiPluxml&authors','authors')  
  });					
</script>
</code></pre>
