<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_PelamarPosting extends CI_Model
{
    public function getAllData()
    {
        $userID = $this->session->userdata('MIS_LOGGED_ID');
        $query = $this->db->query("SELECT t1.*, t1.Voters AS VoterViewPelamar, t2.*, t3.*, t2.Posisi AS PosisiDilamar FROM ViewPelamar t1 
        JOIN tblPelamarr t2 ON t1.PelamarID=t2.PelamarID
        JOIN riwayatKerja t3 ON t1.PelamarID=t3.LedgerNo
        ")->result();
        $pelamarData = [];
        foreach ($query as $row) {
            $pelamarID = $row->PelamarID;
            if (!isset($pelamarData[$pelamarID])) {
                $voters = $row->VoterViewPelamar;
                $parseArr = explode(',', $voters);
                if (in_array($userID, $parseArr)) {
                    $hasVote = 'true';
                } else {
                    $hasVote = 'false';
                }
                $pelamarData[$pelamarID] = [
                    "PelamarID" => $row->PelamarID,
                    "Nama" => $row->Nama,
                    "JenisKelamin" => $row->JenisKelamin,
                    "Usia" => $row->Usia,
                    "NoTelp" => $row->NoTelp,
                    "Email" => $row->Email,
                    "PendidikanTerakhir" => $row->PendidikanTerakhir,
                    "Jurusan" => $row->Jurusan,
                    "Universitas" => $row->Universitas,
                    "IPK" => $row->IPK,
                    "ThnLulus" => $row->ThnLulus,
                    "JurusanLanjut" => $row->JurusanLanjut,
                    "UniversitasLanjut" => $row->UniversitasLanjut,
                    "IPKLanjut" => $row->IPKLanjut,
                    "ThnLulusLanjut" => $row->ThnLulusLanjut,
                    "Posisi" => $row->PosisiDilamar,
                    "TahapSeleksi" => $row->TahapSeleksi,
                    "riwayatKerja" => array(),
                    "Voters" => $voters,
                    "hasVote" => $hasVote,
                    "CV" => $row->CV,
                ];
            }
            if ($row->LedgerNo) {
                $pelamarData[$pelamarID]['riwayatKerja'][] = [
                    "NamaPerusahaan" => $row->NamaPerusahaan,
                    "Posisi" => $row->Posisi,
                    "Periode" => $row->Periode,
                ];
            }
        }
        return array_values($pelamarData);
    }
}
