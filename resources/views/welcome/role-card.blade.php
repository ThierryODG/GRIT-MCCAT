<div class="bg-white rounded-2xl p-8 transition-all duration-300 hover:scale-105 hover:shadow-2xl">
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-{{ $color }}-100 mb-4">
            <i class="fas fa-{{ $icon }} text-2xl text-{{ $color }}-600"></i>
        </div>
        <h3 class="text-2xl font-bold text-gray-800">{{ $title }}</h3>
    </div>
    <ul class="text-gray-600 space-y-3">
        @foreach($features as $feature)
        <li class="flex items-start">
            <i class="fas fa-check text-{{ $color }}-500 mt-1 mr-3 flex-shrink-0"></i>
            <span>{{ $feature }}</span>
        </li>
        @endforeach
    </ul>
</div>
