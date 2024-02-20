# ApiPluXml
Ebauche d'une API pour le CMS PluXml

Extraction de données depuis PluXml renvoyées au format json

<h2>Exemple</h2>
<p><b>Affiche une liste de lien des pages statiques d'un site PluXml distant.</b></p>
<h3>Code à inserer dans le corps HTML de votre page</h3>
<pre><code>
    <script>
    /*/Config/*/
    const apiKey = 'apiPluXml';
    const ProtocolHTTP = 'https';/* anything or http */
    const apiPluXmlSite = 'pluxopolis.net/crashnewstest';/* pluxml site domain name  where to fetch datas example: [pluxopolis.net/crashnewstest] (without brackets)   */
    /*/End Config/*/

    let s = ''; 
    if(ProtocolHTTP != 'http') s='s';
    function getPlxApiResult(u,q) {
        fetch('//'+u,{
            method: 'GET',
            headers:{'apiKey': apiKey
            }
        })
        .then(response => response.text()) // Parse the response as text
        .then(text => {
            try {
                const data = JSON.parse(text); // Try to parse the response as JSON
                console.log(data);
                show(data,'json',q);
                } catch(err) {
                console.log(text);
                show(text,'html',q);
            }
        });  
    }
    
    function show(datas,type,q) {
        const res =	document.querySelector("#results");
        
        if(type=='html') {
            res.insertAdjacentHTML( 'afterend',datas);
        }
        if (type=='json'){
        
         
          if(q =='static') {
            let static=new Array();
            let tpl='<ul>';
            Object.entries(datas).forEach((entry) => {
                const [key, value] = entry;
                let num= entry[0].replace(/^0+/, '');
                tpl +='<li><a hre'+'f="http'+s+'://'+apiPluXmlSite+'/?static'+ num +'/'+ datas[`${key}`]['url']+'">'+ datas[`${key}`]['name']+'</a></li>';
                console.log(datas[`${key}`]['name'])
            });
            tpl +='</ul>';
            res.insertAdjacentHTML( 'afterbegin', tpl);
          }
        }
        
    }
     
    //getPlxApiResult(apiPluXmlSite+'/?apiPluxml') ; // aide descriptif
    getPlxApiResult(apiPluXmlSite+'/?apiPluxml&static','static') ;
    //getPlxApiResult(apiPluXmlSite+'/?apiPluxml&article','article') ;
    //getPlxApiResult(apiPluXmlSite+'/?apiPluxml&categorie','categorie') ;
    //getPlxApiResult(apiPluXmlSite+'/?apiPluxml&commentaires',commentaires') ;
    //getPlxApiResult(apiPluXmlSite+'/?apiPluxml&etiquette','etiquette') ;

    </script>
    <div id="results"><!-- La liste s'affiche ici --></div>
</code></pre>
