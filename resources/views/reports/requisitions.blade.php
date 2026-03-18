<!DOCTYPE html>
<html>
<head>
    <title>Requisition Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #006fba; }
        .header h1 { margin: 0; font-size: 28px; color: #006fba; text-transform: uppercase; }
        .header p { color: #666; margin-top: 8px; font-size: 13px; }
        .section { margin-bottom: 30px; }
        .section-title { font-size: 18px; font-weight: bold; border-bottom: 1px solid #ddd; padding-bottom: 8px; margin-bottom: 15px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; color: #555; text-transform: uppercase; font-size: 12px; }
        tr:nth-child(even) { background-color: #fdfdfd; }
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 30px; border: none; }
        .summary-table td { padding: 0; vertical-align: top; border: none; }
        .summary-box-cell { width: 32%; }
        .spacer { width: 2%; }
        .summary-box { border: 1px solid #e0e0e0; padding: 20px 10px; background-color: #fafafa; text-align: center; border-radius: 5px; }
        .summary-box h3 { margin-top: 0; font-size: 32px; color: #006fba; font-weight: bold; }
        .summary-box p { margin-bottom: 0; font-size: 14px; color: #666; font-weight: 500; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Requisition Summary Report</h1>
        <p>Generated on {{ date('F j, Y, \a\t g:i a') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-box-cell">
                <div class="summary-box">
                    <h3>{{ $totalForms }}</h3>
                    <p>Total Request Forms</p>
                </div>
            </td>
            <td class="spacer"></td>
            <td class="summary-box-cell">
                <div class="summary-box">
                    <h3>{{ $totalRequisitions }}</h3>
                    <p>Total Requisitions Created</p>
                </div>
            </td>
            <td class="spacer"></td>
            <td class="summary-box-cell">
                <div class="summary-box">
                    <h3>{{ $sentToCb }}</h3>
                    <p>Sent to Cost & Budgeting</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="section" style="margin-top: 40px;">
        <h2 class="section-title">Requisition Status Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th style="width: 150px; text-align: right;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuses as $status)
                    <tr>
                        <td>{{ $status->requisition_status ?? 'Pending/None' }}</td>
                        <td style="text-align: right; font-weight: bold;">{{ $status->total }}</td>
                    </tr>
                @endforeach
                @if($statuses->isEmpty())
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888; font-style: italic;">No requisitions found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>
