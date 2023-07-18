<div>
    @foreach ($activities as $activity)
        <p class="text-xxs text-opacity-50">{{$activity->date}} {{$activity->time}}</p>
        <p class="font-bold flex"><span>{{$activity->title}}</span> <span class="ml-2 p-1 text-black {{ ($activity->status == 'Level IV (Awas)' ? 'bg-red-700' : ($activity->status == 'Level III (Siaga)' ? 'bg-yellow-600' : ($activity->status == 'Level II (Waspada)' ? 'bg-yellow-300' : ($activity->status == 'Level I (Normal)' ? 'bg-green-500' : ('bg-grey'))))) }} text-xxs my-auto">{{$activity->status}}</span></p>
        <p class="text-xxs">{{$activity->article}}</p>
        <hr class="my-2 border-gray-300">
    @endforeach

</div>