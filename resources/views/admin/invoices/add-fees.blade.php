@extends('admin.layouts.app')

@section('title', 'تعديل السعر')

@section('content')

<div class="page-wrapper">
<div class="content container-fluid">

<div class="page-header">
<div class="row align-items-center">
<div class="col">
<h3 class="page-title">Add Fees</h3>
<ul class="breadcrumb">
<li class="breadcrumb-item"><a href="fees.html">Fees</a></li>
<li class="breadcrumb-item active">Add Fees</li>
</ul>
</div>
</div>
</div>

<div class="row">
<div class="col-sm-12">
<div class="card">
<div class="card-body">
<form>
<div class="row">
<div class="col-12">
<h5 class="form-title"><span>Fees Information</span></h5>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>Fees ID</label>
<input type="text" class="form-control">
</div>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>Fees Type</label>
<select class="form-control select">
<option>Select Type</option>
<option>Class Test</option>
<option>Exam Fees</option>
<option>Hostel Fees</option>
<option>Transport Fees</option>
<option>Mess Fees</option>
</select>
</div>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>Gender</label>
<select class="form-control select">
<option>Select Class</option>
<option>LKG</option>
<option>UKG</option>
<option>1</option>
<option>2</option>
<option>3</option>
<option>4</option>
<option>5</option>
<option>6</option>
<option>7</option>
<option>8</option>
<option>9</option>
<option>10</option>
<option>11</option>
<option>12</option>
</select>
</div>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>Fees Amount</label>
<input type="text" class="form-control">
</div>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>Start Date</label>
<input type="date" class="form-control">
</div>
</div>
<div class="col-12 col-sm-6">
<div class="form-group">
<label>End Date</label>
<input type="date" class="form-control">
</div>
</div>
<div class="col-12">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</div>
</form>
</div>
</div>
</div>
</div>
</div>
</div>

</div>
@endsection
