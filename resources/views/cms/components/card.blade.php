<div class="card">
    <div class="card-body pt-4 pb-3 px-4">
        <div class="flex space-x-3 rtl:space-x-reverse">
            <div class="flex-none">
                <div
                    class="h-12 w-12 rounded-full flex flex-col items-center justify-center text-2xl bg-[#E5F9FF] dark:bg-slate-900	 text-info-500">
                    <iconify-icon icon="{{ $icon }}"></iconify-icon>
                </div>
            </div>
            <div class="flex-1">
                <div class="text-slate-600 dark:text-slate-300 text-sm mb-1 font-medium">
                    {{ $info }}
                </div>
                <div class="text-slate-900 dark:text-white text-lg font-medium">
                    {{ $total }}
                </div>
            </div>
        </div>
    </div>
</div>
