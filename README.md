# ApiPluXml
Ebauche d'une API pour le CMS PluXml

Extraction de données depuis PluXml renvoyées au format json

<h2>Exemple</h2>
<p><b>Affiche une liste de lien des pages statiques d'un site PluXml distant.</b></p>
<h3>Code à inserer dans le corps HTML de votre page</h3>
<pre><code>&lt;script>
    const apiKey = 'apiPluXmls';
    const apiPluXmlSite = 'pluxopolis.net/crashnewstest';
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
                tpl +='<li><a href="https://'+apiPluXmlSite+'/?static'+ num +'/'+ datas[`${key}`]['name']+'">'+ datas[`${key}`]['name']+'</a></li>';
                console.log(datas[`${key}`]['name'])
            });
            tpl +='</ul>';
            res.insertAdjacentHTML( 'afterbegin', tpl);
          }
          // ici tester les autre valeurs de q et creer le template que vous souhaitez,
          // TIP :  Un objet json est retourné, regarder dans la console de votre navigateur pour y
          // voir sa structure et les infos exploitables.
          //  d'autres exemples viendront , vous pouvez proposer les vôtres :)
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
