<footer class="border-t border-[#e0e0e0] bg-[#f5f5f7] py-6 px-4 sm:px-8 mt-auto">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between text-xs text-[#7a7a7a] gap-2">
        <p>© {{ date('Y') }} {{ config('nominatif.organization.name') }}. Hak Cipta Dilindungi Undang-Undang.</p>
        <div class="flex items-center gap-4 text-[11px]">
            <span>{{ config('nominatif.organization.unit') }}</span>
            <span>•</span>
            <span>Versi {{ config('nominatif.version', '1.0.0') }}</span>
        </div>
    </div>
</footer>
