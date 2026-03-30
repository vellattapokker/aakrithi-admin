@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
<div class="page-header">
    <h1 class="page-title">General Settings</h1>
    <p style="color: var(--color-text-light); font-size: 0.875rem;">Configure your store's general information.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <div class="grid-2">
        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Contact Information</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label class="form-label">Support Email</label>
                    <input type="text" name="settings[support_email]" class="form-control" value="{{ $settings['support_email'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="settings[contact_phone]" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label">Store Address</label>
                    <textarea name="settings[store_address]" class="form-control" rows="3">{{ $settings['store_address'] ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 1.125rem; margin-bottom: 1.5rem;">Store Details</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label class="form-label">Store Name</label>
                    <input type="text" name="settings[store_name]" class="form-control" value="{{ $settings['store_name'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label">Currency Symbol</label>
                    <input type="text" name="settings[currency_symbol]" class="form-control" value="{{ $settings['currency_symbol'] ?? '₹' }}">
                </div>
                 <div>
                    <label class="form-label">Social Media Links (JSON)</label>
                    <textarea name="settings[social_links]" class="form-control" rows="3">{{ $settings['social_links'] ?? '' }}</textarea>
                    <small style="color: var(--color-text-light); font-size: 0.75rem;">e.g., {"instagram": "...", "facebook": "..."}</small>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
        <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem;">Save Changes</button>
    </div>
</form>
@endsection
