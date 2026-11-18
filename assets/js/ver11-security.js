(function(){'use strict';
var token=document.querySelector('meta[name="ismart-csrf-token"]'),field=document.querySelector('meta[name="ismart-csrf-name"]');if(!token||!field)return;
function same(url){try{return new URL(url,location.href).origin===location.origin;}catch(e){return false;}}
function add(form){if(!same(form.action||location.href)||form.method.toLowerCase()!=='post')return;var input=form.elements.namedItem(field.content);if(!input){input=document.createElement('input');input.type='hidden';input.name=field.content;form.appendChild(input);}if(input.tagName)input.value=token.content;}
document.addEventListener('submit',function(e){if(e.target.tagName==='FORM')add(e.target);},true);
var open=XMLHttpRequest.prototype.open,send=XMLHttpRequest.prototype.send;
XMLHttpRequest.prototype.open=function(method,url){this._ismartCsrf=String(method).toUpperCase()==='POST'&&same(url);return open.apply(this,arguments);};
XMLHttpRequest.prototype.send=function(){if(this._ismartCsrf)this.setRequestHeader('X-CSRF-Token',token.content);return send.apply(this,arguments);};
if(window.fetch){var fetch=window.fetch;window.fetch=function(resource,init){var request=resource instanceof Request,method=(init&&init.method)||(request?resource.method:'GET'),url=request?resource.url:resource;
if(String(method).toUpperCase()==='POST'&&same(url)){init=Object.assign({},init);var headers=new Headers(init.headers||(request?resource.headers:undefined));headers.set('X-CSRF-Token',token.content);init.headers=headers;}return fetch.call(this,resource,init);};}
document.addEventListener('click',function(e){var link=e.target.closest('a[href]');if(!link||!same(link.href)||!new URL(link.href).pathname.match(/\/Auth\/logout\/?$/i))return;e.preventDefault();var form=document.createElement('form');form.method='post';form.action=link.href;document.body.appendChild(form);add(form);form.submit();},true);
})();
