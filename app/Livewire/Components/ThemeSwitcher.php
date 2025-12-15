<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ThemeSwitcher extends Component
{
    public string $theme = 'light';
    public string $language = 'en';
    public array $languages = [
        'en' => '🇬🇧 English',
        'ar' => '🇪🇬 العربية',
    ];

    public function mount(): void
    {
        $user = Auth::user();
        if ($user) {
            $this->theme = $user->theme ?? 'light';
            $this->language = $user->language ?? 'en';
        }
    }

    public function toggleTheme(): void
    {
        $this->theme = $this->theme === 'light' ? 'dark' : 'light';

        if ($user = Auth::user()) {
            $user->update(['theme' => $this->theme]);
        }

        $this->dispatch('themeChanged', theme: $this->theme);
    }

    public function changeLanguage(string $lang): void
    {
        if (! in_array($lang, array_keys($this->languages))) {
            return;
        }

        $this->language = $lang;

        if ($user = Auth::user()) {
            $user->update(['language' => $lang]);
        }

        session(['locale' => $lang]);
        $this->dispatch('languageChanged', language: $lang);
    }

    public function render()
    {
        return view('livewire.components.theme-switcher');
    }
}
