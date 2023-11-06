var Xlock;

var _post = new Object();
var _post_onload = new Object();
var _ajax_var = new Object();
_post_onload.URL='';

function _ajax_post(action, o){
	url='';
	if(_post['URL']){ //call based
		url=_post['URL'];
	}else{ //else page based
		url=_post_onload['URL'];
	}
	
 	post='';
 	if(action){
		//action works like local
		post+='&_ajax_action=' + action;
	}
	//global
	for (var i in _post_onload) if(i!='URL'){	
	 	value=_post_onload[i];
	 	post+='&'+i+'='+value;
	 }
	//local
	for (var i in _post) if(i!='URL'){




		value=_post[i];
	 	if(typeof value == 'string'){
	 		value=value.replace(/\+/g,'%2B');
	 		//TODO find better replacement...
	 		value=value.replace(/&/g,' et '); //value=value.replace(/&/g,'%26');
	 	}
	 	post+='&'+i+'='+value;
	 }
	_post = new Object();//reset local
	Xajax(url,  post ,'Xfill', o);	
}

function _ajax_post_confirm(action, msg){
	if(msg==undefined)
		msg = "Etes-vous sûr?";
	if (confirm(msg)){
		_ajax_post(action);
	}
}

function _post(attr,val){
	_post[attr]=val;
}

function _post_node(node){
	_post['_node_id']=node.id;
	_post['_node_value']=node.value;
}

function _ajax_post_sync(action, o){
	url='';
	if(_post['URL']){
		url=_post['URL'];
	}else{
		url=_post_onload['URL'];
	}
	
 	post='';
	post+='&_ajax_action=' + action;
	for (var i in _post_onload) if(i!='URL'){	
	 	value=_post_onload[i];
	 	post+='&'+i+'='+value;
	 }
	for (var i in _post) if(i!='URL'){
	 	value=_post[i];
	 	if(typeof value == 'string'){
	 		value=value.replace(/\+/g,'%2B');
	 		value=value.replace(/&/g,' et ');
	 	}
	 	post+='&'+i+'='+value;
	 }
	_post = new Object();
	XajaxSync(url,  post ,'Xfill', o);	
}

function Xajax(url,post,xfunc,hl) {
	XajaxGeneric(url,post,xfunc,hl,true);
}

function XajaxSync(url,post,xfunc,hl) {
	XajaxGeneric(url,post,xfunc,hl,false);
}

function XajaxGeneric(url,post,xfunc,hl,is_asynchronous){
	// h1 No more use....
	document.body.style.cursor = "wait";

	function ajaxObject(){
		if (document.all && !window.opera) obj = new ActiveXObject("Microsoft.XMLHTTP");
		else obj = new XMLHttpRequest();
		return obj;
	}
	var ajaxHttp = ajaxObject();
	ajaxHttp.open('POST', url, is_asynchronous);
	ajaxHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');	
	ajaxHttp.onreadystatechange =
		function(){
			if(ajaxHttp.readyState == 4){
				
				if(xfunc) {
					eval(xfunc+'(\''+escape(ajaxHttp.responseText)+'\');');
				}

				document.body.style.cursor = "auto";
				Xlock = 0;
			}
		}

	ajaxHttp.send(post);
	// If synchronous, there's no readystate modification...
	if (!is_asynchronous) {
		document.body.style.cursor = "auto";
		Xlock = 0;		
	}
	else {
		Xlock = 1;
	}	   
}

