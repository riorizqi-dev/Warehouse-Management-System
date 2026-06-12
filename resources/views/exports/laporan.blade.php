<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body{font-family: DejaVu Sans, Arial, Helvetica, sans-serif; font-size:12px;}
        h3{margin:0 0 10px 0}
        table{width:100%; border-collapse:collapse}
        th,td{border:1px solid #ccc; padding:6px 8px;}
        th{background:#f5f5f5; text-align:left}
    </style>
    <title>{{ $title ?? 'Laporan' }}</title>
  </head>
  <body>
    <h3>{{ $title ?? 'Laporan' }}</h3>
    <table>
        <thead>
            <tr>
                @foreach($columns as $c)
                    <th>{{ $c }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $r)
                <tr>
                    @foreach($r as $v)
                        <td>{{ $v }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
  </body>
</html>



