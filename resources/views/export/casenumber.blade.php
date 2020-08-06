<table>
    <thead>
    <tr>
        <td colspan="7">Case Number : {{ $casenumber->case_number }}</td>
    </tr>
    <tr  style="background-color: black;">
        <th>No.</th>
        <th>Date</th>
        <th>Case Number</th>
        <th>Payment Method</th>
        <th>Description</th>
        <th>Receipt</th>
        <th>Ammount (Rp)</th>
    </tr>
    </thead>
    <tbody>
        @foreach ( $expenses as $key => $value )
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ date("d-M-Y", strtotime($value->created_at)) }}</td>
            <td>{{ $casenumber->case_number }}</td>
            <td>{{ $value->type }}</td>
            <td>{{ $value->description }}</td>
            <td>@if ( $value->receipt != "" ) Y @else N @endif</td>
            <td>Rp. {{ number_format($value->ammount)}}</td>
        </tr>
    
        @endforeach
        <tr style="background-color: black;">
            <td></td>
            <td>Date</td>
            <td>Name</td>
            <td>Signature</td>
            <td></td>
            <td>Total</td>
            <td>Rp. {{ number_format($casenumber->total_expenses)}}</td>
        </tr>
        <tr>
            <td>Claimed By </td>
            <td>{{ date("d-m-Y") }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Verified by (Divisional Manager) </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>