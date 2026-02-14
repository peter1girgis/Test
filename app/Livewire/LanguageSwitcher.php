<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    public $locale;

    public function mount()
    {
        $this->locale = App::getLocale();
    }

    public function switchLanguage()
    {
        $newLocale = $this->locale === 'ar' ? 'en' : 'ar';
        Session::put('locale', $newLocale);
        $this->locale = $newLocale;

        // عمل refresh للصفحة عشان كل الترجمة تتحدث
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