function Xfill(html){
	html=unescape(html);
	if(html.indexOf('XDBUG')>0 || html.indexOf("( ! )")>0 || html.indexOf(">Call Stack")>0){
		html='<fieldset style="border:5px solid red;"><legend>Debug AJAX</legend>'+html+'</fieldset>';
		document.body.insertAdjacentHTML('afterbegin', html);
	}
	td=html.split('</xfill>');
	for(var i = 0; i < td.length; i++){
		td[i]=td[i].replace(/^\s+/g,'').replace(/\s+$/g,''); //trim fix
		if(td[i].length>0){
			var o;
			if(td[i].indexOf('<xfill type="inner">')>0){
				o=td[i].split('<xfill type="inner">');
				var element = document.getElementById(o[0]);
				if(element) {
					element.innerHTML = o[1];
				}
			}
			else if(td[i].indexOf('<xfill type="value">')>0){
				o=td[i].split('<xfill type="value">');
				var inputElement = document.getElementById(o[0])
				if(inputElement)
					inputElement.value = o[1];
			}
			else if(td[i].indexOf('<xfill type="set">')>0){
				o=td[i].split('<xfill type="set">');
				if(document.getElementById(o[0])){
					switch(document.getElementById(o[0]).tagName){					
						case 'INPUT':
							document.getElementById(o[0]).value = o[1];
						break;
						case 'SELECT':
							for (var idx=0;idx<document.getElementById(o[0]).options.length;idx++) {
								if (o[1]==document.getElementById(o[0]).options[idx].value) {
									document.getElementById(o[0]).selectedIndex=idx;
									break;
								}
							}
						break;
						default:
							document.getElementById(o[0]).innerHTML = o[1];
						break;
					}
				}		
			}
			else if(td[i].indexOf('<xfill type="altsrc">')>0){
				o=td[i].split('<xfill type="altsrc">');
				if(document.getElementById(o[0])){
					document.getElementById(o[0]).alt = o[1];
					document.getElementById(o[0]).src = o[1];
				}
			}
			// document new location, if empty, refresh location
			else if(td[i].indexOf('<xfill type="location">')>0){
				o=td[i].split('<xfill type="location">');
				if(o[1]=='') location.reload();
				else location.replace(o[1]);
			}
			// DOM append to element by id
			else if (td[i].indexOf('<xfill type="append">') > 0) {
				o = td[i].split('<xfill type="append">');
				var parentElement = document.getElementById(o[0]);
				if (parentElement) {
					parentElement.insertAdjacentHTML('beforeend', o[1]);
				}
			}
			// DOM after to element by id
			else if (td[i].indexOf('<xfill type="after">') > 0) {
				o = td[i].split('<xfill type="after">');
				var targetElement = document.getElementById(o[0]);
				if (targetElement) {
					targetElement.insertAdjacentHTML('afterend', o[1]);
				}
			}
			// DOM prepend to element by id
			else if (td[i].indexOf('<xfill type="prepend">') > 0) {
				o = td[i].split('<xfill type="prepend">');
				var parentElement = document.getElementById(o[0]);
				if (parentElement) {
					parentElement.insertAdjacentHTML('afterbegin', o[1]);
				}
			}
			// DOM replace
			else if (td[i].indexOf('<xfill type="html">') > 0) {
				o = td[i].split('<xfill type="html">');
				var element = document.getElementById(o[0]);
				if (element) {
					element.innerHTML = o[1];
				}
			}
			// DOM remove element by id
			else if (td[i].indexOf('<xfill type="remove">') > 0) {
				o = td[i].split('<xfill type="remove">');
				var element = document.getElementById(o[0]);
				if (element && element.parentNode) {
					element.parentNode.removeChild(element);
				}
			}
			// DOM hide element by id
			else if (td[i].indexOf('<xfill type="hide">') > 0) {
				o = td[i].split('<xfill type="hide">');
				var element = document.getElementById(o[0]);
				if (element) {
					element.style.display = 'none';
				}
			}
			// DOM show element by id
			else if (td[i].indexOf('<xfill type="show">') > 0) {
				o = td[i].split('<xfill type="show">');
				var element = document.getElementById(o[0]);
				if (element) {
					element.style.display = '';
				}
			}
			//trigger alert
			else if(td[i].indexOf('<xfill type="alert">')>0){				
				o=td[i].split('<xfill type="alert">');		
				alert(o[1]);
			}
			// eval
			else if(td[i].indexOf('<xfill type="eval">')>0){
				o=td[i].split('<xfill type="eval">');
				//showSimpleModal(o[1],true);
				eval(o[1]);
			}
			// ajax dbug
			else if(td[i].indexOf('<xfill type="dbug">')!=-1){
				o=td[i].split('<xfill type="dbug">');
				if(document.getElementById('body_ajax_dbug') === null){
					var newDiv = document.createElement("div");
					html='<fieldset id="body_ajax_dbug" class="ajax_debug"><legend id="body_ajax_dbug_handle" ondblclick="this.parentElement.remove();">Debug AJAX</legend></fieldset>';
					newDiv.innerHTML = html;
					var body = document.querySelector("body");
					body.appendChild(newDiv);
				}
				document.getElementById('body_ajax_dbug').insertAdjacentHTML('beforeend', '<div style="margin:5px;border:1px dotted black;padding: 5px;" ondblclick="this.remove();" >'+o[1]+'</div>');
			}
		}
	}
}