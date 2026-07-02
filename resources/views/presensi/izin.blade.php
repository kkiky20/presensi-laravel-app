@extends('layouts.presensi')

@section('header')
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Izin / Sakit</div>
        <div class="right"></div>
    </div>

    <style>
        .historicard {
            border-radius: 12px;
            margin-bottom: 10px;
        }

        .historicontent {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .left-content {
            display: flex;
            flex: 1;
        }

        .iconpresensi {
            margin-right: 12px;
        }

        .datapresensi h5 {
            margin-bottom: 3px;
            font-size: 16px;
            font-weight: 600;
        }

        .datapresensi small {
            color: #6c757d;
            display: block;
            margin-bottom: 5px;
        }

        .datapresensi p {
            margin: 0;
            font-size: 13px;
        }

        .status {
            text-align: right;
            min-width: 90px;
        }

        .status p {
            margin-top: 8px;
            margin-bottom: 0;
            font-weight: bold;
            font-size: 13px;
        }
    </style>
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">

            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                </div>
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col">

            @foreach ($dataizin as $d)
                @php
                    if ($d->status == 'i') {
                        $status = 'Izin';
                    } elseif ($d->status == 's') {
                        $status = 'Sakit';
                    } elseif ($d->status == 'c') {
                        $status = 'Cuti';
                    } else {
                        $status = 'Data Tidak Ditemukan';
                    }
                @endphp

                <div class="card historicard">
                    <div class="card-body">

                        <div class="historicontent">

                            <div class="left-content">

                                <div class="iconpresensi">
                                    @if ($d->status == 'i')
                                        <ion-icon name="document-outline" style="font-size:48px;color:#2196F3;">
                                        </ion-icon>
                                    @elseif ($d->status == 's')
                                        <ion-icon name="medkit-outline" style="font-size:48px;color:red;"></ion-icon>
                                    @endif
                                </div>

                                <div class="datapresensi">
                                    <h5>
                                        {{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }}
                                        ({{ $status }})
                                    </h5>

                                    <small>
                                        {{ date('d-m-Y', strtotime($d->tgl_izin_dari)) }}
                                        s/d
                                        {{ date('d-m-Y', strtotime($d->tgl_izin_sampai)) }}
                                    </small>

                                    <p>{{ $d->keterangan }}</p>

                                    <p>
                                        @if (!empty($d->doc_sid))
                                        <span style="color:blue">
                                            <ion-icon name="document-attach-outline"></ion-icon> Lihat SID
                                        </span>
                                        @endif
                                    </p>
                                </div>

                            </div>

                            <div class="status">

                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">
                                        Pending
                                    </span>
                                @elseif ($d->status_approved == 1)
                                    <span class="badge bg-success">
                                        Disetujui
                                    </span>
                                @elseif ($d->status_approved == 2)
                                    <span class="badge bg-danger">
                                        Ditolak
                                    </span>
                                @endif

                                <p>
                                    {{ hitunghari($d->tgl_izin_dari, $d->tgl_izin_sampai) }}
                                    Hari
                                </p>

                            </div>

                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>

    <div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">

        <a href="#" class="fab bg-primary" data-toggle="dropdown">
            <ion-icon name="add-outline"></ion-icon>
        </a>

        <div class="dropdown-menu">

            <a class="dropdown-item bg-primary" href="/izinabsen">
                <ion-icon name="document-outline"></ion-icon>
                <p>Izin Absen</p>
            </a>

            <a class="dropdown-item bg-primary" href="/izinsakit">
                <ion-icon name="medkit-outline"></ion-icon>
                <p>Sakit</p>
            </a>

            <a class="dropdown-item bg-primary" href="/izincuti">
                <ion-icon name="calendar-outline"></ion-icon>
                <p>Cuti</p>
            </a>

        </div>

    </div>
@endsection
