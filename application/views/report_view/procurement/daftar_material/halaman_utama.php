<style>
    .mykonten {
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: x-small !important;
    }

    .ukuran_select2 {
        font-size: 10px !important;
        font-family: Arial, Helvetica, sans-serif;

    }

    .select2-container {
        width: 100% !important;
        padding: 0;
    }


    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
</style>
<style>
    .autoComplete_wrapper {
        display: inline-block;
        position: relative;
        background: #212121;
        width: 100%;
    }

    .autoComplete_wrapper>input {
        height: 3rem;
        margin: 0;
        padding: 0 10px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        font-size: 1.2rem;
        text-overflow: ellipsis;
        color: rgba(15, 15, 15, 0.973);
        outline: 0;
        border-radius: 2px;
        border: .05rem solid rgb(204, 204, 204);
        background-color: #fff;
        transition: all .4s ease;
        -webkit-transition: all -webkit-transform .4s ease
    }

    .autoComplete_wrapper>input::placeholder {
        color: rgba(177, 177, 177, 0.878);
        transition: all .3s ease;
        -webkit-transition: all -webkit-transform .3s ease
    }

    .autoComplete_wrapper>input:hover::placeholder {
        color: rgba(32, 32, 32, 0.6);
        transition: all .3s ease;
        -webkit-transition: all -webkit-transform .3s ease
    }

    .autoComplete_wrapper>input:focus::placeholder {
        padding: .1rem .6rem;
        font-size: .95rem;
        color: rgba(177, 177, 177, 0.878);
    }

    .autoComplete_wrapper>input:focus::selection {
        background-color: rgba(255, 122, 122, .15)
    }

    .autoComplete_wrapper>input::selection {
        background-color: rgba(255, 122, 122, .15)
    }


    .autoComplete_wrapper>ul {
        position: absolute;
        max-height: 226px;
        overflow-y: scroll;
        box-sizing: border-box;
        left: 0;
        right: 0;
        margin: .5rem 0 0 0;
        padding: 0;
        z-index: 1;
        list-style: none;
        border-radius: .6rem;
        background-color: #fff;
        border: 1px solid rgba(33, 33, 33, .07);
        box-shadow: 0 3px 6px rgba(149, 157, 165, .15);
        outline: 0;
        transition: opacity .15s ease-in-out;
        -moz-transition: opacity .15s ease-in-out;
        -webkit-transition: opacity .15s ease-in-out
    }

    .autoComplete_wrapper>ul:empty,
    .autoComplete_wrapper>ul[hidden] {
        display: block;
        opacity: 0;
        transform: scale(0)
    }

    .autoComplete_wrapper>ul>li {
        margin: .3rem;
        padding: .3rem .5rem;
        text-align: left;
        font-size: 1rem;
        color: #212121;
        border-radius: .35rem;
        background-color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: all .2s ease
    }

    .autoComplete_wrapper>ul>li mark {
        background-color: transparent;
        color: #ff7a7a;
        font-weight: 700
    }

    .autoComplete_wrapper>ul>li:hover {
        cursor: pointer;
        background-color: rgba(255, 122, 122, .15)
    }

    .autoComplete_wrapper>ul>li[aria-selected=true] {
        background-color: rgba(255, 122, 122, .15)
    }

    @media only screen and (max-width:600px) {
        .autoComplete_wrapper>input {
            width: 20rem
        }
    }
