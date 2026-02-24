@extends('backend.layouts.modern')

@section('title', 'Edit Pharmacy Generic')

@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-800">Edit Generic</h2>
                <a href="{{ route('pharmacy_generic.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </a>
            </div>

            <form action="{{ route("pharmacy_generic.update", $generic->id) }}" method="POST" id="add_form" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method("PUT")
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Info -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Generic Information</h3>
                </div>

                <!-- Generic Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Generic Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ $generic->generic }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="Enter generic name" required>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ route('pharmacy_generic.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Cancel
                </a>
                <button type="submit" id="add_button" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    Update Generic
                </button>
            </div>

            </form>
        </div>
    </div>
@endsection



