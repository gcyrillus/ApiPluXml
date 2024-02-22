/*/Config/*/
const apiKey = 'apiPluXml';
const ProtocolHTTP = 'https';/* anything or http */
const apiPluXmlSite = 'pluxopolis.net/crashnewstest';/* pluxml site domain name  where to fetch datas example: [pluxopolis.net/crashnewstest] (without brackets)   */
const apibypage=''; /* default value*/  
let artcontent= false ; /* pour voir tout l'article : mettre a  true */
/*/End Config/*/

/* fetch datas */
let s = ''; 
if(ProtocolHTTP != 'http') s='s';
function getPlxApiResult(u,q) {
	fetch('//'+u,{
		method: 'GET',
		headers:{'apiKey': apiKey
		}
	})
	.then(response => response.text()) // Parse the response as text
	.then(async text => {
		try {
			const data = JSON.parse(text); // Try to parse the response as JSON
			if(q == 'article') {
				// data.result.unshift(data.result[0]);
				let rubricks = [];
				await getCatNames(rubricks)
                let authors = [];
                await getAuthors(authors);
                show(data,'json',q, rubricks, authors);
                } else {
                show(data,'json',q);                
			}
			} catch(err) {
			show(text,'html',q);
		}
	});  
}


/* show what we got */
function show(datas,type,q, rubricks='', authors='') {
	const res = document.querySelector("#results");        
	if(type=='html') {
		res.insertAdjacentHTML( 'afterend',datas);
	}
	if (type=='json'){
		
		if(q =='static' || q =='categorie') {
			let tpl='<p>Pages: <b>'+q+'</b> de <b>'+ apiPluXmlSite +'</b></p><ul>';
			Object.entries(datas).forEach((entry) => {
				const [key, value] = entry;
				let num= entry[0].replace(/^0+/, '');
				if(datas[`${key}`]['active'] == '1') tpl +='<li><a hre'+'f="http'+s+'://'+apiPluXmlSite+'/?static'+ num +'/'+ datas[`${key}`]['url']+'">'+ datas[`${key}`]['name']+'</a></li>';
			});
			tpl +='</ul>';
			res.insertAdjacentHTML( 'afterbegin', tpl);
		}
		
		if(q =='etiquette') { 
			let string ='';
			Object.entries(datas).forEach((entry) => {
				const [key, value] = entry;
				if(datas[`${key}`]['active'] == '1') string+= datas[`${key}`]['tags']+',';
			});
			
			const myTags = string.replace(/\,$/, "").replace(/\s/g, "").split(",");
			let iterate = [...new Set(myTags)];
			let tagList='<p><b>'+q+'s</b> de <b>'+ apiPluXmlSite +'</b></p><nav class="tagApiList">';
			let tags='';
			tagList += getTags(tags,iterate);
			tagList +='</nav><style>.tagApiList{display:flex;flex-wrap:wrap;gap:.5em}.tagApiList a{display:block;border:1px solid;padding-inline:.5em;border-radius:5px}</style>';
			res.insertAdjacentHTML( 'afterbegin', tagList);
		}
		
		
		if(q =='article') { 
			let page_number = '1';
			if(apibypage != Number(apibypage) || apibypage <1 ) bypage = datas.bypage; else bypage = apibypage;
			if(datas.page_number != undefined) page_number = datas.page_number
			let pages = Math.ceil(datas.result.length / bypage);
			let next='';
			let previous ='';
			let first = (Number(page_number) * Number(bypage)) - Number(bypage) + 1;
			let last = Number(bypage) * Number(page_number) + Number(bypage)  - Number(bypage) + 1;
            let nextPage= ++datas.page_number;
            let nextlast = last + Number(bypage);
            let whereAt='';
            if(pages>1) whereAt =' <span class="apiPageAt">Page <b>'+page_number +'</b> / '+pages+' </span>&nbsp;';
            if(page_number > 1) previous='<button onclick="getPlxApiResult(\''+apiPluXmlSite+'/?apiPluxml&article&bypage='+bypage+'&page_number='+ --page_number + '\',\'article\');return false;">previous</button>';
            if(last < datas.result.length) next = '<button onclick="getPlxApiResult(\''+apiPluXmlSite+'/?apiPluxml&article&bypage='+bypage+'&page_number='+ nextPage + '\',\'article\');return false;">next</button>';
            let datart = Object.entries(datas.result)
            datart =  datart.slice(--first,--last);
            datart.reverse()
			let articles ='';
            res.innerHTML='';
            res.insertAdjacentHTML( 'beforeend', '<nav class="apiNav">'+previous+' '+whereAt+' '+ next +'</nav>');
			datart.forEach(function(art,articles){res.insertAdjacentHTML( 'afterbegin',getArticles(articles,art['1'],rubricks,authors))})
		}
	}
}
function getArticles(articles,art,rubricks,authors) {
    console.log('sub-index: ' +articles)
	artTags = art.tags.replace(/\,$/, "").replace(/\s/g, "").split(",");
	tags= getTags('',artTags);
	artCats = art.categorie.replace(/\,$/, "").replace(/\s/g, "").split(",");
	cats=getCategories('',artCats,rubricks);
	datef = getDate(art.date);
	if(artcontent ==false ){ art.content = `Lire la suite de: <a href="http${s}://${apiPluXmlSite}/?article${art.numero}/${art.url}">${art.title}</a> <hr>`;}
	else {art.content ='<div>'+art.content+'</div>';}
	articles =`
	<article class="articleApilList">
		<h2><a href="http${s}://${apiPluXmlSite}/?article${art.numero}/${art['url']}">${art.title}</a></h2>
		<p>Ecrit le ${datef} par: <b>${authors[art.author]['name']}</b> | Catégorie: ${cats} |Etiquette: ${tags}</p>
		<div>${art.chapo}</div>
		${art.content}
	</article>
	`;
	return articles;//show!
	
	
}
function getTags(tags,tagada) {    
	tagada.forEach(tag => tags +='<a hre'+'f="http'+s+'://'+apiPluXmlSite+'/?tag/'+ tag.normalize("NFD").replace(/[\u0300-\u036f]/g, "") +'" target="_blank">'+ tag +'</a> ');
	return tags;                
}
function getCategories(cats,catagada,rubricks) {  // extraction de chaque etiquettes  
	catagada.forEach(cat => cats +='<a hre'+'f="http'+s+'://'+apiPluXmlSite+'/?categorie'+ cat+'/'+rubricks[cat].url+'" target="_blank">'+ rubricks[cat].name +'</a> ');
	return cats;                
}
function getDate(artdate) {// deconstruction de la chaine en date lisible
	const year = artdate.slice(0, 4);
	const month = artdate.slice(4, 6);
	const day = artdate.slice(6, 8);
	let datef =  day+  '-'+ month+ '-' +year ;
	return datef;
}

