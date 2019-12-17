<html>
	
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<script>

	$(document).ready(function() {
		
		$('button#kod_produktu').on('click', function() {
			  window.location.href = '/import/' + $('input').val();
			  return false;
		});

		$('button#od_nowa').on('click', function() {
			
			  window.location.href = '/import/';
			  return false;
		});

	});

</script>
</head>	
<body>


	<div>
		
@include('stats')

<form action="{{ route('import') }}" method="GET"> 
<input type="text" id="kod_produktu" name="kod_produktu" class="form-control" style="width: 200px; margin: 20px; display: inline-block;"placeholder="KOD PRODUKTU">
<button id="kod_produktu" class="btn btn-success" style="margin-top: -3px" type="submit">Szukaj</button>
<button id="od_nowa" class="btn btn-success" style="margin-top: -3px">Od nowa</button>
</form>

<table class="table table-bordered" style="margin: 20px; width: 40%">

	<tr>
		<th>Kod produktu</th>
		<th>Ilość</th>
		<th>Cena</th>
		<th>Rok produkcji</th>
	</tr>
	@foreach($import as $record)
	<tr>
		{{-- <td></td> --}}
		<td kod_produktu>{{ $record->kod_produktu }}</td>
		<td>{{ $record->ilosc }}</td>
		<td>{{ $record->cena }}</td>
		<td>{{ $record->rok_produkcji }}</td>
	</tr>
	@endforeach

</table>
	</div>

</body>
</html>