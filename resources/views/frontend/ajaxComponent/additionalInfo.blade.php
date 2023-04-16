<?php

$Attributes = key_exists('Attributes', $item) ? $item['Attributes'] : [];
?>

<table class="table table-bordered">
    @forelse($Attributes as $attribute)
        <tr>
            <td class="text-capitalize">{{$attribute['PropertyName']}}</td>
            <td class="text-capitalize">{{$attribute['Value'] ?? null}}</td>
        </tr>
    @empty
        <tr>
            <td>Additional Info</td>
            <td>Not Founded</td>
        </tr>
    @endforelse
</table>

