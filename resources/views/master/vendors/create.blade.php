@extends('layouts.admin')

@section('title', __('messages.add') . ' ' . __('messages.vendor'))

@section('content')
<div class="card theme-card">
    <form action="{{ route('vendors.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.name') }}</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.contact_person') }}</label>
                <input type="text" name="contact_person" class="form-control @error('contact_person') is-invalid @enderror" value="{{ old('contact_person') }}" required>
                @error('contact_person') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.email') }}</label>
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.phone') }}</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
                @error('phone') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label  class="theme-text">{{ __('messages.address') }}</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer bg-transparent">
            <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
            <a href="{{ route('vendors.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection