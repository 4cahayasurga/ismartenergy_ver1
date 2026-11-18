<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pipeline_finance
{
    public function company_key($name)
    {
        $name=mb_strtolower(preg_replace('/\s+/u',' ',trim($name)),'UTF-8');
        return 'PIPE-'.hash('sha256',$name);
    }
    public function services()
    {
        return array(
            'PASS_PERSON' => array('PAS Pelabuhan Karyawan','Orang',36000),
            'PASS_MOTOR' => array('Kendaraan Roda 2','Unit',53000),
            'PASS_CAR' => array('Kendaraan Roda 4/Lebih','Unit',104000),
            'ELECTRICITY' => array('Listrik','KWH',1835),
            'WATER' => array('Air Tawar (Kantor / Kapal)','M3',21500),
            'ROOM' => array('Sewa Ruangan','M2',45000),
            'LAND' => array('Sewa Tanah','M2',14000),
            'FUEL_ADMIN' => array('Jasa Administrasi / Fasilitas BBM','TON',120000),
            'VAT' => array('PPN (Jasa Administrasi BBM)','%',12),
        );
    }

    public function date_valid($value)
    {
        $d = is_string($value) ? DateTime::createFromFormat('!Y-m-d', $value) : false;
        return $d && $d->format('Y-m-d') === $value;
    }

    public function number($value, $max = 1000000000)
    {
        if (!is_scalar($value) || !preg_match('/^\d+(\.\d{1,6})?$/D', (string) $value) || (float)$value > $max) throw new InvalidArgumentException('Angka tidak valid atau melebihi batas. Gunakan titik untuk desimal.');
        return (float) $value;
    }

    public function totalizers($start, $end)
    {
        $start=$this->number($start,999999999999.999);
        $end=$this->number($end,999999999999.999);
        if (round($start,3)!=$start || round($end,3)!=$end || $end<=$start) throw new InvalidArgumentException('Totalizer akhir harus lebih besar dari awal, maksimal 3 desimal.');
        return array($start,$end);
    }
    public function printable_do($delivery)
    {
        return isset($delivery['TotalizerStart'],$delivery['TotalizerEnd']) && (float)$delivery['TotalizerEnd']>(float)$delivery['TotalizerStart']
            && (!isset($delivery['Quantity']) || round((float)$delivery['TotalizerEnd']-(float)$delivery['TotalizerStart'],3)<=(float)$delivery['Quantity']);
    }

    public function reconcile($delivery, $documents, $requiredTypes=array('DO','SPP','LO'))
    {
        $errors = array();
        foreach (array_unique(array_merge($requiredTypes,array_keys($documents))) as $type) {
            if (empty($documents[$type])) { $errors[] = 'Dokumen ' . $type . ' belum diunggah'; continue; }
            $doc = $documents[$type];
            if (empty($doc['Reviewed'])) $errors[] = $type . ' belum diperiksa terhadap file';
            if (abs((float)$doc['VolumeLiters'] - (float)$delivery['Quantity']) > 0.0005) $errors[] = 'Volume ' . $type . ' berbeda dengan DO';
            if ($this->normalize($doc['VesselName']) !== $this->normalize($delivery['VesselName'])) $errors[] = 'Kapal ' . $type . ' berbeda dengan DO';
        }
        if (isset($documents['DO']) && $this->normalize($documents['DO']['ReferenceNo']) !== $this->normalize($delivery['DONumber'])) $errors[] = 'Nomor dokumen DO berbeda';
        if (isset($documents['DO']) && substr($documents['DO']['DocumentDate'],0,10) !== substr($delivery['DeliveryDate'],0,10)) $errors[] = 'Tanggal dokumen DO berbeda';
        if (isset($documents['SPP'], $documents['LO'])) {
            if ($this->normalize($documents['SPP']['LoadingReference']) !== $this->normalize($documents['LO']['ReferenceNo'])) $errors[] = 'Nomor LO pada SPP tidak cocok';
            if ($documents['LO']['DocumentDate'] > $documents['SPP']['DocumentDate']) $errors[] = 'Tanggal LO sesudah tanggal SPP';
        }
        if (isset($documents['SPP']) && $documents['SPP']['DocumentDate'] > substr($delivery['DeliveryDate'],0,10)) $errors[] = 'Tanggal SPP sesudah tanggal DO';
        return $errors;
    }

    private function normalize($text) { return strtoupper(preg_replace('/\s+/', ' ', trim((string)$text))); }

    public function tons($liters, $mode, $density = null)
    {
        if ($mode === 'LITER_1000') return round($liters / 1000, 6);
        if ($mode !== 'DENSITY' || $density === null || $density <= 0 || $density > 2) throw new InvalidArgumentException('Pilih dasar konversi; densitas SPP wajib untuk konversi massa.');
        return round($liters * $density / 1000, 6);
    }

    /** Menghitung dasar tagihan SPP sesuai satuan tarif aktif. */
    public function spp_quantity($liters, $unit, $density)
    {
        $unit = strtoupper(trim((string) $unit));
        if ($unit === 'LITER') return $liters;
        if ($unit !== 'TON') throw new InvalidArgumentException('Satuan tarif administrasi BBM harus LITER atau TON.');
        if (!is_numeric($density) || $density <= 0 || $density > 2) {
            throw new InvalidArgumentException('Densitas SPP wajib dilengkapi dan diverifikasi sebelum penagihan per TON.');
        }
        $mass = bcdiv(bcmul((string) $liters, (string) $density, 12), '1000', 9);
        return bcdiv(bcadd($mass, '0.0000005', 9), '1', 6);
    }

    public function amount($quantity, $rate)
    {
        $product=bcmul(number_format($quantity,6,'.',''),number_format($rate,4,'.',''),8);
        if (bccomp($product,'1000000000000',2)>0) throw new InvalidArgumentException('Nilai tagihan melebihi batas transaksi.');
        return (float)bcdiv(bcadd($product,'0.005',8),'1',2);
    }

    public function words($amount)
    {
        $n=(int)floor($amount); $words=$this->integer_words($n);
        $cents=(int)round(($amount-$n)*100);
        return ucfirst(trim($words)).($cents?' koma '.trim($this->integer_words($cents)).' sen':'');
    }
    private function integer_words($n)
    {
        $small=array('nol','satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan','sepuluh','sebelas');
        if ($n<12) return $small[$n];
        if ($n<20) return $small[$n-10].' belas';
        if ($n<100) return $this->integer_words((int)floor($n/10)).' puluh'.($n%10?' '.$this->integer_words($n%10):'');
        if ($n<200) return 'seratus'.($n%100?' '.$this->integer_words($n%100):'');
        if ($n<1000) return $small[(int)floor($n/100)].' ratus'.($n%100?' '.$this->integer_words($n%100):'');
        if ($n<2000) return 'seribu'.($n%1000?' '.$this->integer_words($n%1000):'');
        foreach(array(1000000000000=>'triliun',1000000000=>'miliar',1000000=>'juta',1000=>'ribu') as $base=>$label) if ($n>=$base) return $this->integer_words((int)floor($n/$base)).' '.$label.($n%$base?' '.$this->integer_words($n%$base):'');
        return '';
    }

    public function ledger($invoices, $payments, $start, $end)
    {
        $events = array();
        foreach ($invoices as $row) $events[] = array('date'=>substr($row['InvoiceDate'],0,10), 'sort'=>'0-'.sprintf('%012d',$row['InvoiceID']), 'reference'=>$row['InvoiceNumber'], 'debit'=>(float)$row['GrandTotal'], 'credit'=>0, 'notes'=>$row['InvoiceType']==='OPENING'?'Saldo awal':$row['PeriodStart'].' s/d '.$row['PeriodEnd']);
        foreach ($payments as $row) $events[] = array('date'=>substr($row['PaymentDate'],0,10), 'sort'=>'1-'.sprintf('%012d',$row['PaymentID']), 'reference'=>$row['InvoiceNumber'], 'debit'=>0, 'credit'=>(float)$row['Amount'], 'notes'=>$row['ReferenceNo'].' '.$row['Notes']);
        usort($events,function($a,$b){ return strcmp($a['date'].$a['sort'],$b['date'].$b['sort']); });
        $balance=0; $opening=0; $rows=array(); $debit=0; $credit=0;
        foreach ($events as $event) {
            if ($event['date'] > $end) continue;
            $balance=round($balance+$event['debit']-$event['credit'],2);
            if ($event['date'] < $start) { $opening=$balance; continue; }
            $event['balance']=$balance; $rows[]=$event; $debit+=$event['debit']; $credit+=$event['credit'];
        }
        return array('opening'=>$opening,'rows'=>$rows,'debit'=>$debit,'credit'=>$credit,'closing'=>$balance);
    }
}
