<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="{{ asset('assets/css/outcomingpdf.css') }}">
  <title>{{ $outcoming->transmittal_title }}</title>
</head>

<body>
  <div class="header">
    <div class="sender-identity">
      <div class="sender-name">{{ $outcoming->sender }}</div>
      <div class="sender-address">
        {{ $outcoming->sender_address }}
      </div>
    </div>
    <div class="logo-section">
      <div class="sender-logo-section">
        <img src="{{ asset('assets/config/icon.png') }}" alt="Icon" class="sender-logo">
      </div>
      <div class="recipient-logo-section">
        <img src="{{ asset('assets/config/logo.png') }}" alt="Logo" class="recipient-logo">
      </div>
    </div>
  </div>

  <table class="table-detail">
    <thead>
      <tr>
        <th colspan="4" class="header-detail">
          DOCUMENTS & DRAWINGS TRANSMITTAL
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td rowspan="3" class="td-header-detail" style="border-right: none; border-bottom: none; width: 5% !important">TO</td>
        <td rowspan="3" class="td-data-detail" style="border-left: none; border-bottom: none; width: 45% !important">
          <div class="recipient-name">:
            Project Management Unit
            PT Geo Dipa Energi (Persero)
          </div>
          <br>
          <div class="recipient-address">
            Jl. Akses Tol Soroja Blok Sumakamanah Parung Serab No. 22
            Desa Parungserab, Kecamatan Soreang
            Kabupaten Bandung 40921

          </div>
        </td>
        <td class="td-header-detail" style="width: 14% !important">Transmittal Date</td>
        <td class="td-data-detail" style="width: 25% !important">04 October 2021</td>
      </tr>
      <tr>
        <td class="td-header-detail">Transmittal No.</td>
        <td class="td-data-detail">001-TRE-PRO.05-SPC-GDE-2021</td>
      </tr>
      <tr>
        <td class="td-header-detail">GDE Contract No.</td>
        <td class="td-data-detail">CS-GDE-D2P2-001</td>
      </tr>
      <tr>
        <td class="td-header-detail" style="border-top: none; border-right: none; border-bottom: none">ATT</td>
        <td class="td-data-detail" style="border-top: none; border-left: none; border-bottom: none">:</td>
        <td class="td-header-detail" style="border-bottom: none;">Contract Title:</td>
        <td class="td-data-detail" style="border-bottom: none;">Geothermal Power Generation Project (GPGP)</td>
      </tr>
      <tr>
        <td class="td-header-detail" style="border-top: none; border-right: none">CC</td>
        <td class="td-data-detail" style="border-top: none; border-left: none">:</td>
        <td class="td-header-detail" style="border-top: none;">Sub Title:</td>
        <td class="td-data-detail" style="border-top: none;"></td>
      </tr>
    </tbody>
  </table>

  <br>
  <table class="table-document">
    <thead>
      <tr class="header-detail-document">
        <th style="width: 5%">No.</th>
        <th style="width: 25%">Drawing / Document No.</th>
        <th style="width: 5%">Revision</th>
        <th style="width: 5%">Issue Purpose</th>
        <th style="width: 5%">Sheet Size</th>
        <th style="width: 5%">Review Status</th>
        <th style="width: 50%">Description of Document or Title</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>qwerty</td>
        <td>1234</td>
        <td>1234</td>
        <td>1234</td>
        <td>1234</td>
        <td>1234</td>
      </tr>
    </tbody>
  </table>

  <br>
  <div class="footer">
    <table class="table-signature">
      <tbody>
        <tr>
          <td style="width: 28%; text-align: center;">Issued By</td>
          <td style="width: 28%; text-align: center;">Approved By</td>
          <td colspan="2" style="width: 44%;">I hereby acknowledge receipt of the mentioned documents</td>
        </tr>
        <tr style="vertical-align: top">
          <td colspan="2"></td>
          <td style="width: 22%;">Name</td>
          <td style="width: 22%;">Sign</td>
        </tr>
        <tr style="height: 29px;">
          <td colspan="2"></td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td style="text-align: center">(Document Control)</td>
          <td style="text-align: center">(Project Manager)</td>
          <td>Position</td>
          <td>Date</td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td style="font-weight: bold">Additional Remark by Sender :</td>
          <td style="padding: 0 0 8px 0; vertical-align: bottom">
            <div style="border-bottom: 1px solid black"></div>
          </td>
          <td colspan="2"></td>
        </tr>
        <tr style="height: 29px">
          <td></td>
          <td style="padding: 0 0 8px 0; vertical-align: bottom">
            <div style="border-bottom: 1px solid black"></div>
          </td>
          <td colspan="2"></td>
        </tr>
      </tbody>
    </table>
  </div>
  <footer>
    PLEASE RETURN COPY OF TRANSMITTAL WITH SIGNATURE
  </footer>
</body>

</html>