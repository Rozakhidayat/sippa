<style>
  body {
    font-family: Arial, sans-serif;
    font-size: 11px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
  }

  .header-table {
    border: none;
    margin-bottom: 20px;
  }

  .header-table td {
    border: none;
  }

  .text-center {
    text-align: center;
  }

  .bg-light {
    background-color: #f9f9f9;
  }

  table.data {
    width: 100%;
    border-collapse: collapse;
    font-size: 11px;
  }

  table.data th {
    background-color: #eaeaea;
    text-align: center;
    font-weight: bold;
    padding: 6px;
    border: 1px solid #000;
  }

  table.data td {
    padding: 6px;
    border: 1px solid #000;
  }

  table.data tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .footer {
    margin-top: 20px;
    font-size: 10px;
    text-align: right;
  }
</style>

<table class="header-table">
  <tr>
    <td width="20%" style="text-align: right; vertical-align: middle;">
      <img src="{{ $logo }}" width="80">
    </td>

    <td width="30%" class="text-center" style="vertical-align: middle;">
      <h2 style="margin:0; font-size: 18px; text-transform: uppercase;">Laporan Pengajuan SIPPA</h2>
      <h3 style="margin:0; font-size: 14px;">PT PUPUK KUJANG</h3>
      <p style="margin:5px 0 0 0; font-size: 10px;">
        <small>Jl. Jend. A. Yani No. 39, Dawuan Tengah, Kec. Cikampek, Kabupaten Karawang, Jawa Barat 41373</small>
      </p>
      <p style="margin:5px 0 0 0; color: #555;">
        <small>Periode: {{ $periode }} | Departemen: {{ $departements }}</small>
      </p>
    </td>

    <td width="20%"></td>
  </tr>
</table>

<hr style="border: 1px solid #000; margin-top: -10px; margin-bottom: 20px;">

<table class="data">
  <thead>
    <tr>
      <th style="width: 5%">No</th>
      <th>No. Tiket</th>
      <th>Nama Aplikasi</th>
      <th>Pemohon</th>
      <th>Status</th>
      <th>Jenis Pengajuan</th>
      <th>Tanggal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($submissions as $index => $item)
    <tr>
      <td class="text-center">{{ $index + 1 }}</td>
      <td><strong>{{ $item->no_ticket }}</strong></td>
      <td>{{ $item->application_name }}</td>
      <td>{{ $item->user->name }}</td>
      <td class="text-center">{{ $item->status_label}}</td>
      <td class="text-center">{{ $item->type_development_label}}</td>
      <td class="text-center">{{ $item->submission_date->format('d F Y') }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="footer">
  Dicetak pada: {{ now('Asia/Jakarta')->format('d/m/Y H:i') }}
</div>