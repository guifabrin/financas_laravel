<table>
	<tbody>
    @foreach ($account->invoices()->get() as $i)
      <?php $value = 0 ?>
  		@foreach ($i->transactions()->orderBy('date')->get() as $t)
  			<?php $value += $t->value?>
        <tr>
          <td>{{$t->invoice_id}}</td>
          <td>{{$t->date}}</td>
          <td>{{$t->description}}</td>
          <td>{{$t->value}}</td>
        </tr>
      @endforeach
      <tr><th>{{$value}}</th></tr>
    @endforeach
	</tbody>
</table>