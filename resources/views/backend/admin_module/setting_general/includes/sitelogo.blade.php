{{-- Site Logo & Branding Section --}}
<div class="p-6 border-t border-slate-200" id="kt_portlet_site">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-image text-primary-500"></i>
                Logo & Branding
            </h3>
            <p class="text-sm text-slate-500 mt-1">Upload your clinic logo and favicon</p>
        </div>
    </div>

    @if($current_section == 'update_logo')
        @include('backend.layouts.includes.notification_alerts')
    @endif

    {!! Form::open(['route'=>('setting_logo_update'), 'id' => 'update_form_site', 'class' => '', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

    <input type="hidden" name="id" value="{{$item_site['id']}}">

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Desktop Logo --}}
        <div class="space-y-3">
            <h4 class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Desktop Logo</h4>
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <p class="text-xs text-slate-500 mb-3">
                        Recommended: <span class="text-amber-600 font-medium">{{ $logo_desktop_width }}px × {{ $logo_desktop_height }}px</span>
                        · Format: JPG, PNG, GIF
                    </p>
                    <label for="logo_desktop" class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-lg"></i>
                        <span class="text-sm text-slate-600">Choose file or drag & drop</span>
                        <input type="file" class="hidden" id="logo_desktop" name="logo_desktop" accept="image/png,image/jpeg,image/gif">
                    </label>
                </div>
                @if(isset($item_site['logo_desktop']) && !empty($item_site['logo_desktop']))
                    <div class="shrink-0 w-20 h-20 rounded-xl border border-slate-200 overflow-hidden bg-white flex items-center justify-center p-1">
                        <img src="{{ URL::asset('uploads/'. $directory_logos. '/'.$item_site['logo_desktop']) }}" 
                             alt="Desktop Logo" class="max-w-full max-h-full object-contain"/>
                    </div>
                @endif
            </div>
        </div>

        {{-- Favicon --}}
        <div class="space-y-3">
            <h4 class="text-sm font-semibold text-slate-600 uppercase tracking-wider">Favicon</h4>
            <div class="flex items-start gap-4">
                <div class="flex-1">
                    <p class="text-xs text-slate-500 mb-3">
                        Small icon shown in browser tabs · Square format recommended
                    </p>
                    <label for="favicon" class="flex items-center gap-3 px-4 py-3 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer hover:border-primary-400 hover:bg-primary-50/50 transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-slate-400 text-lg"></i>
                        <span class="text-sm text-slate-600">Choose file or drag & drop</span>
                        <input type="file" class="hidden" id="favicon" name="favicon" accept="image/png,image/jpeg,image/gif">
                    </label>
                </div>
                @if(isset($item_site['favicon']) && !empty($item_site['favicon']))
                    <div class="shrink-0 w-20 h-20 rounded-xl border border-slate-200 overflow-hidden bg-white flex items-center justify-center p-2">
                        <img src="{{ URL::asset('uploads/'. $directory_logos. '/'.$item_site['favicon']) }}" 
                             alt="Favicon" class="max-w-full max-h-full object-contain"/>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Additional Settings --}}
    <div class="mt-8 space-y-5">
        <h4 class="text-sm font-semibold text-slate-600 uppercase tracking-wider border-b border-slate-200 pb-2">
            Additional Settings
        </h4>

        <div>
            <label for="google_analytics" class="block text-sm font-medium text-slate-700 mb-1">
                <i class="fa-brands fa-google mr-1"></i> Google Analytics Code
            </label>
            <textarea class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm font-mono text-xs" 
                      name="google_analytics" id="google_analytics" rows="4" spellcheck="false" 
                      placeholder="Paste your Google Analytics tracking code here">{{ $item_site['google_analytics'] }}</textarea>
        </div>

        <div>
            <label for="footer_copy_right" class="block text-sm font-medium text-slate-700 mb-1">
                Footer Copyright <span class="text-red-500">*</span>
            </label>
            <input type="text" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm" 
                   name="footer_copy_right" id="footer_copy_right" placeholder="Enter footer copyright text"
                   value="{{ $item_site['footer_copy_right'] }}"/>
        </div>
    </div>

    <div class="flex justify-end mt-6 pt-4 border-t border-slate-200">
        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors font-medium shadow-sm" id="update_button_site">
            <i class="fa-solid fa-check"></i>
            Update Logo & Branding
        </button>
    </div>

    {!! Form::close() !!}
</div>
