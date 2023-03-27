<x-mail::message>
# Hello {{ $name }},

Below is a copy of the receipt
for payment of your fees

<x-mail::panel>

# <center>Payment Receipt</center>

<table style="width:100%; margin:2.5%">
    <tr>
        <th colspan=2>Details</th>
    </tr>
    <tr>
        <td>
            Name:
        </td>
        <td >
            {{ $name }} 
        </td>
    </tr>
    <tr>
        <td>
            Status:
        </td>
        <td>
            {{ $status }} 
        </td>
    </tr>
    <tr>
        <td>
        Paid Amount:
        </td>
        <td>
            &#8358; {{ $amount }} 
        </td>
    </tr>
    <tr>
        <td>
            Date:
        </td>
        <td>
            {{ $date }} 
        </td>
    </tr>
</table>

</x-mail::panel>

<x-mail::button :url="''">
Download Copy
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
