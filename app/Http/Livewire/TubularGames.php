<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;

class TubularGames extends Component
{
    public $tubularGames = [];

    public function loadTubularGames()
    {
        $this->tubularGames = \Cache::remember('tubular',60, static function () {
            $before = Carbon::now()->subMonths(2)->timestamp;
            $after = Carbon::now()->addMonths(2)->timestamp;
            return \Http::withHeaders(config('services.igdb'))
                ->withOptions(['body' => "fields name,cover.url,first_release_date, popularity,platforms.abbreviation,rating;
            where platforms = (48,49,130,6) & (first_release_date >= {$before} & first_release_date < {$after});
            sort popularity desc;
            limit 10;"])
                ->get('https://api-v3.igdb.com/games')
                ->json();
        });
    }

    public function render()
    {
        return view('livewire.tubular-games');
    }
}