<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/x-icon" href="{{ public_path('TTCOA.ico') }}">
    <title>Procurement Requisition Form - {{ $requisitionForm->form_code }}</title>
    <style>
        @page {
            margin: 0.5cm;
            size: legal portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 0;
            padding: 10px;
        }

        .form-code {
            position: absolute;
            top: 0;
            right: 0;
            font-weight: bold;
            font-size: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            width: 60px;
            height: auto;
        }

        .title-bar {
            background-color: #a8a7a7;
            text-align: center;
            padding: 8px;
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 2px;
        }

        .subtitle-bar {
            background-color: #eff300;
            text-align: center;
            padding: 6px;
            font-weight: bold;
            font-size: 9pt;
            margin-bottom: 2px;
            font-weight: bold;
        }

        .note-bar {
            background-color: #ffffff;
            text-align: center;
            padding: 4px;
            font-size: 8pt;
            margin-bottom: 10px;
            color: red;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 9pt;
        }

        .info-table .label {
            font-weight: bold;
            background-color: #f2f2f2;
            width: 25%;
        }

        .section-header {
            background-color: #adadad;
            padding: 6px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        .items-table {
            font-size: 8pt;
        }

        .items-table th {
            background-color: #d9d9d9;
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-weight: bold;
        }

        .items-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .signature-table {
            margin-top: 15px;
        }

        .signature-table th {
            background-color: #d9d9d9;
            border: 1px solid #000;
            padding: 6px;
            font-weight: bold;
            text-align: center;
        }

        .signature-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            height: 40px;
        }

        .procurement-section {
            background-color: #d9d9d9;
            padding: 6px;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }

        .two-col {
            display: table;
            width: 100%;
        }

        .two-col .col {
            display: table-cell;
            width: 50%;
            padding: 2px;
        }

        .text-small {
            font-size: 8pt;
        }

        .text-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    {{-- Footer --}}
    <div class="form-code">
        Form #: {{ $requisitionForm->form_code }}
    </div>
    {{-- Header with Logo --}}
    <div class="header">
        <img src="{{ public_path('coa.png') }}" alt="Ministry Logo" class="logo" style="width: 180px;">
    </div>

    {{-- Top Information Section --}}
    <table class="info-table">
        <tr>
            <td colspan="6"
                style="background-color: #a8a7a7; text-align: center; padding: 8px; font-weight: bold; font-size: 12pt; margin-bottom: 2px;">
                PROCUREMENT REQUISITION</td>
        </tr>
        <tr>
            <td colspan="6" class="subtitle-bar">
                {{ strtoupper($requisitionForm->category) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="note-bar">
                FORM MUST BE PRINTED ON LEGAL SIZE (8.5 X 14) PAPER</td>
        </tr>
        <tr>
            <td class="label">Requesting Unit / Department/ Division</td>
            <td colspan="1">{{ $requisitionForm->requestingUnit->name ?? '' }}</td>
            <td class="label">Name of Requesting Head of Department/Unit/Division</td>
            <td colspan="3">{{ $requisitionForm->headOfDepartment->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Name of Contact Person</td>
            <td colspan="1">{{ $requisitionForm->contactPerson->name ?? '' }}</td>
            <td class="label">Date (dd/mm/yyyy)</td>
            <td colspan="3">
                {{ $requisitionForm->date ? \Carbon\Carbon::parse($requisitionForm->date)->format('d/m/Y') : '' }}</td>
        </tr>
        <tr>
            <td class="label">Contact Info. (phone/email)</td>
            <td colspan="5">{{ $requisitionForm->contactPerson->email ?? '' }}</td>
        </tr>
    </table>

    {{-- Procurement Request Section --}}
    <table class="info-table">
        <tr>
            <td colspan="6" class="section-header">PROCUREMENT REQUEST (Please ensure this form is submitted with a
                covering memo explaining the request)</td>
        </tr>
        <tr>
            <td colspan="6" class="label">Justification for Request</td>
        </tr>
        <tr>
            <td colspan="6" style="min-height: 40px; padding: 8px;">
                {{ $requisitionForm->justification ?? '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Location of Delivery/Installation/Works</td>
            <td colspan="3">{{ $requisitionForm->location_of_delivery ?? '' }}</td>
            <td class="label">Date Required By (dd/mm/yyyy)</td>
            <td>{{ $requisitionForm->date_required_by ? \Carbon\Carbon::parse($requisitionForm->date_required_by)->format('d/m/Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Estimated Value (TTD)</td>
            <td colspan="5">${{ number_format($requisitionForm->estimated_value ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td colspan="6" class="text-small"
                style="padding: 6px; background-color: #ffffff;color:red;text-align:center;font-weight:bold">
                Please contact the Finance and Accounts department to obtain the following information
            </td>
        </tr>
        <tr>
            <td class="label">Availability of Funds (Yes/No)</td>
            <td>{{ $requisitionForm->availability_of_funds ? 'Yes' : 'No' }}</td>
            <td class="label">Verified by Accounts (Yes/No)</td>
            <td>{{ $requisitionForm->verified_by_accounts ? 'Yes' : 'No' }}</td>
            <td class="label">Vote No.</td>
            <td>
                @if ($requisitionForm->votes->count() > 0)
                    {{ $requisitionForm->votes->pluck('number')->implode(', ') }}
                @endif
            </td>
        </tr>
    </table>

    {{-- Items Table --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 20%;">ITEM DESCRIPTION</th>
                <th style="width: 8%;">QTY. IN STOCK</th>
                <th style="width: 8%;">QTY. REQUESTING</th>
                <th style="width: 10%;">UNIT OF MEASURE (each)</th>
                <th style="width: 12%;">SIZE (length, height, capacity, weight, volume, etc.)</th>
                <th style="width: 10%;">COLOUR</th>
                <th style="width: 14%;">BRAND/ MODEL (if applicable)</th>
                <th style="width: 14%;">OTHER (any additional specifications)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="9" class="text-small"
                    style="padding: 4px; background-color: #ffffff;color:red;text-align:center;font-weight:bold">
                    For items with multiple specifications, please attach additional documentation as necessary
                </td>
            </tr>
            @foreach ($requisitionForm->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="text-align: left;">{{ $item->name }}</td>
                    <td>{{ $item->qty_in_stock }}</td>
                    <td>{{ $item->qty_requesting }}</td>
                    <td>{{ $item->unit_of_measure }}</td>
                    <td>{{ $item->size }}</td>
                    <td>{{ $item->colour }}</td>
                    <td>{{ $item->brand_model }}</td>
                    <td>{{ $item->other }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Requesting Head of Department Section --}}
    <table class="signature-table">
        <thead>
            <tr>
                <td colspan="3" class="section-header">REQUESTING HEAD OF DEPARTMENT/UNIT/DIVISION</td>
            </tr>
            <tr>
                <th style="width: 33%;">Name</th>
                <th style="width: 33%;">Signature</th>
                <th style="width: 34%;">Date (dd/mm/yyyy)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $requisitionForm->headOfDepartment->name ?? '' }}</td>
                <td>{{ $requisitionForm->hod_approval ? $requisitionForm->headOfDepartment->initials ?? '' : '' }}
                </td>
                <td>{{ $requisitionForm->hod_approval_date && $requisitionForm->hod_approval ? $requisitionForm->hod_approval_date->format('d/m/Y') : '' }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Non-Objection Section --}}
    <table class="signature-table">
        <thead>
            <tr>
                <td colspan="4" class="section-header">NON-OBJECTION REQUIRED FROM (as applicable):</td>
            </tr>
            <tr>
                <th style="width: 30%;">POSITION</th>
                <th style="width: 25%;">Name</th>
                <th style="width: 25%;">Signature</th>
                <th style="width: 20%;">Date (dd/mm/yyyy)</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Permanent Secretary</td>
                @php $officer = $officersByRole['Permanent Secretary']; $date = $datesByRole['Permanent Secretary']; @endphp
                <td>{{ $officer?->name ?? '' }}</td>
                <td>{{ $officer?->initials ?? '' }}</td>
                <td>{{ $date ? $date->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td>Deputy Permanent Secretary</td>
                @php $officer = $officersByRole['Deputy Permanent Secretary']; $date = $datesByRole['Deputy Permanent Secretary']; @endphp
                <td>{{ $officer?->name ?? '' }}</td>
                <td>{{ $officer?->initials ?? '' }}</td>
                <td>{{ $date ? $date->format('d/m/Y') : '' }}</td>
            </tr>
            <tr>
                <td>Chief Medical Officer</td>
                @php $officer = $officersByRole['Chief Medical Officer']; $date = $datesByRole['Chief Medical Officer']; @endphp
                <td>{{ $officer?->name ?? '' }}</td>
                <td>{{ $officer?->initials ?? '' }}</td>
                <td>{{ $date ? $date->format('d/m/Y') : '' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Procurement Section --}}
    <table class="info-table">
        <tr>
            <td colspan="2" class="procurement-section">FOR PROCUREMENT USE ONLY</td>
        </tr>
        <tr>
            <td class="label" style="width: 30%;">Date Received (dd/mm/yyyy)</td>
            <td style="width: 70%;">
                {{ $requisitionForm->reporting_officer_approval_date ? \Carbon\Carbon::parse($requisitionForm->reporting_officer_approval_date)->format('d/m/Y') : '' }}
            </td>
        </tr>
        <tr>
            <td class="label">Procurement Officer Assigned</td>
            <td>{{ $requisitionForm->requisition?->procurement_officer?->name ?? '' }}</td>
        </tr>
        <tr>
            <td class="label">Seen by</td>
            <td>{{ Str::of($requisitionForm->requisition?->created_by ?? '')->replace('.', ' ')->title() }}</td>
        </tr>
        <tr>
            <td class="label">Expected Date of Completion</td>
            <td>&nbsp;</td>
        </tr>
    </table>
</body>

</html>
