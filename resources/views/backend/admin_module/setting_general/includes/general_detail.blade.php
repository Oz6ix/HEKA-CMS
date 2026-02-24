{{-- General Information Section --}}
<div class="p-6" id="kt_portlet_homepage_element">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-hospital text-primary-500"></i>
                Hospital General Information
            </h3>
            <p class="text-sm text-slate-500 mt-1">Update your clinic or hospital details</p>
        </div>
    </div>

    @if($current_section == 'update_general_info')
        @include('backend.layouts.includes.notification_alerts')
    @endif

    {!! Form::open(['route'=>('setting_general_info_update'), 'id' => 'update_form_homepage_element', 'class' => '', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

    <input type="hidden" name="id" value="{{$item_general['id']}}">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Left Column: Hospital Info --}}
        <div class="space-y-5">
            <h4 class="text-sm font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200 pb-2">
                Hospital Details
            </h4>

            <div>
                <label for="hospital_name" class="block text-sm font-medium text-slate-700 mb-1">
                    Hospital Name <span class="text-red-500">*</span>
                </label>
                <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                       name="hospital_name" id="hospital_name" placeholder="Enter hospital name" required
                       value="{{ $item_general['hospital_name'] }}"/>
            </div>

            <div>
                <label for="hospital_code" class="block text-sm font-medium text-slate-700 mb-1">
                    Hospital Code <span class="text-red-500">*</span>
                </label>
                <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                       name="hospital_code" id="hospital_code" placeholder="Enter hospital code" maxlength="4" minlength="4"
                       value="{{ $item_general['hospital_code'] }}"/>
                <p class="text-xs text-slate-400 mt-1">Exactly 4 characters</p>
            </div>

            <div>
                <label for="hospital_address" class="block text-sm font-medium text-slate-700 mb-1">
                    Address <span class="text-red-500">*</span>
                </label>
                <textarea class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                          name="hospital_address" id="hospital_address" rows="3" spellcheck="false" 
                          placeholder="Enter address">{{ $item_general['hospital_address'] }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-slate-700 mb-1">
                        Phone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                           name="contact_phone" id="contact_phone" placeholder="Enter phone number"
                           value="{{ $item_general['contact_phone'] }}"/>
                </div>
                <div>
                    <label for="alternative_phone" class="block text-sm font-medium text-slate-700 mb-1">
                        Alternative Phone
                    </label>
                    <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                           name="alternative_phone" id="alternative_phone" placeholder="Enter alternative phone"
                           value="{{ $item_general['alternative_phone'] }}"/>
                </div>
            </div>

            <div>
                <label for="contact_email" class="block text-sm font-medium text-slate-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                       name="contact_email" id="contact_email" placeholder="Enter email address"
                       value="{{ $item_general['contact_email'] }}"/>
            </div>
        </div>

        {{-- Right Column: Social Links --}}
        <div class="space-y-5">
            <h4 class="text-sm font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200 pb-2">
                Social Media Links
            </h4>

            @foreach([
                ['facebook_url', 'Facebook', 'fa-brands fa-facebook', 'Enter facebook url'],
                ['twitter_url', 'Twitter / X', 'fa-brands fa-x-twitter', 'Enter twitter url'],
                ['youtube_url', 'YouTube', 'fa-brands fa-youtube', 'Enter youtube url'],
                ['linkedin_url', 'LinkedIn', 'fa-brands fa-linkedin', 'Enter linkedin url'],
                ['instagram_url', 'Instagram', 'fa-brands fa-instagram', 'Enter instagram url'],
            ] as [$field, $label, $icon, $placeholder])
                <div>
                    <label for="{{ $field }}" class="block text-sm font-medium text-slate-700 mb-1">
                        <i class="{{ $icon }} mr-1"></i> {{ $label }}
                    </label>
                    <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                           name="{{ $field }}" id="{{ $field }}" placeholder="{{ $placeholder }}"
                           value="{{ $item_general[$field] }}"/>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end mt-6 pt-4 border-t border-slate-200">
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium shadow-sm" id="update_button_homepage_element">
            <i class="fa-solid fa-check"></i>
            Update Information
        </button>
    </div>

    {!! Form::close() !!}
</div>
