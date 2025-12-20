<aside
    id="sidebar"
    class="flex fixed top-0 ltr:left-0 rtl:right-0 z-20 flex-col flex-shrink-0 pt-16 w-64 h-full  lg:flex transition-all transform ltr:-translate-x-full rtl:translate-x-full duration-300"


    aria-label="Sidebar"
>

    <div class="flex relative flex-col flex-1 pt-0 min-h-0 bg-gray-50 dark:bg-gray-800">
        <div class="flex overflow-y-auto flex-col flex-1 pt-8 pb-4">
            <div class="flex-1 px-3 bg-gray-50" id="sidebar-items">

                <ul class="pb-2 pt-1">

                    <li>
                        <form action="#" method="GET" class="lg:hidden">
                            <label for="mobile-search" class="sr-only">Search</label>
                            <div class="relative">
                                <div
                                    class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
                                >
                                    <svg
                                        class="w-5 h-5 text-gray-500"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="email"
                                    id="mobile-search"
                                    class="bg-gray-50 border border-gray-300 text-dark-500 text-sm font-light rounded-lg focus:ring-2 focus:ring-fuchsia-50 focus:border-fuchsia-300 block w-full pl-10 p-2.5 mb-2"
                                    placeholder="Search"
                                />
                            </div>
                        </form>
                    </li>


                    {{------------------------------------------------------------------------------------------------------}}
                    @php
                        $navigationConfig = config('navigation');
                       $userRole = strtolower(auth()->user()?->role?->name) ?? 'admin';

                      $menuItems = collect($navigationConfig)->get($userRole, []);
                    @endphp



                    @foreach($menuItems as $item)
                        @if($item['sub'])

                            <li>
                                <button
                                    type="button"
                                    class="w-full flex items-center py-2.5 px-4 text-base font-normal text-dark-500 rounded-lg hover:bg-gray-200 group transition-all duration-200"
                                    sidebar-toggle-collapse
                                    aria-controls="dropdown-{{$item['label']}}"
                                    data-collapse-toggle="dropdown-{{$item['label']}}"
                                >
                                    <div
                                        class="bg-white shadow-lg shadow-gray-300  bg-fuchsia-500 !text-white text-dark-700 w-8 h-8 p-2.5 mr-1 rounded-lg text-center grid place-items-center"

                                    >
                                        <svg
                                            class="text-dark"
                                            width="12px"
                                            height="12px"
                                            viewBox="0 0 42 44"
                                            version="1.1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                        >
                                            <title>basket</title>
                                            <g
                                                stroke="none"
                                                stroke-width="1"
                                                fill="none"
                                                fill-rule="evenodd"
                                            >
                                                <g
                                                    transform="translate(-1869.000000, -741.000000)"
                                                    fill="currentColor"
                                                    fill-rule="nonzero"
                                                >
                                                    <g transform="translate(1716.000000, 291.000000)">
                                                        <g
                                                            id="basket"
                                                            transform="translate(153.000000, 450.000000)"
                                                        >
                                                            <path
                                                                class="color-background"
                                                                d="M34.080375,13.125 L27.3748125,1.9490625 C27.1377583,1.53795093 26.6972449,1.28682264 26.222716,1.29218729 C25.748187,1.29772591 25.3135593,1.55890827 25.0860125,1.97535742 C24.8584658,2.39180657 24.8734447,2.89865282 25.1251875,3.3009375 L31.019625,13.125 L10.980375,13.125 L16.8748125,3.3009375 C17.1265553,2.89865282 17.1415342,2.39180657 16.9139875,1.97535742 C16.6864407,1.55890827 16.251813,1.29772591 15.777284,1.29218729 C15.3027551,1.28682264 14.8622417,1.53795093 14.6251875,1.9490625 L7.919625,13.125 L0,13.125 L0,18.375 L42,18.375 L42,13.125 L34.080375,13.125 Z"
                                                                opacity="0.595377604"
                                                            ></path>
                                                            <path
                                                                class="color-background"
                                                                d="M3.9375,21 L3.9375,38.0625 C3.9375,40.9619949 6.28800506,43.3125 9.1875,43.3125 L32.8125,43.3125 C35.7119949,43.3125 38.0625,40.9619949 38.0625,38.0625 L38.0625,21 L3.9375,21 Z M14.4375,36.75 L11.8125,36.75 L11.8125,26.25 L14.4375,26.25 L14.4375,36.75 Z M22.3125,36.75 L19.6875,36.75 L19.6875,26.25 L22.3125,26.25 L22.3125,36.75 Z M30.1875,36.75 L27.5625,36.75 L27.5625,26.25 L30.1875,26.25 L30.1875,36.75 Z"
                                                            ></path>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>

                                    <span

                                        class="{{app()->isLocale('en') ? 'ml-3':'mr-4'}} text-dark-500 text-sm font-light"
                                        sidebar-toggle-item
                                    >{{__($item['label'])}}</span
                                    >
                                    <svg
                                        sidebar-toggle-item
                                        class="w-4 h-4 ml-auto text-gray-700"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </button>
                                <ul
                                    id="dropdown-{{$item['label']}}"
                                    sidebar-toggle-list
                                    class="pb-2 pt-1 hidden"
                                >
                                    @foreach($item['sub-items'] as $subItem)
                                        <li>
                                            <a
                                                href="{{route($subItem['route'])}}"
                                                class="text-sm text-dark-500 font-light rounded-lg flex items-center p-2 group hover:bg-gray-200 transition duration-75 pl-11"
                                            >{{__($subItem['label'])}}</a
                                            >
                                        </li>
                                    @endforeach

                                </ul>
                            </li>

                        @else
                            <li>

                                <a
                                    href="{{route($item['route'])}}"
                                    class="flex items-center py-2.5 px-4 text-base font-normal text-dark-500 rounded-lg hover:bg-gray-200 group transition-all duration-200"
                                    sidebar-toggle-collapse
                                >
                                    <div
                                        class="bg-white shadow-lg shadow-gray-300 text-dark-700 w-8 h-8 p-2.5 mr-1 rounded-lg text-center grid place-items-center">
                                        @if (str_contains($item['icon'], 'heroicon'))
                                            <x-dynamic-component :component="$item['icon']"/>
                                        @else
                                            {{ $item['icon'] }}
                                        @endif


                                    </div>
                                    <span
                                        class="{{app()->isLocale('en') ? 'ml-3':'mr-4'}} text-dark-500 text-sm font-light"
                                        sidebar-toggle-item
                                    >{{__($item['label'])}}</span
                                    >
                                </a>
                            </li>
                        @endif

                    @endforeach

                    {{-------------------------------------------------------------------------------------------------------------}}

                </ul>
                <hr
                    class="border-0 h-px bg-gradient-to-r from-gray-100 via-gray-300 to-gray-100"
                />

            </div>
        </div>
        <div
            class="hidden relative bottom-0 left-0 justify-center p-4 space-x-4 w-full lg:flex bg-gray-100"
            sidebar-bottom-menu
        >
            <a
                href="#"
                class="inline-flex justify-center p-2 text-gray-500 rounded cursor-pointer hover:text-dark-500 hover:bg-gray-200"
            >
                <svg
                    class="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"
                    ></path>
                </svg>
            </a>
            <a
                href="https://demos.creative-tim.com/soft-ui-flowbite-pro/users/settings/"
                data-tooltip-target="tooltip-settings"
                class="inline-flex justify-center p-2 text-gray-500 rounded cursor-pointer hover:text-dark-500 hover:bg-gray-200"
            >
                <svg
                    class="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fill-rule="evenodd"
                        d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                        clip-rule="evenodd"
                    ></path>
                </svg>
            </a>
            <div
                id="tooltip-settings"
                role="tooltip"
                class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg opacity-0 transition-opacity duration-300 tooltip shadow-lg-sm"
            >
                Settings page
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button
                type="button"
                data-dropdown-toggle="language-dropdown"
                class="inline-flex justify-center p-2 text-gray-500 rounded cursor-pointer hover:text-dark-500 hover:bg-gray-200"
            >
                <svg
                    class="h-5 w-5 rounded-full mt-0.5"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 3900 3900"
                >
                    <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                    <path
                        d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0"
                        stroke="#fff"
                        stroke-width="300"
                    />
                    <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                    <g fill="#fff">
                        <g id="d">
                            <g id="c">
                                <g id="e">
                                    <g id="b">
                                        <path
                                            id="a"
                                            d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"
                                        />
                                        <use xlink:href="#a" y="420"/>
                                        <use xlink:href="#a" y="840"/>
                                        <use xlink:href="#a" y="1260"/>
                                    </g>
                                    <use xlink:href="#a" y="1680"/>
                                </g>
                                <use xlink:href="#b" x="247" y="210"/>
                            </g>
                            <use xlink:href="#c" x="494"/>
                        </g>
                        <use xlink:href="#d" x="988"/>
                        <use xlink:href="#c" x="1976"/>
                        <use xlink:href="#e" x="2470"/>
                    </g>
                </svg>
            </button>

            <div
                class="hidden z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow-lg"
                id="language-dropdown"
            >
                <ul class="py-1" role="none">
                    <li>
                        <a
                            href="{{route('set-locale', 'en')}}"
                            class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200"
                            role="menuitem"
                        >
                            <div class="inline-flex items-center">
                                <svg
                                    class="h-3.5 w-3.5 rounded-full mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    id="flag-icon-css-us"
                                    viewBox="0 0 512 512"
                                >
                                    <g fill-rule="evenodd">
                                        <g stroke-width="1pt">
                                            <path
                                                fill="#bd3d44"
                                                d="M0 0h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0z"
                                                transform="scale(3.9385)"
                                            />
                                            <path
                                                fill="#fff"
                                                d="M0 10h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0zm0 20h247v10H0z"
                                                transform="scale(3.9385)"
                                            />
                                        </g>
                                        <path
                                            fill="#192f5d"
                                            d="M0 0h98.8v70H0z"
                                            transform="scale(3.9385)"
                                        />
                                        <path
                                            fill="#fff"
                                            d="M8.2 3l1 2.8H12L9.7 7.5l.9 2.7-2.4-1.7L6 10.2l.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7L74 8.5l-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 7.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 24.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 21.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 38.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 35.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 52.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 49.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm-74.1 7l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7H65zm16.4 0l1 2.8H86l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm-74 7l.8 2.8h3l-2.4 1.7.9 2.7-2.4-1.7L6 66.2l.9-2.7-2.4-1.7h3zm16.4 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8H45l-2.4 1.7 1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9zm16.4 0l1 2.8h2.8l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h3zm16.5 0l.9 2.8h2.9l-2.3 1.7.9 2.7-2.4-1.7-2.3 1.7.9-2.7-2.4-1.7h2.9zm16.5 0l.9 2.8h2.9L92 63.5l1 2.7-2.4-1.7-2.4 1.7 1-2.7-2.4-1.7h2.9z"
                                            transform="scale(3.9385)"
                                        />
                                    </g>
                                </svg>
                                English (US)
                            </div>
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{route('set-locale', 'ar')}}"
                            class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200"
                            role="menuitem"
                        >
                            <div class="inline-flex items-center">
                                <svg
                                    class="h-3.5 w-3.5 rounded-full mr-2"
                                    xmlns="http://www.w3.org/2000/svg"
                                    id="flag-icon-css-de"
                                    viewBox="0 0 512 512"
                                >
                                    <path fill="#ffce00" d="M0 341.3h512V512H0z"/>
                                    <path d="M0 0h512v170.7H0z"/>
                                    <path fill="#d00" d="M0 170.7h512v170.6H0z"/>
                                </svg>
                                عربي
                            </div>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</aside>
