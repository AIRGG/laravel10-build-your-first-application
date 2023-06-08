@extends('layout')
@section('header', 'Halaman Tipe')

@section('content')
<button class="btn btn-primary" onclick="addForm()">Tambah</button>
<div class="row mt-3">
    <table class="table table-bordered table-striped table-sm">
        <thead class="text-center">
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Action</th>
        </thead>
        <tbody id="mainTBody">
            {{-- <tr>
                <td>1</td>
                <td>1</td>
                <td>1</td>
                <td class="text-center">
                    <button class="btn btn-warning btn-sm">Edit</button>
                    <button class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr> --}}
        </tbody>
    </table>
</div>

<!-- MODAL FORM -->
<div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Form</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="event.preventDefault()" id="mainForm">
                    @csrf
                    <input type="hidden" name="oldid" value="" />

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Kode</label>
                        <input type="text" class="form-control" name="kode_posts_tipe" value="{{ old('kode_posts_tipe') }}" placeholder="Masukkan Kode">

                        <!-- error message untuk kode_posts_tipe -->
                        <div id="err-kode_posts_tipe" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Nama</label>
                        <input type="text" class="form-control" name="nama_posts_tipe" value="{{ old('nama_posts_tipe') }}" placeholder="Masukkan Nama">

                        <!-- error message untuk nama_posts_tipe -->
                        <div id="err-nama_posts_tipe" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                <button type="button" class="btn btn-primary" onclick="prosesForm()">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js-custom')
<script>
    let act = 'add'
    var myModal = new bootstrap.Modal(document.getElementById("mainModal"), {});
    let datanya = []

    function getDataAll() {
        $.get('/posts-tipe/get-all').then((data) => {
            let htmlTBody = ''
            if(data.length == 0) {
                htmlTBody += `
                <tr class="text-center">
                    <td colspan="5">Data Kosong</td>
                </tr>
                `
            }
            data.forEach((dt, idx) => {
                htmlTBody += `
                <tr>
                    <td>${idx + 1}</td>
                    <td>${dt['kode_tipe']}</td>
                    <td>${dt['nama_tipe']}</td>
                    <td>
                        <button class="btn btn-success" onclick="editForm(${idx})">Edit</button>
                        <button class="btn btn-danger" onclick="deleteForm(${idx})">Hapus</button>
                    </td>
                </tr>
                `
            })
            datanya = data
            $('#mainTBody').html(htmlTBody)
        })
    }

    function setData(idx) {
        let datanow = datanya[idx]
        $('input[name="oldid"]').val(datanow['id'])
        $('input[name="kode_posts_tipe"]').val(datanow['kode_tipe'])
        $('input[name="nama_posts_tipe"]').val(datanow['nama_tipe'])
    }
    function clearData() {
        $('input[name="oldid"]').val('')
        $('input[name="kode_posts_tipe"]').val('')
        $('input[name="nama_posts_tipe"]').val('')
    }

    function addForm() {
        act = 'add'
        myModal.show();
        clearData()
    }

    function editForm(idx) {
        setData(idx)
        act = 'edit'
        myModal.show();
    }

    function deleteForm(idx) {
        setData(idx)
        act = 'delete'
        Swal.fire({
            title: 'Yakin?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                prosesForm()
            }
        })
    }

    function prosesForm() {
        let url = '/posts-tipe/' + act
        let formdata = new FormData(document.getElementById("mainForm"))
        $.ajax({
            url: url,
            method: 'post',
            data: formdata,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response)
                Swal.fire(
                    'Success #2',
                    response.message,
                    'success'
                );
                myModal.hide()
                getDataAll();
            },
            error: function(xhr, msg, txt) {
                if(xhr.status == 200) {
                    myModal.hide()
                    getDataAll();
                    return
                }
                let errornya = JSON.parse(xhr.responseText).errors
                Object.keys(errornya).forEach(v => {
                    console.log(v)
                    let errorlist = '<ul>'
                    errornya[v].forEach(x => {
                        errorlist += `<li>${x}</li>`
                    })
                    errorlist += '</ul>'
                    $(`#err-${v}`).html(errorlist)
                    $(`#err-${v}`).show()
                    $(`[name="${v}"]`).addClass('is-invalid')
                })
            }
        });
    }

    getDataAll();
</script>
@endsection