</style>
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css"> -->
<div class="content-wrapper">
    <section class="content-header">
        <h4 style="font-weight:bold;"><?= $judul ?></h4>

        <ol class="breadcrumb">
            <li><a href="<?= site_url('dashboard')  ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active"><?= $judul ?></li>
        </ol>
    </section>

    <section class="content-header">

        <button class="btn btn-xs" style="background-color:#7f8c8d;" onclick="history.back()"><i class="fa fa-arrow-left"></i> Back</button>


    </section>

    <section class="content">

        <div class="flashdata" data-flash="<?= $this->session->flashdata('message') ?>"></div>

        <div class="box box-danger">

            <div class="box-header with-border">
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Kategori</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input autocomplete="off" type="text" class="form-control input-sm" name="kategori" id="kategori">
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Sub Kategori</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <input autocomplete="off" type="text" class="form-control input-sm" name="subKategori" id="subKategori">
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Nama Barang</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select width="200px" name="" id="" class="mySelect form-control input-sm selectNmBarang">
                                        <option disabled selected></option>
                                        <?php foreach ($dftrMaterial as $row) : ?>
                                            <option><?= $row['NmBarang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="warningNmBarang" style="color: red; margin-top:5px;" hidden>Nama barang sudah ada</div>
                                    <input type="hidden" class="inpNmBarang" id="namaBarang" value="">
                                    <input autocomplete="off" type="hidden" id="NmBarang" value="<?= htmlentities(json_encode($dftrMaterial)) ?>">
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px">Kode Barang</th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <select width="200px" name="" id="kodeBarang" class="mySelect form-control input-sm selectKdBarang">
                                        <option disabled selected></option>
                                        <?php foreach ($kdBarang as $row) : ?>
                                            <option><?= $row['KdBarang'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="warningKdBarang" style="color: red; margin-top:5px;" hidden>Kode barang sudah ada</div>
                                    <input type="hidden" class="inpKdBarang" id="kodeBarang" value="">
                                    <input autocomplete="off" type="hidden" id="KdBarang" value="<?= htmlentities(json_encode($kdBarang)) ?>">
                                </td>
                            </tr>
                        </table>
                        <table border="0" width="100%">
                            <tr>
                                <th style="padding-top:5px;padding-bottom:5px;" width="100px"></th>
                                <td style="padding-top:5px;padding-bottom:5px;">
                                    <button type="submit" name="btn_simpan" id="btn_simpan" class="btn btn-sm bg-purple"><i class="fa fa-plus"></i> Tambahkan</button>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
            <div class="overlay loadingku" style="display: none;">
                <i class="fa fa-refresh fa-spin"></i>
            </div>

        </div>
        <div class="box box-primary">
            <a href="<?= site_url('MyReport/Procurement/DaftarMaterial/exportExcel') ?>" class="btn btn-success" id="btn_exportExcel" style="margin: 10px 15px;">Export Excel</a>
            <div class="box-header white-border">
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="MyTable" style="width: 100%;">
                        <thead>
                            <tr class="bg-gray">
                                <th width="10%" class="text-center">Kode Barang</th>
                                <th width="30%" class="text-center">Nama Barang</th>
                                <th width="20%" class="text-center">Kategori</th>
                                <th width="20%" class="text-center">Sub Kategori</th>
                                <!-- <th width="20%" class="text-center">Time Entry</th> -->
                                <th width="20%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="<?= site_url('MyReport/Procurement/DaftarMaterial/Update') ?>" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalKodeBarang">
                        <label for="">Kode Barang</label>
                        <div class="autoComplete_wrapper">
                            <input id="autoCompleteKodeBarang" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" class="form-control" autocapitalize="off" name="kodeBarang">
                            <input type="hidden" value="" id="kodeBarangHidden" name="kdBarangLama">
                        </div>
                        <div class="warningKdBarangModal" style="color: red; margin-top:5px;" hidden>Kode barang sudah ada</div>
                    </div>
                    <div id="modalNamaBarang">
                        <label for="">Nama Barang</label>
                        <div class="autoComplete_wrapper">
                            <input id="autoCompleteNamaBarang" type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" class="form-control" autocapitalize="off" name="namaBarang">
                            <input type="hidden" value="" id="namaBarangHidden" name="nmBarangLama">
                        </div>
                        <div class="warningNmBarangModal" style="color: red; margin-top:5px;" hidden>Nama barang sudah ada</div>
                    </div>
                    <div id="modalKategori">
                        <label for="">Kategori</label>
                        <input autocomplete="off" style="margin-bottom: 5px;" type="text" class="form-control" id="inpKategori" name="kategori">
                    </div>
                    <div id="modalSubKategori">
                        <label for="">Sub Kategori</label>
                        <input autocomplete="off" style="margin-bottom: 5px;" type="text" class="form-control" id="inpSubKategori" name="subKategori">
                    </div>

                    <input type="hidden" class="type" name="type" value="">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnSimpanModal">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
<script>

</script>
<script>
    $(document).ready(function() {
        autoCompleteNamaBarang()
        autoCompleteKodeBarang()

        $('#MyTable').DataTable({
            "ajax": {
                'url': "<?= base_url('MyReport/Procurement/DaftarMaterial/getData') ?>",
            },
            "scrollY": false,
            "scrollCollapse": true,
            "pageLength": 50,
            "lengthMenu": [50, 100, 200, 500, 1000, -1],
            "columns": [{
                    "data": "KdBarang",
                    "orderable": true,
                },
                {
                    "data": "NmBarang",
                    "orderable": true,
                },
                {
                    "data": "Kategori",
                    "orderable": true,
                },
                {
                    "data": "SubKategori",
                    "orderable": true,
                },
                {
                    "orderable": false,
                    "class": "text-center",
                    "data": "ID",
                    "render": function(data, type, row) {
                        return (
                            `<button id=${data} class="btn btn-primary btn-edit">Edit</button>`
                        )
                    },
                },
            ],
            order: [
                [2, "desc"]
            ]
        })

        $('#MyTable').on('click', '.btn-edit', function() {
            var kodeBarang = $(this).closest('tr').find('td:nth(0)').text()
            var namaBarang = $(this).closest('tr').find('td:nth(1)').text()
            var kategori = $(this).closest('tr').find('td:nth(2)').text()
            var subKategori = $(this).closest('tr').find('td:nth(3)').text()
            $('#exampleModalLabel').html('Update Data')
            $('#autoCompleteKodeBarang').val(kodeBarang)
            $('#kodeBarangHidden').val(kodeBarang)
            $('#inpKategori').val(kategori)
            $('#inpSubKategori').val(subKategori)
            $('#autoCompleteNamaBarang').val(namaBarang)
            $('#namaBarangHidden').val(namaBarang)
            $('#exampleModal').modal('show')
            if (kodeBarang == '') {
                $('.type').val('first')
            } else {
                $('.type').val('second')
            }
        });

        $('#exampleModal #autoCompleteKodeBarang').focusout(function() {
            var valueInput = $(this).val()
            var valueKodeBarangHidden = $('#kodeBarangHidden').val()
            var ListKode = JSON.parse($('#KdBarang').val())
            var arrListKode = []
            $.each(ListKode, function(index, val) {
                arrListKode.push(val.KdBarang)
            })
            var res = checkInputInArray2(valueInput, arrListKode)
            if (res == true) {
                if (valueInput == valueKodeBarangHidden) {
                    $('#exampleModal .btnSimpanModal').removeAttr('disabled')
                    $('#exampleModal .warningKdBarangModal').hide()
                } else {
                    $('#exampleModal .warningKdBarangModal').show()
                    $('#exampleModal .btnSimpanModal').attr('disabled', true)
                }
            } else {
                $('#exampleModal .btnSimpanModal').removeAttr('disabled')
                $('#exampleModal .warningKdBarangModal').hide()
            }
        })

        $('#exampleModal #autoCompleteNamaBarang').focusout(function() {
            var valueInput = $(this).val()
            var valueNamaBarangHidden = $('#namaBarangHidden').val()
            var ListNama = JSON.parse($('#NmBarang').val())
            var arrListNama = []
            $.each(ListNama, function(index, val) {
                arrListNama.push(val.NmBarang)
            })
            var res = checkInputInArray2(valueInput, arrListNama)
            if (res == true) {
                if (valueInput == valueNamaBarangHidden) {
                    $('#exampleModal .btnSimpanModal').removeAttr('disabled')
                    $('#exampleModal .warningNmBarangModal').hide()
                } else {
                    $('#exampleModal .warningNmBarangModal').show()
                    $('#exampleModal .btnSimpanModal').attr('disabled', true)
                }
            } else {
                $('#exampleModal .btnSimpanModal').removeAttr('disabled')
                $('#exampleModal .warningNmBarangModal').hide()
            }
        })

        $('.select2').select2({
            'theme': 'classic',
            'width': '100%',
            "tags": true,
            dropdownParent: $('#exampleModal')
        });
        $('.mySelect').select2({
            'theme': 'classic',
            'width': '100%',
            "tags": true,
        });

        $('.selectNmBarang').on('change', function() {
            var selectedData = $(this).select2('data')[0].text;
            var ListNama = JSON.parse($('#NmBarang').val())
            var arrListNama = []
            $.each(ListNama, function(index, val) {
                arrListNama.push(val.NmBarang)
            })
            var res = checkInputInArray(selectedData, arrListNama)
            if (res == true) {
                $('.warningNmBarang').show()
                $('#btn_simpan').attr('disabled', true)
            } else {
                $('#btn_simpan').removeAttr('disabled')
                $('.warningNmBarang').hide()
            }
            console.log(res)
            checkBtnSimpanDanWarningMessage()
            $('.inpNmBarang').val(selectedData)

        })
        $('.selectKdBarang').on('change', function() {
            var selectedData = $(this).select2('data')[0].text;
            var ListNama = JSON.parse($('#KdBarang').val())
            var arrListNama = []
            $.each(ListNama, function(index, val) {
                arrListNama.push(val.KdBarang)
            })
            var res = checkInputInArray(selectedData, arrListNama)
            if (res == true) {
                console.log('kode barang sow')
                $('.warningKdBarang').show()
                $('#btn_simpan').attr('disabled', true)
            } else {
                console.log('kode barang hide')
                $('#btn_simpan').removeAttr('disabled')
                $('.warningKdBarang').hide()
            }
            checkBtnSimpanDanWarningMessage()
            $('.inpKdBarang').val(selectedData)

        })

        $('#btn_simpan').click(function() {
            var kategori = $('#kategori').val()
            var subKategori = $('#subKategori').val()
            var namaBarang = $('#namaBarang').val()
            var kodeBarang = $('#kodeBarang').val()
            $.ajax({
                url: '<?= base_url('MyReport/Procurement/DaftarMaterial/save') ?>',
                method: 'POST',
                data: {
                    kategori,
                    subKategori,
                    namaBarang,
                    kodeBarang
                },
                success: function(result) {
                    var res = JSON.parse(result)
                    if (res.status == 'error') {
                        toastr.warning('', 'Data gagal ditambahkan');
                        return false;
                    } else {
                        toastr.success('', 'Data berhasil ditambahkan');
                        window.location.reload()
                    }
                }
            })
        })

        $('.myTextarea').on('keydown', function(e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;
                var spaces = '        ';
                var indent = spaces;
                $(this).val(function(_, val) {
                    return val.substring(0, start) + indent + val.substring(end);
                });
                this.setSelectionRange(start + indent.length, start + indent.length);
            }
        });


        const flashdata = $('.flashdata').data('flash')
        if (flashdata != '') {
            toastr.success('', flashdata);
        }

        checkBtnSimpanDanWarningMessage()
    });

    function autoCompleteNamaBarang() {
        var ListNama = JSON.parse($('#NmBarang').val())
        var arrListNama = []
        $.each(ListNama, function(index, val) {
            arrListNama.push(val.NmBarang)
        })
        const autoCompleteJS = new autoComplete({
            selector: "#autoCompleteNamaBarang",
            placeHolder: "Cari nama barang...",
            data: {
                src: arrListNama,
                cache: true,
            },
            resultsList: {
                element: (list, data) => {
                    if (!data.results.length) {
                        // Create "No Results" message element
                        const message = document.createElement("div");
                        // Add class to the created element
                        message.setAttribute("class", "no_result");
                        // Add message text content
                        message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                        // Append message element to the results list
                        list.prepend(message);
                    }
                },
                noResults: true,
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const selection = event.detail.selection.value;
                        autoCompleteJS.input.value = selection;
                    }
                }
            }
        });
    }

    function autoCompleteKodeBarang() {
        var ListKode = JSON.parse($('#KdBarang').val())
        var arrListKode = []
        $.each(ListKode, function(index, val) {
            arrListKode.push(val.KdBarang)
        })
        const autoCompleteJS = new autoComplete({
            selector: "#autoCompleteKodeBarang",
            placeHolder: "Cari kode barang...",
            data: {
                src: arrListKode,
                cache: true,
            },
            resultsList: {
                element: (list, data) => {
                    if (!data.results.length) {
                        // Create "No Results" message element
                        const message = document.createElement("div");
                        // Add class to the created element
                        message.setAttribute("class", "no_result");
                        // Add message text content
                        message.innerHTML = `<span>Found No Results for "${data.query}"</span>`;
                        // Append message element to the results list
                        list.prepend(message);
                    }
                },
                noResults: true,
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: (event) => {
                        const selection = event.detail.selection.value;
                        autoCompleteJS.input.value = selection;
                    }
                }
            }
        });
    }

    function checkInputInArray(input, array) {
        var lowerCaseInput = input.replace(/\s+/g, ' ').toLowerCase();
        for (var i = 0; i < array.length; i++) {
            var lowerCaseArrayItem = array[i].replace(/\s+/g, ' ').toLowerCase();
            if (lowerCaseArrayItem.includes(lowerCaseInput)) {
                return true;
            }
        }
        return false;
    }

    function checkInputInArray2(input, array) {
        var lowerCaseInput = input.replace(/\s+/g, ' ').toLowerCase();
        for (var i = 0; i < array.length; i++) {
            var lowerCaseArrayItem = array[i].replace(/\s+/g, ' ').toLowerCase();
            if (lowerCaseArrayItem === lowerCaseInput) {
                return true;
            }
        }
        return false;
    }

    function checkBtnSimpanDanWarningMessage() {
        if ($('.warningKdBarang').is(':visible') || $('.warningNmBarang').is(':visible')) {
            $('#btn_simpan').attr('disabled', true)
        }
        if ($('.selectKdBarang').val() == null) {
            $('.warningKdBarang').hide()
        }
        if ($('.selectNmBarang').val() == null) {
            $('.warningNmBarang').hide()
        }
    }



    // function checkBtnSimpanDanWarningMessageModal() {
    //     if ($('#exampleModal .warningKdBarangModal').is(':visible') || $('#exampleModal .warningNmBarangModal').is(':visible')) {
    //         $('#exampleMoal .btnSimpanModal').attr('disabled', true)
    //     }
    //     if ($('#exampleModal .selectNmBarangModal').val() == null) {
    //         $('#exampleModal .warningNmBarangModal').hide()
    //     }
    //     if ($('#exampleModal .selectKdBarangModal').val() == null) {
    //         $('#exampleModal .warningNmBarangModal').hide()
    //     }
    // }
</script>