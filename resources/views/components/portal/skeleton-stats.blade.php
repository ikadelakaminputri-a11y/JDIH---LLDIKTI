<div class="flex justify-center gap-10 py-1">
    @foreach (range(1, 4) as $i)
        <div class="text-center space-y-1.5">
            <x-portal.skeleton-bar class="h-7 w-16 mx-auto" />
            <x-portal.skeleton-bar class="h-3 w-24 mx-auto" />
        </div>
        @if ($i < 4)
            <div class="w-px bg-gray-100"></div>
        @endif
    @endforeach
</div>
