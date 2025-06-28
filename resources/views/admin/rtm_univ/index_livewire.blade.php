@extends('components.layout.main_layout')

@section('content')
    <div class="container-fluid">
        {{-- Render komponen Livewire --}}
        @livewire('admin.rtm-univ.jadwal-rtm-index')
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        .dropdown-item i {
            margin-right: 5px; 
        }

        .dropdown-item:hover {
            background-color: #f8f9fa; 
        }
        .btn-fixed-size {
            width: 120px; 
            height: 38px; 
            display: flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap; 
        }

        .dropdown .btn-fixed-size {
            width: 120px; 
        }
    </style>
@endsection

@section('script')
    {{-- DataTables JavaScript tidak diperlukan untuk Livewire. Jika Anda tetap ingin menggunakannya,
         perhatikan bagaimana Livewire me-render ulang tabel. Lebih baik Livewire yang mengelola tabelnya. --}}
    {{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script> --}}

    {{-- Script untuk SweetAlert2 disesuaikan dengan Livewire --}}
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection