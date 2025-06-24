@extends('components.layout.main_layout')

@section('content')
    {{-- Di sinilah komponen Livewire Anda akan disisipkan --}}
    <livewire:rtm-jadwal-livewire-index /> {{-- Sesuaikan nama tag dengan nama komponen Anda --}}
@endsection

@section('style')
    {{-- Gaya khusus yang dibutuhkan oleh Livewire component ini (misalnya untuk x-cloak Alpine.js) --}}
    <style>
        [x-cloak] { display: none !important; }
        /* Pastikan gaya untuk tombol dan dropdown yang Anda butuhkan juga ada di sini atau main_layout */
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


