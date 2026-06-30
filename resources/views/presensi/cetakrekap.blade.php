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
                font-size: 10px;
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

    <body class="A4 landscape">

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
                                Rekap Presensi Karyawan Periode {{ $namabulan[$bulan] }} {{ $tahun }}</span>
                            <br>
                            <span><i>Jl. Anjasmoro No. 22, Kota Malang, Jawa Timur</i></span>
                        </div>
                    </td>
                </tr>
            </table>


            <table class="tabelpresensi" border="1">
                <thead>
                    <tr>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2">Nama Karyawan</th>
                        <th colspan="31">Tanggal</th>
                        <th rowspan="2">TH</th>
                        <th rowspan="2">TT</th>
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= 31; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap as $d)
                        <tr>
                            <td>{{ $d->nik }}</td>
                            <td>{{ $d->nama_lengkap }}</td>

                            @php
                                $totalhadir = 0;
                                $totalterlambat = 0;
                            @endphp

                            @for ($i = 1; $i <= 31; $i++)
                                @php
                                    $tgl = 'tgl_' . $i;
                                    if (empty($d->$tgl)) {
                                        $hadir = ['', ''];
                                    } else {
                                        $hadir = explode('-', $d->$tgl);
                                        $totalhadir += 1;
                                        if ($hadir[0] > $d->jam_masuk) {
                                            $totalterlambat += 1;
                                        }
                                    }
                                @endphp

                                <td>
                                    <span style="color: {{ $hadir[0] > $d->jam_masuk ? 'red' : '' }}">
                                        {{ !empty($hadir[0]) ? $hadir[0] : '-' }}
                                    </span><br>

                                    <span style="color: {{ $hadir[1] < $d->jam_pulang ? 'red' : '' }}">
                                        {{ !empty($hadir[1]) ? $hadir[1] : '-' }}
                                    </span>
                                </td>
                            @endfor

                            <td>{{ $totalhadir }}</td>
                            <td>{{ $totalterlambat }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            <table width="100%" style="margin-top: 100px">
                <tr>
                    <td></td>
                    <td style="text-align: center; margin-left: 5px">Malang, {{ date('d-m-Y') }}</td>
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
