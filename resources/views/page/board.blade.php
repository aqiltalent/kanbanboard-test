@extends('layouts.app')
@section('title', 'BOARD')
@section('content')
	<div>
		<div class="kanban__main__header">
			<div class="kanban__main__header__title">KANBAN BOARD</div>
		</div>
		<kanban-board columns_data="{{ json_encode($columns) }}"></kanban-board>
	</div>
@endsection