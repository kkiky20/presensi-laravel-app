    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <title>A4</title>

        <!-- Normalize or reset CSS with your favorite library -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

        <!-- Load paper.css for happy printing -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

        {{-- Google Fonts --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
            rel="stylesheet">

        <!-- Set page size here: A5, A4 or A3 -->
        <!-- Set also "landscape" if you need -->
        <style>
            @page {
                size: A4;
            }

            h2,
            #title {
                font-family: "Inter", sans-serif;
                font-size: 20px;
                font-weight: bold;
            }

            .title {
                margin-right: 15px;
            }

            div {
                margin-left: 15px;
                line-height: 25px;
            }

            .tabeldatakaryawan {
                margin-top: 10px;
            }

            .tabeldatakaryawan td {
                padding: 5px;
            }

            .tabelpresensi {
                width: 100%;
                margin-top: 20px;
                border-collapse: collapse;
            }

            .tabelpresensi tr th {
                border: 1px solid #131212;
                padding: 8px;
                background: #dbdbdb;
            }

            .tabelpresensi tr td {
                border: 1px solid #131212;
                padding: 6px;
                font-size: 12px;
            }

            .foto {
                width: 40px;
                height: 30px;

            }
        </style>
    </head>

    <!-- Set "A5", "A4" or "A3" for class name -->
    <!-- Set also "landscape" if you need -->

    <body class="A4">

        @php
            if (!function_exists('selisih')) {
                function selisih($jam_masuk, $jam_keluar)
                {
                    [$h, $m, $s] = explode(':', $jam_masuk);
                    $dtAwal = mktime($h, $m, $s, 1, 1, 1);
                    [$h, $m, $s] = explode(':', $jam_keluar);
                    $dtAkhir = mktime($h, $m, $s, 1, 1, 1);
                    $dtSelisih = $dtAkhir - $dtAwal;
                    $totalmenit = $dtSelisih / 60;
                    $jam = explode('.', $totalmenit / 60);
                    $sisamenit = $totalmenit / 60 - $jam[0];
                    $sisamenit2 = $sisamenit * 60;
                    $jml_jam = $jam[0];
                    return $jml_jam . ':' . round($sisamenit2);
                }
            }
        @endphp

        <!-- Each sheet element should have the class "sheet" -->
        <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
        <section class="sheet padding-10mm">

            <table style="width: 100%">
                <tr>
                    <td style="width: 30px;">
                        <img src="{{ asset('template') }}/assets/img/alfath-logo.webp" alt="" width="90">
                    </td>
                    <td>
                        <div>
                            <span id="title">PT. ALFATH CORPORATION INDONESIA <br>
                                Laporan Presensi Karyawan Periode {{ $namabulan[$bulan] }} {{ $tahun }}</span>
                            <br>
                            <span><i>Jl. Anjasmoro No. 22, Kota Malang, Jawa Timur</i></span>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="tabeldatakaryawan" style="margin-top: 20px ">
                <tr>
                    <td rowspan="6">
                        @php
                            $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                        @endphp
                        <img src="{{ url($path) }}" alt="" height="200" width="150">
                    </td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>
                        {{ $karyawan->nik }}
                    </td>
                </tr>
                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td>
                        {{ $karyawan->nama_lengkap }}
                    </td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>
                        {{ $karyawan->jabatan }}
                    </td>
                </tr>
                <tr>
                    <td>Nama Departemen</td>
                    <td>:</td>
                    <td>
                        {{ $karyawan->nama_dept }}
                    </td>
                </tr>
                <tr>
                    <td>No HP</td>
                    <td>:</td>
                    <td>
                        {{ $karyawan->no_hp }}
                    </td>
                </tr>
            </table>

            <table class="tabelpresensi">
                <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Foto</th>
                    <th>Jam Pulang</th>
                    <th>Foto</th>
                    <th>Keterangan</th>
                    <th>Jml Jam</th>
                </tr>
                @foreach ($presensi as $d)
                    @php
                        $path_in = Storage::url('uploads/absensi/' . $d->foto_in);
                        $path_out = Storage::url('uploads/absensi/' . $d->foto_out);
                        $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }} </td>
                        <td>{{ $d->jam_in }}</td>
                        <td><img src="{{ url($path_in) }}" alt="" class="foto"> </td>
                        <td>{{ $d->jam_out != null ? $d->jam_out : 'Belum Absen' }}</td>
                        <td>
                            @if ($d->jam_out != null)
                                <img src="{{ url($path_out) }}" alt="" class="foto">
                            @else
                                <img src="{{ asset('template') }}/assets/img/camera.png" alt="" width="40px">
                            @endif
                        </td>

                        <td>
                            @if ($d->jam_in > $d->jam_masuk)
                                Terlambat {{ $jamterlambat }}
                            @else
                                Tepat Waktu
                            @endif
                        </td>
                        <td>
                            @if ($d->jam_out != null)
                                @php
                                    $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                                @endphp
                            @else
                                @php
                                    $jmljamkerja = 0;
                                @endphp
                            @endif
                            {{ $jmljamkerja }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <table width="100%" style="margin-top: 100px">
                <tr>
                    <td colspan="2" style="text-align: right; margin-left: 5px">Malang, {{ date('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td style="text-align:center; vertical-align:bottom;" height="100px">
                        <u>Muhammad Rizky</u><br>
                        <i><b>HRD Manager</b></i>
                    </td>
                    <td style="text-align:center; vertical-align:bottom;">
                        <u>Inas</u><br>
                        <i><b>Direktur</b></i>
                    </td>
                </tr>
            </table>

        </section>

    </body>

    </html>
