<div class="card">
  <div class="card-header" id="{{$talk['title']}}CardHeader">
    <h2 class="mb-0">
      <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse{{$id}}" aria-expanded="false" aria-controls="collapse{{$id}}">
        {{$talk['title']}}
        <small class="text-muted">by {{$talk['speaker']['name']}}</small>
      </button>
    </h2>
  </div>
  <div id="collapse{{$id}}" class="collapse" aria-labelledby="heading{{$id}}" data-parent="{{$parent_id}}">
    <div class="card-body">
      Event data!
    </div>
  </div>
</div>
