#!/usr/bin/env python3
"""Local portable SPP OCR. No shell commands or cloud uploads."""
import argparse,csv,io,json,os,shutil,subprocess,tempfile
from pathlib import Path

def run(args,timeout=18):
    return subprocess.run(args,stdout=subprocess.PIPE,stderr=subprocess.PIPE,timeout=timeout,check=True,env=dict(os.environ,OMP_THREAD_LIMIT='1')).stdout

def read(path):
    from PIL import Image,ImageOps,ImageFilter
    Image.MAX_IMAGE_PIXELS=24000000
    tess=shutil.which('tesseract')
    if not tess: raise RuntimeError('Tesseract belum terpasang')
    candidates=[];warnings=[];page_count=1
    with tempfile.TemporaryDirectory(prefix='spp_ocr_') as tmp:
        source=Path(path)
        if source.open('rb').read(5)==b'%PDF-':
            page_count=None
            info_tool=shutil.which('pdfinfo')
            if info_tool:
                import re
                info=run([info_tool,str(source)],10).decode('utf-8','replace')
                found=re.search(r'^Pages:\s*(\d+)',info,re.MULTILINE)
                if found:page_count=int(found.group(1))
            tool=shutil.which('pdftoppm');texttool=shutil.which('pdftotext')
            if not tool: raise RuntimeError('Poppler belum terpasang')
            if texttool:
                try:
                    text=run([texttool,'-f','1','-l','1','-layout',str(source),'-'],12).decode('utf-8','replace')[:60000]
                    if len(text.strip())>40:candidates.append(dict(text=text,words=[]))
                except (subprocess.SubprocessError,OSError):pass
            run([tool,'-f','1','-l','1','-singlefile','-scale-to','2600','-png',str(source),str(Path(tmp)/'page')],18)
            source=Path(tmp)/'page.png'
            warnings.append('PDF: Auto Fill membaca halaman pertama; unggah setiap SPP secara terpisah.')
        with Image.open(source) as original:
            original.load();image=ImageOps.exif_transpose(original).convert('RGB')
        ratio=min(3.0,2600/max(image.size));image=image.resize((max(1,round(image.width*ratio)),max(1,round(image.height*ratio))))
        gray=ImageOps.autocontrast(ImageOps.grayscale(image),cutoff=1).filter(ImageFilter.UnsharpMask(radius=1.2,percent=140,threshold=3))
        languages=run([tess,'--list-langs'],5).decode('utf-8','replace').splitlines()
        lang='+'.join(x for x in ['eng','ind'] if x in languages)
        if not lang:raise RuntimeError('Data bahasa OCR eng/ind belum terpasang')
        for n,(variant,psm) in enumerate([(gray,6),(gray.point(lambda x:255 if x>175 else 0),4)]):
            filename=str(Path(tmp)/('pass%d.png'%n));variant.save(filename)
            try:
                tsv=run([tess,filename,'stdout','-l',lang,'--psm',str(psm),'tsv'],20).decode('utf-8','replace')
                words=[]
                for row in csv.DictReader(io.StringIO(tsv),delimiter='\t'):
                    text=(row.get('text') or '').strip()
                    if text and float(row.get('conf',-1))>=15:words.append(dict(text=text,x=int(row['left']),y=int(row['top']),width=int(row['width']),height=int(row['height'])))
                if words:candidates.append(dict(text=' '.join(w['text'] for w in words),words=words))
            except subprocess.TimeoutExpired:warnings.append('Salah satu tahap OCR melewati batas waktu; periksa hasil.')
        if not candidates:raise RuntimeError('Dokumen belum terbaca; gunakan scan yang lebih jelas')
        return dict(ok=True,candidates=candidates,warnings=warnings,pdfPages=page_count)

if __name__=='__main__':
    parser=argparse.ArgumentParser();parser.add_argument('--input',required=True);args=parser.parse_args()
    try:print(json.dumps(read(args.input),ensure_ascii=True))
    except Exception as exc:print(json.dumps(dict(ok=False,error=str(exc)[:200])));raise SystemExit(1)
