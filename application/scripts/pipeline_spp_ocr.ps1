param([Parameter(Mandatory=$true)][string]$ImagePath, [int]$Scale = 2)
$ErrorActionPreference = 'Stop'
[Console]::OutputEncoding = New-Object System.Text.UTF8Encoding($false)
Add-Type -AssemblyName System.Runtime.WindowsRuntime
function Await-Result($operation, [Type]$resultType) {
    $method = [System.WindowsRuntimeSystemExtensions].GetMethods() | Where-Object { $_.Name -eq 'AsTask' -and $_.IsGenericMethod -and $_.GetParameters().Count -eq 1 } | Select-Object -First 1
    $task = $method.MakeGenericMethod($resultType).Invoke($null, @($operation)); $task.Wait(); return $task.Result
}
$stream=$null; $bitmap=$null
try {
    $fileType=[Windows.Storage.StorageFile, Windows.Storage, ContentType=WindowsRuntime]
    $file=Await-Result ($fileType::GetFileFromPathAsync([IO.Path]::GetFullPath($ImagePath))) $fileType
    # Detect content rather than the temporary file extension.
    $probe=[IO.File]::OpenRead($ImagePath)
    try{$header=New-Object byte[] 5;[void]$probe.Read($header,0,5)}finally{$probe.Dispose()}
    $isPdf=([Text.Encoding]::ASCII.GetString($header) -eq '%PDF-')
    $pageCount=1
    if($isPdf){
        $pdfType=[Windows.Data.Pdf.PdfDocument, Windows.Data.Pdf, ContentType=WindowsRuntime]
        $pdf=Await-Result ($pdfType::LoadFromFileAsync($file)) $pdfType
        if($pdf.PageCount -lt 1 -or $pdf.PageCount -gt 100){throw 'Invalid PDF page count'}
        $pageCount=[int]$pdf.PageCount;$page=$pdf.GetPage(0)
        try{
            $stream=New-Object Windows.Storage.Streams.InMemoryRandomAccessStream
            $options=New-Object Windows.Data.Pdf.PdfPageRenderOptions
            $options.DestinationWidth=[uint32]([Math]::Max(1,2600*$page.Size.Width/[Math]::Max($page.Size.Width,$page.Size.Height)))
            $options.DestinationHeight=[uint32]([Math]::Max(1,2600*$page.Size.Height/[Math]::Max($page.Size.Width,$page.Size.Height)))
            $action=$page.RenderToStreamAsync($stream,$options)
            $asTask=[System.WindowsRuntimeSystemExtensions].GetMethods() | Where-Object {$_.Name -eq 'AsTask' -and !$_.IsGenericMethod -and $_.GetParameters().Count -eq 1} | Select-Object -First 1; $task=$asTask.Invoke($null,@($action));$task.Wait();$stream.Seek(0)
        }finally{$page.Dispose()}
    }else{
    $stream=Await-Result ($file.OpenAsync([Windows.Storage.FileAccessMode]::Read)) ([Windows.Storage.Streams.IRandomAccessStream, Windows.Storage.Streams, ContentType=WindowsRuntime])
    }
    $decoder=Await-Result ([Windows.Graphics.Imaging.BitmapDecoder, Windows.Graphics.Imaging, ContentType=WindowsRuntime]::CreateAsync($stream)) ([Windows.Graphics.Imaging.BitmapDecoder, Windows.Graphics.Imaging, ContentType=WindowsRuntime])
    $transform=New-Object Windows.Graphics.Imaging.BitmapTransform
    $ratio=[Math]::Min([double]$Scale,2600.0/[Math]::Max($decoder.PixelWidth,$decoder.PixelHeight))
    $transform.ScaledWidth=[uint32]([Math]::Max(1,$decoder.PixelWidth*$ratio)); $transform.ScaledHeight=[uint32]([Math]::Max(1,$decoder.PixelHeight*$ratio))
    $bitmap=Await-Result ($decoder.GetSoftwareBitmapAsync([Windows.Graphics.Imaging.BitmapPixelFormat]::Bgra8,[Windows.Graphics.Imaging.BitmapAlphaMode]::Premultiplied,$transform,[Windows.Graphics.Imaging.ExifOrientationMode]::RespectExifOrientation,[Windows.Graphics.Imaging.ColorManagementMode]::DoNotColorManage)) ([Windows.Graphics.Imaging.SoftwareBitmap, Windows.Graphics.Imaging, ContentType=WindowsRuntime])
    $engine=[Windows.Media.Ocr.OcrEngine, Windows.Foundation, ContentType=WindowsRuntime]::TryCreateFromUserProfileLanguages()
    if ($null -eq $engine) { throw 'OCR_LANGUAGE_UNAVAILABLE' }
    $result=Await-Result ($engine.RecognizeAsync($bitmap)) ([Windows.Media.Ocr.OcrResult, Windows.Foundation, ContentType=WindowsRuntime])
    $words=@();foreach($line in $result.Lines){foreach($word in $line.Words){$r=$word.BoundingRect;$words+=@{text=$word.Text;x=$r.X;y=$r.Y;width=$r.Width;height=$r.Height}}}
    # Read overlapping halves at a higher effective resolution. Select each word
    # from the half containing its centre, then recover words visible only in the full page.
    $tiledWords=@();$tileBitmap=$null
    try {
        $tileRatio=[Math]::Min(2.0,2600.0/[Math]::Max($decoder.PixelWidth,$decoder.PixelHeight*0.60))
        $scaledWidth=[uint32][Math]::Max(1,[Math]::Floor($decoder.PixelWidth*$tileRatio))
        $scaledHeight=[uint32][Math]::Max(1,[Math]::Floor($decoder.PixelHeight*$tileRatio))
        $middle=$scaledHeight/2.0
        foreach($half in @(0,1)) {
            $top=if($half -eq 0){0}else{[int][Math]::Floor($scaledHeight*0.40)}
            $bottom=if($half -eq 0){[int][Math]::Floor($scaledHeight*0.60)}else{[int]$scaledHeight}
            $part=New-Object Windows.Graphics.Imaging.BitmapTransform
            $part.ScaledWidth=$scaledWidth;$part.ScaledHeight=$scaledHeight
            $bounds=New-Object Windows.Graphics.Imaging.BitmapBounds
            $bounds.X=0;$bounds.Y=[uint32]$top;$bounds.Width=$scaledWidth;$bounds.Height=[uint32]($bottom-$top)
            $part.Bounds=$bounds
            $tileBitmap=Await-Result ($decoder.GetSoftwareBitmapAsync([Windows.Graphics.Imaging.BitmapPixelFormat]::Bgra8,[Windows.Graphics.Imaging.BitmapAlphaMode]::Premultiplied,$part,[Windows.Graphics.Imaging.ExifOrientationMode]::RespectExifOrientation,[Windows.Graphics.Imaging.ColorManagementMode]::DoNotColorManage)) ([Windows.Graphics.Imaging.SoftwareBitmap, Windows.Graphics.Imaging, ContentType=WindowsRuntime])
            $tileResult=Await-Result ($engine.RecognizeAsync($tileBitmap)) ([Windows.Media.Ocr.OcrResult, Windows.Foundation, ContentType=WindowsRuntime])
            $factor=$ratio/$tileRatio
            foreach($line in $tileResult.Lines){foreach($word in $line.Words){$r=$word.BoundingRect;$centre=$top+$r.Y+$r.Height/2.0
                if(($half -eq 0 -and $centre -lt $middle) -or ($half -eq 1 -and $centre -ge $middle)){
                    $tiledWords+=@{text=$word.Text;x=$r.X*$factor;y=($r.Y+$top)*$factor;width=$r.Width*$factor;height=$r.Height*$factor}
                }
            }}
            $tileBitmap.Dispose();$tileBitmap=$null
        }
        if($tiledWords.Count -gt 0){
            foreach($w in $words){$near=$false;foreach($tw in $tiledWords){if([Math]::Abs(($tw.y+$tw.height/2)-($w.y+$w.height/2)) -lt [Math]::Max(8,$w.height*0.6) -and [Math]::Abs(($tw.x+$tw.width/2)-($w.x+$w.width/2)) -lt [Math]::Max(12,($tw.width+$w.width)*0.45)){$near=$true;break}};if(!$near){$tiledWords+=,$w}}
            $words=$tiledWords
        }
    } catch { # The full-page result remains usable when a crop cannot be decoded.
    } finally {if($null -ne $tileBitmap){$tileBitmap.Dispose()}}
    @{ok=$true;text=$result.Text;words=$words;width=$bitmap.PixelWidth;height=$bitmap.PixelHeight;pdfPages=$pageCount} | ConvertTo-Json -Depth 5 -Compress
} catch { @{ok=$false;error='OCR tidak dapat membaca foto. Gunakan JPG/PNG/PDF yang jelas (PDF tidak terkunci) dan periksa ketersediaan bahasa OCR Windows.'} | ConvertTo-Json -Compress; exit 1 }
finally { if($null -ne $bitmap){$bitmap.Dispose()};if($null -ne $stream){$stream.Dispose()} }

