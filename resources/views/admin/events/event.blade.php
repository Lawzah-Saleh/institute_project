@extends('admin.layouts.app')

@section('title', 'تعديل السعر')

@section('content')
<div class="page-wrapper">
<div class="content container-fluid">

<div class="page-header">
<div class="row align-items-center">
<div class="col">
<h3 class="page-title">Events</h3>
<ul class="breadcrumb">
<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
<li class="breadcrumb-item active">Events</li>
</ul>
</div>
</div>
</div>
<div class="page-header">
<div class="row align-items-center">
<div class="col"></div>
<div class="col-auto text-end float-end ms-auto">
<a href="add-events.html" class="btn btn-primary"><i class="fas fa-plus"></i></a>
</div>
</div>
</div>

<div class="row">
<div class="col-lg-12 col-md-12">
<div class="card">
<div class="card-body">
<div id="calendar"></div>
</div>
</div>
</div>
</div>

<div class="modal fade none-border" id="my_event">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title">Add Event</h4>
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body"></div>
<div class="modal-footer justify-content-center">
<button type="button" class="btn btn-success save-event submit-btn">Create event</button>
<button type="button" class="btn btn-danger delete-event submit-btn" data-dismiss="modal">Delete</button>
</div>
</div>
</div>
</div>

</div>

<footer>
<p>Copyright © 2022 Dreamguys.</p>
</footer>

</div>

</div>

@endsection
