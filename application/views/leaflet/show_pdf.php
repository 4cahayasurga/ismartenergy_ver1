 <style>
.pdfobject-container { height: 50rem; border: 1rem solid rgba(0,0,0,.1); }
</style>
<div id="example1"></div>

<script src="<?=base_url('./assets/pdfobject/pdfobject.js')?>"></script>
<script>PDFObject.embed("<?=base_url($file)?>", "#example1");</script>

