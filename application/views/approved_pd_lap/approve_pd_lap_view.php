<style>
    .borderless tr,
    .borderless td,
    .borderless th {
        border: none !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <b><?= $judul ?></b> <br>
            <b><small><?php echo $Job->JobNo ?> - <?php echo $Job->JobNm ?></small></b>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('welcome/index') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?php echo $judul ?></li>
        </ol>
    </section>

    <section class="content-header">
        <a href="<?= site_url('job/sub_job/' . $Job->JobNo) ?>" class="btn btn-warning"><i class="fa fa-arrow-left"></i> Kembali</a>

    </section>

    <section class="content">

        <div class="row">



            <div class="col-sm-12">
                <div class="box">
                    <div class="box-body">

                        <div class="col-sm-6">
                            <div class="table-responsive">
                                <table class="table table-responsive borderless">
                                    <tr>
                                        <th>Alokasi</th>
                                        <td>
                                            <select name="alokasi" id="alokasi" class="form-control">
                                                <option value="">-- Pilih --</option>
                                                <?php
                                                foreach ($alokasi as $alokasi) { ?>
                                                    <option value="<?= $alokasi->Alokasi ?>"><?= $alokasi->Alokasi ?> - <?= $alokasi->Keterangan ?></option>
                                                <?php } ?>
                                            </select>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Cari</th>
                                        <td>
                                            <input type="text" name="cari" id="cari" placeholder="No.PD" class="form-control">
                                        </td>
                                        <td>
                                        	<input type="hidden"  id="my_job" value="<?=$Job->JobNo?>">
                                            <button id="btn-cari" class="btn btn-sm btn-warning btn-cari"><i class="fa fa-search"></i></button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



        </div>
        <div class="col-sm-12 row tunggu" style="display:none;">
            <span class="tunggu">Loading.....</span>
        </div>
        <div class="tampil"></div>






    </section>
</div>

<script>
    // var table;



    $(function() {
        // $('#alokasi').select2({
        //     width: '100%'
        // });


        $('#btn-cari').click(function() {
        	var JobNo = $('#my_job').val();
            var alokasi = $('#alokasi').val();
            var cari = $('#cari').val();

            if (alokasi == '') {
                toastr.options.positionClass = "toast-bottom-right";
                toastr.info('Pilih Alokasi Terlebih Dahulu');
                return false;
            }

            showtable(JobNo, alokasi, cari)
        })

        $('#alokasi').change(function() {
            var alokasi = this.value;
            var cari = $('#cari').val();
            var JobNo = $('#my_job').val();
            showtable(JobNo, alokasi, cari);
        })
    })



    function showtable(JobNo, alokasi, cari) {

        $.ajax({
            url: "<?= site_url('Approved_pd_lap/show_table') ?>",
            type: "POST",
            dataType: "HTML",
            data: {
                "JobNo": JobNo,
                "alokasi": alokasi,
                "cari": cari
            },
            success: function(data) {
            	$('.tampil').html('');
                $('.tampil').html(data);
            }
        })

    }

    function ApproveUnapprove(a, b, c) {
        var JobNo = $('#my_job').val();
        Swal({
            title: 'Apakah Anda yakin?',
            // text: "Apakah yakin ingin menghapus data ini?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yakin',
            cancelButtonColor: '#d33',

        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "<?= base_url('Approved_pd_lap/status') ?>/",

                    type: "POST",
                    data: {
                        "NoPD": a,
                        "JobNo": b,
                        "ApprovedByKT": c
                    },
                    dataType: "JSON",
                    beforeSend: function() {
                        $('.tunggu').show();
                    },
                    success: function(response) {
                        var alokasi = $('#alokasi').val();
                        var cari = $('#cari').val();
                        $('.tunggu').hide();


                        if (response.status == 'success') {
                            if (response.approve == 'ya') {
                                $.toast({
                                    // heading: 'Berhasil',
                                    text: 'Berhasil Di Approve',
                                    position: 'bottom-right',
                                    stack: false,
                                    hideAfter: 1000,
                                    icon: 'success',
                               
                                })
                            } else {
                                $.toast({
                                    // heading: 'Berhasil',
                                    text: 'Berhasil Di Unapprove',
                                    position: 'bottom-right',
                                    stack: false,
                                    hideAfter: 1000,
                                    icon: 'success',
                               
                                })
                            }

                        } else {
                           $.toast({
                                    // heading: 'Berhasil',
                                    text: 'Terjadi Kesalahan',
                                    position: 'bottom-right',
                                    stack: false,
                                    hideAfter: 1000,
                                    icon: 'error',
                               
                                })
                        }
                        showtable(JobNo, alokasi, cari);
                        // reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error deleting data');
                    }
                });
            }
        });
    }



    // $(document).ready(function() {
    //     $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
    //         return {
    //             "iStart": oSettings._iDisplayStart,
    //             "iEnd": oSettings.fnDisplayEnd(),
    //             "iLength": oSettings._iDisplayLength,
    //             "iTotal": oSettings.fnRecordsTotal(),
    //             "iFilteredTotal": oSettings.fnRecordsDisplay(),
    //             "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    //             "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    //         };
    //     };
    //     table = $("#mytable").dataTable({
    //         "responsive": true,
    //         // "lengthChange": false,
    //         "autoWidth": false,
    //         scrollX: true,
    //         // scrollY: 200,
    //         scrollCollapse: true,
    //         scroller: true,
    //         initComplete: function() {
    //             var api = this.api();
    //             $('#mytable_filter input')
    //             .off('.DT')
    //             .on('keyup.DT', function(e) {
    //                 if (e.keyCode == 13) {
    //                     api.search(this.value).draw();
    //                 }
    //             });
    //         },
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //             "url": "<?= site_url('ApprovePj/get_json_pj/') ?>" + JobNo,
    //             "type": "POST",
    //         },
    //         columns: [{
    //             "data": "NoPD",
    //             "orderable": false,
    //             "searchable": false,
    //         },

    //         {
    //             "data": "NoPD",
    //             "orderable": false,
    //             "searchable": true,
    //         },

    //         {
    //             "data": "TipeForm",
    //             "orderable": false,
    //             "searchable": true,
    //         },

    //         {
    //             "data": "Deskripsi",
    //             "orderable": false,
    //             "searchable": true,
    //         },

    //         {
    //             "data": "TotalPD",
    //             "orderable": false
    //         },

    //         {
    //             "data": "NoPJ",
    //             "orderable": false
    //         },

    //         {
    //             "data": "TglPj",
    //             "orderable": false,
    //             "searchable": true,
    //         },

    //         {
    //             "data": "TotalPJ",
    //             "orderable": false
    //         },

    //         {
    //             "data": "ApprovedByAK",
    //             "orderable": false
    //         },

    //         {
    //             "data": "TimeApprovedAK",
    //             "orderable": false
    //         },

    //         {
    //             "data": "view",
    //             'className': 'text-center',
    //             "orderable": false,
    //             "searchable": false,
    //             "render": function(data, type, row) {
    //                 if (row.ApprovedByAK != '') {
    //                     return '<button class="btn btn-sm btn-secondary text-bold">UNAPPROVE</button>';
    //                 } else {
    //                     return '<button class="btn btn-sm btn-primary text-bold" >APPROVE</button>';
    //                 }
    //             }
    //         }
    //         ],
    //         order: [
    //         [1, 'asc']
    //         ],
    //         rowCallback: function(row, data, iDisplayIndex) {
    //             var info = this.fnPagingInfo();
    //             var page = info.iPage;
    //             var length = info.iLength;
    //             var index = page * length + (iDisplayIndex + 1);
    //             $('td:eq(0)', row).html(index);
    //         }
    //     });

    // })
</script>