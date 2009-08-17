// see license.txt for licensing
function kfm_runSearch(){
	kfm_run_delayed('search',kfm_runSearch2);
}
function kfm_runSearch2(){
	var keywords='',tags='';
	var kEl=$("kfm_search_keywords"),tEl=$("kfm_search_tags");
	if(kEl)keywords=kEl.value;
	if(tEl)tags=tEl.value;
	if(keywords==""&&tags=="")x_kfm_loadFiles(kfm_cwd_id,kfm_refreshFiles);
	else x_kfm_search(keywords,tags,kfm_refreshFiles)
}
function kfm_searchBoxFile(){
	return new Element('input',{
		'id':'kfm_search_keywords',
		'events':{
			'keyup':kfm_runSearch
		}
	});
}
