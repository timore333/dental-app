@props(['label' => null,  'icon'=>null,  'options' => []])
<li>
    <button type="button"
            class="flex items-center w-full p-2 text-base text-gray-900 transition
            duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
            aria-controls="dropdown-{{$label}}" data-collapse-toggle="dropdown-{{$label}}">

        {{$icon}}

        <span class="flex-1 ml-3 text-left whitespace-nowrap" sidebar-toggle-item>{{ __($label) }}</span>

        <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
             xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd">

            </path>
        </svg>

    </button>

    <ul id="dropdown-{{$label}}" class="hidden py-2 space-y-2">
        @foreach($options as $option)
            <li>
                <a href="{{ route($option['route']) }}"
                   class="flex items-center p-2 text-base text-gray-900 transition duration-75
                    rounded-lg pl-11 group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">
                     {{ __($option['label']) }}
                </a>
            </li>
        @endforeach

    </ul>

</li>
