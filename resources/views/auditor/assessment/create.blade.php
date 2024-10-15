@extends('components.layout.auditee_layout')

@section('content')
    <form
        action="{{ route('auditor.peer-assessment.store', ['auditor' => $auditorId, 'jadwalAudit' => $jadwalId, 'unit' => $unitId, 'type' => $type]) }}"
        method="post" id="create-form" enctype="multipart/form-data" class="block">
        @csrf

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{ $title }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($pertanyaan as $item)
                            @php
                                $jawaban = $jawabans->where('assessment_form_id', $item->id)->first();
                                $key = 'jawaban_' . $item->id;
                                $session = $sessionFormData[$key] ?? ($jawaban ? $jawaban->jawaban : '');
                            @endphp
                            <div class="form-group mb-4">
                                <label for="" style="font-weight: 500">
                                    {{ $loop->iteration }}.
                                    {{ $item->assessment_pertanyaan->pertanyaan }}
                                </label>
                                <span class="text-danger">&#42;</span>
                                <div class="d-flex">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <div class="custom-control custom-radio mr-5">
                                            <input class="custom-control-input jawaban" type="radio"
                                                id="customRadio-{{ $item->id }}-{{ $i }}"
                                                name="jawaban_{{ $item->id }}" value="{{ $i }}"
                                                {{ $session == $i ? 'checked' : '' }}
                                                {{ isset($status) && $status->status == 'selesai' ? 'disabled' : '' }}>

                                            <label for="customRadio-{{ $item->id }}-{{ $i }}"
                                                class="custom-control-label">
                                                <span>{{ $i }}</span><br>
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                                @if ($errors->has('jawaban_' . $item->id))
                                    <span class="text-danger d-block" style="font-size: 14px">
                                        {{ $errors->first('jawaban_' . $item->id) }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="pagination-container">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div class="pagination-wrapper">
                                <div class="mb-3">
                                    <label for="">Keterangan Skor</label>
                                    <li>[1] Sangat Tidak Setuju</li>
                                    <li>[2] Tidak Setuju</li>
                                    <li>[3] Cukup Setuju</li>
                                    <li>[4] Setuju</li>
                                    <li>[5] Sangat Setuju</li>
                                </div>

                                <input type="hidden" name="status" value="selesai">
                                @if (isset($status) && $status->status == 'selesai')
                                @else
                                    <div class="d-inline">
                                        <button type="submit" id="button-submit" class="btn btn-primary">Submit</button>
                                        <button id="button-submit-loading" class="btn btn-primary d-none" type="button"
                                            disabled>
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Loading...
                                        </button>
                                    </div>
                                @endif

                                <a href="{{ route('auditor.peer-assessment') }}"
                                    class="btn btn-outline-secondary ">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('style')
    <style>
        .pagination-container {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #f4f6f9;
            padding: 10px 0;
        }


        .pagination-wrapper {
            /* display: flex; */
            flex-wrap: wrap;
        }

        .pagination-wrapper .pagination {
            display: flex;
            flex-wrap: wrap;
        }

        .pagination-wrapper .page-item {
            flex: 1 0 1;
        }
    </style>
@endsection

@section('script')
    @if (session('error_message'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error_message') }}"
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            var form = $('#create-form');

            form.on('submit', function(e) {
                $('#button-submit').addClass('d-none');
                $('#button-submit-loading').removeClass('d-none');
            });

            save_session();

            $('.jawaban').on('change', function() {
                save_session();
            });

        });


        // Save ke Session
        function save_session() {
            const formData = new FormData(document.getElementById('create-form'));

            $.ajax({
                url: '{{ route('auditor.peer-assessment.session', ['auditor' => $auditorId, 'jadwalAudit' => $jadwalId, 'unit' => $unitId]) }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        console.log('Session berhasil tersimpan.');
                    }
                },
                error: function(response) {
                    console.error('Error.');
                }
            });
        }
    </script>
@endsection
