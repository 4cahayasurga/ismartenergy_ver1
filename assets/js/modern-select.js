(function($){'use strict';
 if(!$||!$.fn.select2)return;
 function match(params,data){
  var option=data.element;if(option&&(option.hidden||option.disabled||(option.parentElement&&option.parentElement.disabled)))return null;
  if(data.children){var copy=$.extend({},data);copy.children=data.children.map(function(child){return match(params,child);}).filter(Boolean);return copy.children.length?copy:null;}
  var term=(params.term||'').trim().toLocaleLowerCase();return !term||String(data.text||'').toLocaleLowerCase().indexOf(term)!==-1?data:null;
 }
 function enhance(select){
  if(select.dataset.searchEnhanced||select.dataset.nativeSelect==='true')return;
  var $select=$(select);select.dataset.searchEnhanced='1';
  if(!$select.data('select2')){
   var modal=$select.closest('.modal');
   $select.select2({width:'100%',minimumResultsForSearch:0,dropdownParent:modal.length?modal:$(document.body),matcher:match,language:{noResults:function(){return 'Tidak ada pilihan yang sesuai';},searching:function(){return 'Mencari…';}}});
   // Select2 emits jQuery change; notify the native listeners used by area/vessel filters too.
   $select.on('select2:select.modernSearch select2:unselect.modernSearch select2:clear.modernSearch',function(){var inline=select.onchange;select.onchange=null;try{select.dispatchEvent(new Event('change',{bubbles:true}));}finally{select.onchange=inline;}});
  }else{$select.data('select2').options.set('minimumResultsForSearch',0);}
  $select.on('select2:open.modernSearch',function(){setTimeout(function(){var search=document.querySelector('.select2-container--open .select2-search__field');if(search){search.placeholder='Ketik untuk mencari…';search.setAttribute('aria-label','Cari pilihan');search.focus();}},0);});
  select.addEventListener('invalid',function(){var widget=$select.next('.select2').find('.select2-selection');widget.attr('aria-invalid','true');widget.trigger('focus');});
  select.addEventListener('change',function(){$select.next('.select2').find('.select2-selection').removeAttr('aria-invalid');});
 }
 function scan(root){if(root.matches&&root.matches('select'))enhance(root);if(root.querySelectorAll)root.querySelectorAll('select').forEach(enhance);}
 $(function(){scan(document);var pending=new Set(),queued=false;var observer=new MutationObserver(function(records){records.forEach(function(record){if(record.type==='childList')record.addedNodes.forEach(function(n){if(n.nodeType===1&&!n.closest('.select2-container'))scan(n);});var el=record.target,select=el.tagName==='SELECT'?el:(el.closest?el.closest('select'):null);if(select)pending.add(select);});if(pending.size&&!queued){queued=true;queueMicrotask(function(){queued=false;pending.forEach(function(s){if($(s).data('select2'))$(s).trigger('change.select2');});pending.clear();});}});observer.observe(document.body,{subtree:true,childList:true,attributes:true,attributeFilter:['disabled','hidden','selected']});document.addEventListener('reset',function(event){setTimeout(function(){$(event.target).find('select').trigger('change.select2');},0);});});
})(window.jQuery);
