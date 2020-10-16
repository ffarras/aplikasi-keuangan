<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Keuangan Azura Labs</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:#333;
            text-align:left;
            font-size:18px;
            margin:0;
        }
        .page{
            max-width: 80em;
            margin: 0 auto;
        }
        table th,
        table td{
            text-align: left;
        }
        table.layout{
            width: 100%;
            border-collapse: collapse;
        }
        table.display{
            margin: 1em 0;
        }
        table.display th{
            border: 1px solid #B3BFAA;
            padding: .5em 1em;
            text-align: center; 
            vertical-align: middle;
        }
        table.display td{
            border: 1px solid #B3BFAA;
            padding: .5em 1em;
        }
​
        table.display th{ background: #D5E0CC; }
        table.display td{ background: #fff; }
​
        table.responsive-table{
            box-shadow: 0 1px 10px rgba(0, 0, 0, 0.2);
        }
​
        .listcust {
            margin: 0;
            padding: 0;
            list-style: none;
            display:table;
            border-spacing: 10px;
            border-collapse: separate;
            list-style-type: none;
        }
​
        .customer {
            padding-left: 600px;
        }

        th{
            text-align: center;
            background-color: #f0f0f0;
        }

        caption{
            font-size:38px;
        }

        img{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <center>
        <img src="{{ public_path('/atlantis/images/logo-min.png') }}" height="48px" alt="navbar brand">
        <caption>Laporan Keuangan Azura Labs</caption>
        <h4 style="line-height: 0px;">Periode {{ $startdate }} sampai {{ $enddate }}</h4>
        </center>
    </div>
    <div class="page">
        <table class="layout display responsive-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Account</th>
                    <th>Aktivitas</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            @if ($union && $total)
            <tbody>
                @forelse ($union as $uni)
                    <tr>
                        <td>{{ $loop->iteration}}</td>
                        <td>{{$uni->jenis}}</td>
                        <td>{{$uni->tanggal}}</td>
                        <td>{{$uni->account->nama}}</td>
                        <td>{{$uni->aktivitas}}</td>
                        <td>{{$uni->jumlah}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align: right;">Total</th>
                    <td>{{number_format($total, 0, '', ',')}}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</body>
</html>