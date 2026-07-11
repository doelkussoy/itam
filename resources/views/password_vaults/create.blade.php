@extends('layouts.admin')

@section('title', __('messages.add_vault') ?? 'Add Vault Password')

@section('content')
<div class="card theme-card" style="max-width: 600px; margin: 0 auto;">
    <form action="{{ route('password_vaults.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label class="theme-text">{{ __('messages.device_name') }} *</label>
                <input type="text" name="device_name" class="form-control theme-input @error('device_name') is-invalid @enderror" value="{{ old('device_name') }}" placeholder="e.g., CCTV NVR Lab, Switch core CBA, Budi AnyDesk" required>
                @error('device_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.username') }} *</label>
                <input type="text" name="username" class="form-control theme-input @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="e.g., admin, root, budi@cba.co.id" required>
                @error('username') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.password') }} *</label>
                <div class="input-group">
                    <input type="password" name="encrypted_password" id="encrypted_password" class="form-control theme-input @error('encrypted_password') is-invalid @enderror" placeholder="Enter password" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-info" id="toggle-pwd-visibility"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                @error('encrypted_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.category') }} *</label>
                <select name="category" class="form-control theme-input @error('category') is-invalid @enderror" required>
                    <option value="" style="color: #000;">{{ __('messages.select_category') ?? 'Select Category' }}</option>
                    <option value="AnyDesk" style="color: #000;">AnyDesk</option>
                    <option value="Windows" style="color: #000;">Windows</option>
                    <option value="Email" style="color: #000;">Email</option>
                    <option value="CCTV" style="color: #000;">CCTV</option>
                    <option value="Switch" style="color: #000;">Switch</option>
                    <option value="Printer" style="color: #000;">Printer</option>
                    <option value="SAP" style="color: #000;">SAP</option>
                    <option value="Other" style="color: #000;">Other</option>
                </select>
                @error('category') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="theme-text">{{ __('messages.notes') }}</label>
                <textarea name="notes" class="form-control theme-input @error('notes') is-invalid @enderror" rows="3" placeholder="e.g. extension info, backup codes, access restrictions">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="card-footer" style="background-color: transparent; border-top: 1px solid var(--tech-border);">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{ __('messages.save') }}</button>
            <a href="{{ route('password_vaults.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times mr-1"></i> {{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#toggle-pwd-visibility').click(function() {
        var input = $('#encrypted_password');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
@endpush
