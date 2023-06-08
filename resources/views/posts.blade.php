@extends('layout')
@section('header', 'Halaman Posts')

@section('content')
<button class="btn btn-primary" onclick="addForm()">Tambah</button>
<div class="row mt-3">
    <table class="table table-bordered table-striped table-sm">
        <thead class="text-center">
            <th>No</th>
            <th>Kode Tipe</th>
            <th>Nama Tipe</th>
            <th>Title</th>
            <th>Content</th>
            <th>Image</th>
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
                    <input type="hidden" name="fileold" value="" />
                    <img id="imgEdit" src="" style="height: 100px; width: auto;">
                    <div class="form-group">
                        <label class="font-weight-bold">GAMBAR</label>
                        <input type="file" class="form-control" name="image" accept=".jpg,.jpeg,.png">

                        <!-- error message untuk title -->
                        <div id="err-image" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">TIPE</label>
                        <select class="form-control" name="tipe" id="tipe">
                            @foreach ($dataPostTipe as $key => $value)
                                <option value="{{ $value->id }}">{{ $value->nama_tipe }}</option>
                            @endforeach
                        </select>

                        <!-- error message untuk tipe -->
                        <div id="err-tipe" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">JUDUL</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" placeholder="Masukkan Judul Post">

                        <!-- error message untuk title -->
                        <div id="err-title" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label class="font-weight-bold">KONTEN</label>
                        <textarea class="form-control" name="content" rows="5" placeholder="Masukkan Konten Post">{{ old('content') }}</textarea>

                        <!-- error message untuk content -->
                        <div id="err-content" class="alert alert-danger mt-2 alert-error-msg" style="display:none;">
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
    let datanyaProdi = []

    function getDataAll() {
        $.get('/posts/get-all').then((data) => {
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
                    <td>${dt['title']}</td>
                    <td>${dt['content']}</td>
                    <td>
                        <img src="/posts/${dt['image']}" style="height: 100px; width: auto;" />
                    </td>
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
        $('select[name="tipe"]').val(datanow['id_tipe'])
        $('input[name="title"]').val(datanow['title'])
        $('textarea[name="content"]').val(datanow['content'])
        $('input[name="fileold"]').val(datanow['image'])
        $('#imgEdit').attr('src', `/posts/${datanow['image']}`)
    }
    function clearData() {
        $('input[name="oldid"]').val('')
        $('select[name="tipe"]').val('')
        $('input[name="title"]').val('')
        $('textarea[name="content"]').val('')
        $('input[name="foto"]').val('')
        $('input[name="fileold"]').val('')
        $('#imgEdit').attr('src', ``)
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
        let url = '/posts/' + act
        let formdata = new FormData(document.getElementById("mainForm"))
        $('.alert-error-msg').hide()
        $('.form-control').removeClass('is-invalid')
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
