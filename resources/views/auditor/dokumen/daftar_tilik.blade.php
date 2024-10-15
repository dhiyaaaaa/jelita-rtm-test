@php
    use Carbon\Carbon;
@endphp
@extends('components.layout.main_layout')

@section('content')
    <div class="card card-dark">
        <div class="card-header">
            <h3 class="card-title title-size">{{ $title }}</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <a href="{{ route('auditor.dokumen.show', $jadwal->id) }}" class="btn btn-outline-secondary mb-3">Kembali</a>

            {{-- Detail Daftar Tilik --}}
            <table class="table mb-3">
                <tr>
                    <td class="text-bold" width="15%">Prodi/Fakultas/Unit</td>
                    <td width="3%">:</td>
                    <td>
                        {{ $type === 'ps' ? $unit->nama . ' ' . $unit->jenjang->nama : $unit->nama }}
                    </td>
                </tr>
            </table>

            <table id="daftar-tilik" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Pernyataan</th>
                        <th class="text-center">Jawaban</th>
                        <th class="text-center">Link</th>
                        <th class="text-center">Catatan</th>
                        @if (!$expired)
                            <th class="text-center">Temuan Negatif</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($daftarTilik as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $item->form->instrumen->kode }}</td>
                            <td>{{ $item->form->instrumen->pernyataan }}</td>
                            <td>
                                @foreach ($item->form->jawaban_auditee as $jawaban)
                                    {{ $jawaban->jawaban }}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($item->form->link as $link)
                                    <p style="margin: 0; padding: 0;">
                                        {{ $loop->iteration }}.
                                        <a href="{{ $link->link }}" target="_blank">
                                            {{ $link->link }}
                                        </a>
                                    </p>
                                @endforeach
                            </td>
                            <td>{{ $item->catatan }}</td>

                            @if (!$expired)
                                <td class="text-center">
                                    <button class="btn btn-{{ !$item->ptk ? 'success' : 'danger' }}"
                                        id="edit-hapus-ptk_{{ $item->id }}">{{ !$item->ptk ? 'Tambah' : 'Hapus' }}
                                        Temuan Negatif</button>

                                    {{-- Loading --}}
                                    <button id="button-edit-hapus-ptk-loading-{{ $item->id }}"
                                        class="btn btn-{{ !$item->ptk ? 'success' : 'danger' }} d-none" type="button"
                                        disabled>
                                        <span class="spinner-border spinner-border-sm" role="status"
                                            aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('script')
    <!-- DataTables & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>



    <!-- Page specific script -->
    <script>
        $(function() {
            $("#daftar-tilik").DataTable({
                "responsive": true,
                "autoWidth": false,
                "pageLength": 25,
                "columnDefs": [{
                        "width": "5%",
                        "targets": [0]
                    },
                    {
                        "width": "5%",
                        "targets": [1]
                    },
                    {
                        "width": "20%",
                        "targets": [2]
                    },
                    {
                        "width": "20%",
                        "targets": [3]
                    },
                    {
                        "width": "15%",
                        "targets": [4]
                    },
                    {
                        "width": "20%",
                        "targets": [5]
                    },
                ]
            });
        })
    </script>

    <script>
        $(document).ready(function() {
            // Edit Hapus PTK
            $(document).on('click', '[id^=edit-hapus-ptk_]', function() {
                var id = $(this).attr('id').split('_')[1];

                $('[id^=edit-hapus-ptk_' + id + ']').addClass('d-none');

                $('#button-edit-hapus-ptk-loading-' + id).removeClass('d-none');

                edit_hapus_ptk(id);
            });

        });

        // CSRF Token
        function edit_hapus_ptk(jawabanId) {
            $.ajax({
                url: "{{ route('auditor.dokumen.edit_hapus_ptk', ['jawabanId' => ':jawabanId']) }}"
                    .replace(':jawabanId', jawabanId),
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                    }).then((result) => {
                        window.location.reload();
                    });

                    $('[id^=edit-hapus-ptk_' + jawabanId + ']').removeClass('d-none');
                    $('#button-edit-hapus-ptk-loading-' + jawabanId).addClass('d-none');

                },
                error: function(xhr) {
                    var response = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.message,
                    });

                    $('[id^=edit-hapus-ptk_' + jawabanId + ']').removeClass('d-none');
                    $('#button-edit-hapus-ptk-loading-' + jawabanId).addClass('d-none');
                }
            });
        }
    </script>
@endsection