async function getCatNames(rubricks) {
    // faut retrouver la correspondance du numéro donc second request et on attend
	rubricks = await fetch("//" + apiPluXmlSite + "/?apiPluxml&categorie", {
		method: "GET",
		headers: { apiKey: apiKey }
	})
	.then((response) => response.json())
	.then((json) => {
		try {
			Object.entries(json).forEach((entry) => {
				//rubricks[num].cat.name = cat.url;
				const [key, value] = entry;
				rubricks[key] ={}
                rubricks[key].name = value.name;
                rubricks[key].url = value.url;
			});
			
			} catch (err) {
			console.log("rub error");
		}
		
		return rubricks;
	});
	
}

async function getAuthors(authors) {
    // faut retrouver la correspondance du numéro donc second request et on attend
	authors = await fetch("//" + apiPluXmlSite + "/?apiPluxml&authors", {
		method: "GET",
		headers: { apiKey: apiKey }
	})
	.then((response) => response.json())
	.then((json) => {
		try {
			//console.log(json['001'].name );
			 Object.entries(json).forEach((entry) => {
             const [key, value] = entry;
             authors[key]={}
             authors[key].name= value.name;
             authors[key].infos = value.infos;
             });

			
			} catch (err) {
			console.log("fetch author error");
		}
		
		return authors;
	});	
}		
/* sources 
	https://stackoverflow.com/questions/990904/remove-accents-diacritics-in-a-string-in-javascript
	https://stackoverflow.com/questions/12248854/javascript-remove-last-character-if-a-colon & https://stackoverflow.com/questions/10800355/remove-whitespaces-inside-a-string-in-javascript
	https://stackoverflow.com/questions/1960473/get-all-unique-values-in-a-javascript-array-remove-duplicates  // let unique = [...new Set(myArray)];
	... forum.pluxml.prg, alsacréations.com, pluxopolis.net, php.net, mdn, ....
*/					              