@extends('layouts.admin')

@section('title', __('messages.add_user'))

@section('content')
<div class="card theme-card">
    <div class="card-header">
        <h3 class="card-title theme-text">{{ __('messages.add_new_user') }}</h3>
    </div>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control theme-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                        @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.username') }} <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control theme-input @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
                        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control theme-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control theme-input @error('password') is-invalid @enderror" required>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.confirm_password') }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control theme-input" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="theme-text">{{ __('messages.role') }} <span class="text-danger">*</span></label>
                        <select name="role" class="form-control select2 theme-input @error('role') is-invalid @enderror" required>
                            <option value="">{{ __('messages.select_role') }}</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right bg-transparent">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            <button type="submit" class="btn btn-primary" style="box-shadow: 0 0 10px rgba(0,123,255,0.3);"><i class="fas fa-save"></i> {{ __('messages.save') }}</button>
        </div>
    </form>
</div>
@endsection
