@extends('layouts.admin')

@section('title', __('messages.edit') . ' ' . __('messages.employee'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('employees.update', $employee) }}" method="POST">
        @csrf @method('PUT')
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.employee_id') }}</label>
                    <input type="text" name="employee_id" class="form-control @error('employee_id') is-invalid @enderror" value="{{ old('employee_id', $employee->employee_id) }}" required >
                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.full_name') }}</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $employee->name) }}" required >
                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.email') }}</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $employee->email) }}" >
                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.phone') }}</label>
                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $employee->phone) }}" >
                    @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.department') }}</label>
                    <select name="department_id" class="form-control @error('department_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">Select {{ __('messages.department') }}</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" style="color: #000;" {{ old('department_id', $employee->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.position') }}</label>
                    <select name="position_id" class="form-control @error('position_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">Select {{ __('messages.position') }}</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}" style="color: #000;" {{ old('position_id', $employee->position_id) == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                        @endforeach
                    </select>
                    @error('position_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.supervisor') ?? 'Supervisor' }}</label>
                    <select name="supervisor_id" class="form-control select2 @error('supervisor_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">Select {{ __('messages.supervisor') ?? 'Supervisor' }}</option>
                        @foreach($supervisors as $sup)
                            <option value="{{ $sup->id }}" style="color: #000;" {{ old('supervisor_id', $employee->supervisor_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supervisor_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-6 form-group">
                    <label  class="theme-text">{{ __('messages.location') }}</label>
                    <select name="location_id" class="form-control select2 @error('location_id') is-invalid @enderror" >
                        <option value="" style="color: #000;">Select {{ __('messages.location') }}</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" style="color: #000;" {{ old('location_id', $employee->location_id) == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                    @error('location_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.telephone_extension') ?? 'Telephone Extension' }}</label>
                    <input type="text" name="extension" class="form-control @error('extension') is-invalid @enderror" value="{{ old('extension', $employee->extension) }}" placeholder="e.g. 102">
                    @error('extension') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.anydesk_id') ?? 'AnyDesk ID' }}</label>
                    <input type="text" name="anydesk_id" class="form-control @error('anydesk_id') is-invalid @enderror" value="{{ old('anydesk_id', $employee->anydesk_id) }}" placeholder="e.g. 123456789">
                    @error('anydesk_id') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-4 form-group">
                    <label  class="theme-text">{{ __('messages.anydesk_password') ?? 'AnyDesk Password' }}</label>
                    <input type="text" name="anydesk_password" class="form-control @error('anydesk_password') is-invalid @enderror" value="{{ old('anydesk_password', $employee->anydesk_password) }}" placeholder="AnyDesk Pwd">
                    @error('anydesk_password') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label  class="theme-text">Status</label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror" required >
                        <option value="Active" style="color: #000;" {{ old('status', $employee->status) == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" style="color: #000;" {{ old('status', $employee->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <button type="submit" class="btn btn-primary" ><i class="fas fa-save"></i> {{ __('messages.update') }}</button>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary ml-2" >{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection
