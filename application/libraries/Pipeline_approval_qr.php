<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Isi QR ringkas untuk kamera/QR scanner umum; verifikasi server tetap terpisah. */
class Pipeline_approval_qr
{
    const FORMAT_VERSION=2;
    public static function payload($approval)
    {
        $date=(new DateTimeImmutable($approval['ApprovedAt'],new DateTimeZone('Asia/Jakarta')))
            ->setTimezone(new DateTimeZone('Asia/Jakarta'))->format('d-m-Y H:i:s').' WIB';
        return implode("\n",array('Nama: '.$approval['Approver'],'Tanggal: '.$date,'Dokumen: '.$approval['DocumentNo']));
    }
}
