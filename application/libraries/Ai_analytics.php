<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ai_analytics {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function analyze($data)
        {
            $result = [];

            // 1. Total SPBB
            $result['total'] = count($data);

            // 2. Total Amount
            $total_amount = 0;
            foreach ($data as $row) {
                $total_amount += $row->amount;
            }
            $result['total_amount'] = $total_amount;

            // 3. Rata-rata
            $result['average'] = $total_amount / max(count($data),1);

            // 4. Deteksi Anomali (lebih dari 2x rata-rata)
            $anomaly = [];
            foreach ($data as $row) {
                if ($row->amount > ($result['average'] * 2)) {
                    $anomaly[] = $row;
                }
            }
            $result['anomaly'] = $anomaly;

            // 5. Insight otomatis
            if(count($anomaly) > 0){
                $result['insight'] = "Terdapat transaksi abnormal yang melebihi 2x rata-rata.";
            } else {
                $result['insight'] = "Performa transaksi stabil dan dalam batas normal.";
            }

            return $result;
        }

        public function generateInsight($data)
        {
            $avg = $data['total_liter'] / max($data['total_transaksi'],1);

            $risk = "LOW";
            if ($avg < 300) {
                $risk = "HIGH";
            }

            $insight = "
            📊 Total distribusi mencapai ".number_format($data['total_liter'])." liter.<br>
            🚢 Total kapal aktif ".$data['jumlah_kapal']." unit.<br>
            📦 Rata-rata distribusi per transaksi ".number_format($avg)." liter.<br>
            ⚠ Risk Level: ".$risk."
            ";

            $recommendation = $risk == "HIGH"
                ? "Perlu evaluasi distribusi dan monitoring volume kecil."
                : "Distribusi dalam kondisi stabil dan optimal.";

            return [
                'insight' => $insight,
                'recommendation' => $recommendation,
                'risk' => $risk,
                'avg' => round($avg,2)
            ];
        }


        // public function generateInsight($data)
        // {
        //     $avg_per_transaksi = 0;
        //     if ($data['total_transaksi'] > 0) {
        //         $avg_per_transaksi = $data['total_liter'] / $data['total_transaksi'];
        //     }

        //     // Risk Scoring sederhana
        //     $risk = "LOW";
        //     if ($avg_per_transaksi < 500) {
        //         $risk = "MEDIUM";
        //     }
        //     if ($avg_per_transaksi < 300) {
        //         $risk = "HIGH";
        //     }

        //     // Insight Text
        //     $insight = "
        //     📊 Total distribusi mencapai ".number_format($data['total_liter'])." liter.
        //     🚢 Melibatkan ".$data['jumlah_kapal']." kapal aktif.
        //     📦 Rata-rata distribusi per transaksi sebesar ".number_format($avg_per_transaksi)." liter.
        //     ⚠ Risk Level: ".$risk."
        //     ";

        //     // Rekomendasi otomatis
        //     if($risk == "HIGH"){
        //         $recommendation = "Perlu evaluasi distribusi dan monitoring transaksi kecil.";
        //     } elseif($risk == "MEDIUM"){
        //         $recommendation = "Pantau konsistensi volume distribusi.";
        //     } else {
        //         $recommendation = "Performa distribusi dalam kondisi optimal.";
        //     }

        //     return [
        //         'avg' => round($avg_per_transaksi,2),
        //         'risk' => $risk,
        //         'insight' => $insight,
        //         'recommendation' => $recommendation
        //     ];
        // }

}


