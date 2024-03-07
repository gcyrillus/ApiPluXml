/* fetch API datas */
let s = ''; 
if(ProtocolHTTP != 'http') s='s';
function getPlxApiResult(u,q) {
	fetch('http'+s+'://'+u,{
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
				if(datas[`${key}`]['active'] == '1') tpl +='<li><a hre'+'f="http'+s+'://'+apiPluXmlSite+'static'+ num +'/'+ datas[`${key}`]['url']+'">'+ datas[`${key}`]['name']+'</a></li>';
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
			if(apibypage != Number(apibypage) || apibypage <1 ) bypage = datas.i[0]['bypage']; else bypage = apibypage;
			if(datas.i[0]['page_number'] != undefined) page_number = datas.i[0]['page_number']
			let pages = Math.ceil(datas.size / bypage);
			let next='';
			let previous ='';
			let first = (Number(page_number) * Number(bypage)) - Number(bypage) + 1;
			let last = Number(bypage) * Number(page_number) + Number(bypage)  - Number(bypage) + 1;
            		let nextPage= ++datas.i[0]['page_number'];
            		let nextlast = last + Number(bypage);
            		let whereAt='';
			let datart = Object.entries(datas.result)
            		if(pages>1) whereAt =' <span class="apiPageAt">Page <b>'+page_number +'</b> / '+pages+' </span>&nbsp;';
            		if(page_number > 1) previous='<button onclick="getPlxApiResult(\''+apiPluXmlSite+'apiPluxml&article&bypage='+bypage+'&page_number='+ --page_number + '\',\'article\');return false;">previous</button>';
            		if(last  <= datart.length) next = '<button onclick="getPlxApiResult(\''+apiPluXmlSite+'apiPluxml&article&bypage='+bypage+'&page_number='+ nextPage + '\',\'article\');return false;">next</button>';
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
	artTags = art.tags.replace(/\,$/, "").replace(/\s/g, "").split(",");
	tags= getTags('',artTags);
	artCats = art.categorie.replace(/\,$/, "").replace(/\s/g, "").split(",");
	cats=getCategories('',artCats,rubricks);
	datef = getDate(art.date);
	let thumb='';
	let thumbTitle='';
	let thumbAlt='';
	if(art.thumbnail_title !== '') {thumbTitle=' title="'+art.thumbnail_title.replaceAll('\'', '')+'"';}
	if(art.thumbnail_alt !== '') {  thumbAlt=' alt="'+art.thumbnail_alt.replaceAll('\'', '')+'"';}
	if(art.thumbnail !== '') {thumb='<img sr'+'c="http'+s+'://'+apiPluXmlSite.replace(/\?$/, '')+art.thumbnail+'" '+thumbTitle+thumbAlt+'>'}
	if(artcontent ==false ){ art.content = `<p style="clear:both">Lire la suite de: <a href="http${s}://${apiPluXmlSite}article${art.numero.replace(/^0+/, '')}/${art.url}">${art.title}</a></p> <hr>`;}
	else {art.content ='<div>'+art.content+'</div>';}
	articles =`
	<article class="articleApilList">
		<h2><a href="http${s}://${apiPluXmlSite}article${art.numero.replace(/^0+/, '')}/${art['url']}">${art.title}</a></h2>
		<p>Ecrit le ${datef} par: <b>${authors[art.author]['name']}</b> | Catégorie(s): ${cats} |Etiquette(s): ${tags}</p>
		<div>${thumb}
		${art.chapo}
  		</div>
		${art.content}
	</article>
	`;
	return articles;//show!	
}
function getTags(tags,tagada) {    
	tagada.forEach(tag => tags +='<a hre'+'f="http'+s+'://'+apiPluXmlSite+'tag/'+ tag.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase() +'" target="_blank">'+ tag +'</a> ');
	return tags;                
}
function getCategories(cats,catagada,rubricks) {  // extraction de chaque etiquettes  
	catagada.forEach(cat => cats +='<a hre'+'f="http'+s+'://'+apiPluXmlSite+'categorie'+ cat.replace(/^0+/, '')+'/'+rubricks[cat].url+'" target="_blank">'+ rubricks[cat].name +'</a> ');
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
	rubricks = await fetch("http"+s+"://" + apiPluXmlSite + "apiPluxml&categorie", {
		method: "GET",
		headers: { apiKey: apiKey }
	})
	.then((response) => response.json())
	.then((json) => {
		try {
			Object.entries(json).forEach((entry) => {
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
	authors = await fetch("http"+s+"://" + apiPluXmlSite + "apiPluxml&authors", {
		method: "GET",
		headers: { apiKey: apiKey }
	})
	.then((response) =>  response.json() )
	.then((json) => {

		try {
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
