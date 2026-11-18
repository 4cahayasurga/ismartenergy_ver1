(function(){'use strict';
function start(){
 var input=document.getElementById('v11-menu-search'),nav=document.querySelector('.pipeline-nav .sidebar-menu');
 if(input&&nav){
  var items=Array.prototype.slice.call(nav.children),note=document.createElement('div');note.className='v11-search-note';note.setAttribute('role','status');note.hidden=true;input.parentNode.appendChild(note);
  input.addEventListener('input',function(){var q=input.value.trim().toLocaleLowerCase('id'),count=0;
   items.forEach(function(item){var children=item.querySelectorAll('.treeview-menu>li'),visible=0;
    if(children.length){Array.prototype.forEach.call(children,function(child){var match=!q||child.textContent.toLocaleLowerCase('id').indexOf(q)>=0;child.hidden=!match;if(match)visible++;});item.hidden=visible===0;
     var list=item.querySelector('.treeview-menu');list.style.display=q?(visible?'block':'none'):(item.classList.contains('active')?'block':'');
    }else{item.hidden=!!q&&item.textContent.toLocaleLowerCase('id').indexOf(q)<0;}
    if(!item.hidden)count++;
   });note.hidden=count>0;note.textContent='Menu tidak ditemukan.';
  });
  document.addEventListener('keydown',function(e){if(e.key==='/'&&!/input|textarea|select/i.test(document.activeElement.tagName)){e.preventDefault();input.focus();}if(e.key==='Escape'){input.value='';input.dispatchEvent(new Event('input'));input.blur();document.body.classList.remove('sidebar-open');}});
 }
 var wrap=document.querySelector('.ui-modern .content-wrapper');
 if(wrap){var active=document.querySelector('.pipeline-nav [aria-current="page"]')||document.querySelector('.pipeline-nav li.active>a'),crumb=document.createElement('nav');crumb.className='v11-crumb';crumb.setAttribute('aria-label','Lokasi halaman');var home=document.createElement('span');home.textContent='Ruang kerja';crumb.appendChild(home);var separator=document.createElement('span');separator.textContent='/';separator.setAttribute('aria-hidden','true');crumb.appendChild(separator);var title=document.createElement('span');title.textContent=active?active.textContent.trim():document.title.split('|').pop().trim();crumb.appendChild(title);wrap.insertBefore(crumb,wrap.firstChild);
  Array.prototype.forEach.call(wrap.querySelectorAll('table'),function(table){if(table.closest('.table-responsive')||table.closest('.modal')||table.closest('.alert'))return;var box=document.createElement('div');box.className='table-responsive';table.parentNode.insertBefore(box,table);box.appendChild(table);});
 }
 var toggle=document.querySelector('.sidebar-toggle');if(toggle){toggle.setAttribute('aria-label','Buka atau tutup navigasi');toggle.setAttribute('role','button');}
 document.addEventListener('click',function(e){if(window.innerWidth<768&&document.body.classList.contains('sidebar-open')&&!e.target.closest('.main-sidebar,.sidebar-toggle'))document.body.classList.remove('sidebar-open');});
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',start);else start();
})();
