@extends('backend.layouts.modern')

@section('title', 'Profile & Security')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-800">My Profile</h1>
        <p class="text-slate-500 mt-1">Manage your account details and security settings</p>
    </div>

    {{-- Flash messages --}}
    @if(session('message'))
    <div class="rounded-lg p-4 text-sm font-medium flex items-center gap-3" style="background: #dcfce7; color: #166534;">
        <i class="fas fa-check-circle text-lg"></i>
        {{ session('message') }}
    </div>
    @endif
    @if(session('error_message'))
    <div class="rounded-lg p-4 text-sm font-medium flex items-center gap-3" style="background: #fee2e2; color: #991b1b;">
        <i class="fas fa-exclamation-circle text-lg"></i>
        {{ session('error_message') }}
    </div>
    @endif
    @if($errors->any())
    <div class="rounded-lg p-4 text-sm font-medium" style="background: #fee2e2; color: #991b1b;">
        <div class="flex items-center gap-3 mb-2">
            <i class="fas fa-exclamation-triangle text-lg"></i>
            <span>Please fix the following errors:</span>
        </div>
        <ul class="list-disc pl-8 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Profile Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center" style="background: #dbeafe; color: #2563eb;">
                    <i class="fas fa-user text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Profile Information</h2>
                    <p class="text-xs text-slate-500">Update your name and email address</p>
                </div>
            </div>

            <form action="{{ url($url_prefix . '/profile/update') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="phone" value="{{ $item['phone'] }}">

                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ $item['name'] }}" placeholder="Enter full name"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ $item['email'] }}" placeholder="Enter email"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
                    <input type="text" value="{{ $item['phone'] }}" disabled
                        class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-500 cursor-not-allowed">
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm transition-all" style="background: #2563eb;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Change Password Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center" style="background: #fef3c7; color: #d97706;">
                    <i class="fas fa-lock text-lg"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Change Password</h2>
                    <p class="text-xs text-slate-500">Ensure your account uses a strong password</p>
                </div>
            </div>

            <form action="{{ url($url_prefix . '/password/update') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <div>
                    <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1.5">Current Password <span class="text-red-500">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="current_password" id="current_password" placeholder="Enter current password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-10 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-slate-700 mb-1.5">New Password <span class="text-red-500">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="new_password" id="new_password" placeholder="Enter new password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-10 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    <p class="mt-1.5 text-xs text-slate-400">Minimum 8 characters with uppercase, lowercase, and number</p>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="confirm_password" id="confirm_password" placeholder="Confirm new password"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 pr-10 text-sm text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm transition-all" style="background: #d97706;" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                        <i class="fas fa-key"></i> Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
