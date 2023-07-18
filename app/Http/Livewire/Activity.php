<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VolcanoActivity;

use Illuminate\Support\Facades\Http;

class Activity extends Component
{

    public function render()
    {
        $data = Http::withHeaders(['User-Agent' => $_SERVER['HTTP_USER_AGENT']])->post('https://tangkalbencana.id/api/getVolcanoActivity', [
            'username' => 'admin',
            'apikey' => '69aa399d-3ff1-4724-861a-b5133aeded6b',
        ])->getBody();
        $data = collect(json_decode($data));

        return view('livewire.activity', [
            'activities' => $data->sortByDesc('date'),
        ]);
    }
}
