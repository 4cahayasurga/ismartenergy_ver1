<link rel="stylesheet" href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.0/css/fixedColumns.dataTables.css">
<style>
    .font_12 {
        font-size: 12px !important;
    }

    .jarak {
        margin-top: 10px;
    }

    #tbl_tambah tr {
        padding-top: 10px !important;
    }

    .swal2-popup {
        font-size: 1.4rem !important;
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
    }

    #example {
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    }

    #example thead tr {
        border-radius: 20px;
    }

    #example thead tr:first-child th {
        text-align: center;
        justify-items: center;
        background-color: #e5e9f0;
        text-wrap: wrap;
        border: 1px solid #adb5bd;
    }

    #example thead tr:last-child th {
        justify-items: center;
        text-align: center;
        background-color: #eceff4;
        text-wrap: wrap;
        border: 1px solid #adb5bd;
    }

    #example tbody tr td {
        border-bottom: 1px solid #adb5bd;
    }

    .loading {
        display: flex;
        justify-content: center;
    }

    .modalku {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 2;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        animation: show-modal 0.5s ease-in-out;
    }

    .modal-dialog {
        background-color: yellow;
    }

    .modal-content {
        margin-top: 50px;
        width: 100%;
        height: 90vh;
        background-color: #fff;
    }

    .modal-body {
        height: 75vh;
        overflow: hidden;
    }

    .cont-search {
        margin-top: 10px;
        display: flex;
        justify-content: center;
        gap: 20px;
        align-items: center;
        width: 100%;
    }

    .cont-search i {
        border: 1px solid #adb5bd;
        background-color: #adb5bd;
        border-radius: 5px;
        padding: 5px;
        color: #fff;
    }

    .cont-search i:hover {
        background-color: #4c566a;
        border-color: #4c566a;
        cursor: pointer;
    }

    .search-table {
        width: 120px;
        border-radius: 5px;
        padding: 2px 10px;
        border: 1px solid #adb5bd;
        outline: none;
        color: #4c566a;
    }

    .modaltipeform {
        display: none;
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 99;
    }

    .modaltipeform .content {
        padding: 20px;
        min-width: 200px;
        min-height: 100px;
        max-height: 300px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px 10px 0 0;
        overflow: scroll;
    }

    .modaltipeform .content table {
        width: 100%;
        margin-bottom: 20px;
        min-height: 50px;
        max-height: 100px;
        overflow: scroll;
    }

    .modaltipeform .footer {
        background-color: #e8eaeb;
        border-radius: 0 0 10px 10px;
        display: flex;
        justify-content: center;
    }

    .modaltipeform .footer #btnFilter {
        background-color: #2b82bd;
        color: #fff;
        width: 100%;
        padding: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 0 10px;
        cursor: pointer;
    }

    .modaltipeform .footer #btnFilter:hover {
        background-color: #3ea6ed;
    }

    .modaltipeform .footer .closeModalTipeForm {
        background-color: #c2c2c2;
        color: #fff;
        width: 100%;
        padding: 5px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0 0 10px 0;
        cursor: pointer;
    }

    @keyframes show-modal {
        0% {
            opacity: 0;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }
</style>
<div class="content-wrapper">
    <div class="flashdata" data-flash="<?= $this->session->flashdata('message') ?>"></div>
    <section class="content-header">
        <h1>
            <?= ($judul) ? $judul : '' ?>
        </h1>
        <p style="font-style: italic; margin-top: 10px;">silahkan pilih pelamar dibawah ini..</p>
        <ol class="breadcrumb">
            <li><a href="<?= site_url('Welcome') ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Form Pelamar</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="table-responsive table-example">
                            <table class="table font_12 table-bordered table-striped table-hover table-hover" data-id="example" id="example">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th colspan="5">Biodata Pelamar</th>
                                        <th colspan="4">Pendidikan</th>
                                        <th colspan="4">Pendidikan Lanjutan</th>
                                        <th style="background-color: #a5d8ff;"></th>
                                        <th colspan="3">Riwayat Kerja</th>
                                        <th colspan="2"></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th>ID Pelamar</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th>
                                            Usia
                                            <div class="cont-search ">
                                                <input type="text" class="search-table">
                                                <i class="fa fa-filter filterHead"></i>
                                            </div>
                                        </th>
                                        <th>
                                            Pendidikan Terakhir
                                            <div class="cont-search ">
                                                <input type="text" class="search-table">
                                                <i class="fa fa-filter filterHead"></i>
                                            </div>
                                        </th>
                                        <th>
                                            Jurusan
                                            <div class="cont-search ">
                                                <input type="text" class="search-table">
                                                <i class="fa fa-filter filterHead"></i>
                                            </div>
                                        </th>
                                        <th>Universitas</th>
                                        <th>IPK</th>
                                        <th>Tahun Lulus</th>
                                        <th>Jurusan</th>
                                        <th>Universitas</th>
                                        <th>IPK</th>
                                        <th>Tahun Lulus</th>
                                        <th style="background-color: #a5d8ff;">
                                            Posisi Yg Dilamar
                                            <div class="cont-search ">
                                                <input type="text" class="search-table">
                                                <i class="fa fa-filter filterHead"></i>
                                            </div>
                                        </th>
                                        <th>Nama Perusahaan</th>
                                        <th>Posisi</th>
                                        <th>Periode</th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $NoUrut = 1;
                                    foreach ($allData as $data) : ?>
                                        <tr>
                                            <td class="text-center"><?= $NoUrut++; ?></td>
                                            <!-- biodata -->
                                            <td><?= $data['PelamarID'] ?></td>
                                            <td><?= $data['Nama'] ?></td>
                                            <td><?= $data['JenisKelamin'] ?></td>
                                            <td><?= $data['Usia'] ?> Tahun</td>
                                            <!-- biodata -->
                                            <!-- pendidikan terakhir -->
                                            <td><?= $data['PendidikanTerakhir'] ?></td>
                                            <!-- pendidikan terakhir -->
                                            <!-- pendidiakan -->
                                            <td><?= $data['Jurusan'] ?></td>
                                            <td><?= $data['Universitas'] ?></td>
                                            <td><?= $data['IPK'] ?></td>
                                            <td><?= $data['ThnLulus'] ?></td>
                                            <!-- pendidiakan -->
                                            <!-- pendidiakan lanjutan-->
                                            <td><?= $data['JurusanLanjut'] ?></td>
                                            <td><?= $data['UniversitasLanjut'] ?></td>
                                            <td><?= $data['IPKLanjut'] ?></td>
                                            <td><?= $data['ThnLulusLanjut'] ?></td>
                                            <!-- pendidiakan lanjutan-->
                                            <!-- posisi -->
                                            <td style="background-color: #a5d8ff;"><?= $data['Posisi'] ?></td>
                                            <!-- posisi -->
                                            <!-- riwyat kerja -->
                                            <td>
                                                <?php if (count($data['riwayatKerja']) >= 1) : ?>
                                                    <?php foreach ($data['riwayatKerja'] as $row2) : ?>
                                                        <p>✓ <?= $row2['NamaPerusahaan'] ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (count($data['riwayatKerja']) >= 1) : ?>
                                                    <?php foreach ($data['riwayatKerja'] as $row2) : ?>
                                                        <p>✓ <?= $row2['Posisi'] ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (count($data['riwayatKerja']) >= 1) : ?>
                                                    <?php foreach ($data['riwayatKerja'] as $row2) : ?>
                                                        <p>✓ <?= $row2['Periode'] ?></p>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>
                                            <!-- riwyat kerja -->
                                            <td>
                                                <?php if ($data['hasVote'] != 'true') : ?>
                                                    <form action="<?= site_url('FrmPelamarPosting/createVote') ?>" method="POST">
                                                        <input type="hidden" name="pelamarid" value="<?= $data['PelamarID'] ?>">
                                                        <button type="submit" class="btn btn-success btn-md btn-vote">Pilih</button>
                                                    </form>
                                                <?php else : ?>
                                                    <button type="submit" disabled class="btn btn-primary btn-md">Dipilih</button>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($data['CV'] != '') : ?>
                                                    <button class="btn btn-warning btn-lihatCV" data-url="<?= url_cv_pelamar_hris('server') . $data['CV']; ?>">Lihat CV</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Modal -->
<div class="modalku" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">CV Pelamar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <embed id="imgModalCv" type="application/pdf" src="" width="550" height="800"></embed>
                        <!-- <img id="imgModalC" src="" alt=""> -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary close-modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal filter -->
<div class="modaltipeform" hidden>
    <div class="content">
        <p>Filter Value:</p>
        <hr>
        <input type="hidden" class="thindex" value="">
        <table id="tableTipeForm">

        </table>
    </div>
    <div class="footer">
        <div id="btnFilter">Filter</div>
        <div class="closeModalTipeForm">Close</div>
    </div>
</div>


<script src="https://cdn.datatables.net/2.0.0/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/dataTables.fixedColumns.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/fixedColumns.dataTables.js"></script>

<script>
    $(function() {
        var flashdata = $('.flashdata').data('flash')
        if (flashdata != '') {
            Swal.fire({
                title: 'Berhasil',
                text: flashdata,
                icon: 'success'
            })
        }

        $('#example').DataTable({
            paging: true,
            language: {
                search: 'Pencarian <i class="fa fa-search"></i>',
                searchPlaceholder: "Pencarian Disini",
                processing: "<i class='fa fa-refresh fa-spin'></i>",
                paginate: {
                    "previous": '<i class="fa fa-angle-left"></i>',
                    "next": '<i class="fa fa-angle-right"></i>'
                }
            },
            // pageLength: 50,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            columnDefs: [{
                orderable: false,
                targets: '*'
            }]
        });

        $('.btn-vote').click(function(e) {
            e.preventDefault()
            Swal.fire({
                title: 'Apa anda yakin?',
                text: 'Anda tidak dapat membatalkan pilihan setelah memilih pelamar',
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: 'Ya!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.value == true) {
                    $(this).closest('form').submit()
                }
            })
        })

        $('.btn-lihatCV').click(function(e) {
            e.preventDefault()
            var url = $(this).data('url');
            $('#myModal #imgModalCv').attr('src', url);
            //    tampilkan modal
            $('#myModal').css('display', 'block');
        })

        $('.close-modal').click(function() {
            $('#myModal').css('display', 'none')
        })

        $('.search-table').on('input', function() {
            var txtInput = $(this).val()
            var tableID = 'example'
            var th = $(this).closest('th')
            var index = th.index()
            searchTable(index, txtInput, tableID)
        })

        var tableID;
        $('.filterHead').click(function() {
            var table = $(this).closest('table')
            tableID = table.attr('data-id')
            var th = $(this).closest('th')
            var index = th.index()
            $('.thindex').val(index)
            var modal = $('.modaltipeform');
            var valuesMap = {};
            var valuesArray = [];
            $(`#${tableID} tbody tr`).each(function() {
                var valuesTd = $(this).find(`td:eq(${index})`).text()
                if (!valuesMap[valuesTd]) {
                    valuesMap[valuesTd] = true
                    valuesArray.push(valuesTd)
                }
            })
            $('#tableTipeForm').empty();
            $.each(valuesArray, function(index, label) {
                if (label == 'null') {
                    var value = ''
                } else {
                    var value = label
                }
                $('#tableTipeForm').append(`
					<tr style="border-bottom:1px solid #ebe6e6; ">
						<td><input style="margin-right:8px; margin-bottom:10px;" type="checkbox" name="isi" class="checkboxtipeform" value="${value}" id="${index}"><label for="${index}">${label}</label></td>
					</tr>
				`)
            })
            var buttonPosition = $(this).offset();
            var modalWidth = modal.outerWidth();
            var modalHeight = modal.outerHeight();
            var screenWidth = $(window).width();
            var screenHeight = $(window).height();
            var left = buttonPosition.left;
            if (buttonPosition.left + modalWidth > screenWidth) {
                left = screenWidth - modalWidth;
            }
            var top = buttonPosition.top + $(this).outerHeight();
            if (buttonPosition.top + $(this).outerHeight() + modalHeight > screenHeight) {
                top = screenHeight - modalHeight - $(this).outerHeight();
            }
            modal.css({
                top: top,
                left: left
            });
            modal.show();
        });

        $('#btnFilter').click(function() {
            var checkValues = [];
            var index = $('.thindex').val()
            $('.checkboxtipeform:checked').each(function() {
                checkValues.push($(this).val())
            })
            filterTable(checkValues, index)
            var modal = $('.modaltipeform');
            modal.hide();
        })

        $('.closeModalTipeForm').click(function() {
            var modal = $('.modaltipeform');
            modal.hide();
        })

        function searchTable(index, txtInput, tableID) {
            var valueInput = txtInput.toLowerCase()

            $(`#${tableID} tbody tr`).each(function() {
                var filedValue = $(this).find(`td:eq(${index})`).text().toLowerCase()
                if (filedValue.indexOf(valueInput) > -1) {
                    $(this).show()
                } else {
                    $(this).hide()
                }
            })
        }

        function filterTable(values, index) {
            $(`#example tbody tr`).each(function() {
                var txtValue = $(this).find(`td:eq(${index})`).text().toLowerCase()
                var lowerCaseArray = values.map(function(value) {
                    return value.toLowerCase();
                });
                if (lowerCaseArray.length === 0 || lowerCaseArray.includes(txtValue)) {
                    $(this).show()
                } else {
                    $(this).hide()
                }
            })
        }

    })
</script>