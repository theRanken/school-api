<x-mail::message>
# Hello {{ $user }},

Below is a copy of the invoice generated
for payment of your fees

<x-mail::panel>

# <center>Invoice</center>

<table style="width:100%; margin:2.5%">
    <tr>
        <th colspan=2>Fees Details</th>
    </tr>
    <tr>
        <td>
            Name:
        </td>
        <td >
            {{ $user }} 
        </td>
    </tr>
    <tr>
        <td>
            Class:
        </td>
        <td>
            {{ $class }} 
        </td>
    </tr>
    <tr>
        <td>
            Fees Due:
        </td>
        <td>
            &#8358; {{ $fee }} 
        </td>
    </tr>
    <tr>
        <td>
            Date:
        </td>
        <td>
            {{date_format($date, "d M Y H:i:s A")}} 
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
