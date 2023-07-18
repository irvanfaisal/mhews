<div>
    @foreach ($eruptions as $eruption)
    <div class="flex flex-col">
        <div>
            <p class="text-xxs text-opacity-50">{{$eruption->date}} - {{$eruption->time}}</p>
            <p class="font-bold">{{$eruption->title}}</p>
        @if($eruption->img)
            <img class="pr-2 my-auto max-h-24" src="https://tangkalbencana.id/storage/{{$eruption->img }}">
        @endif
            <p class="text-xxs">{{$eruption->article}}</p>
        </div>
    </div>

    <hr class="my-2 border-gray-300">
    @endforeach
 
</div>