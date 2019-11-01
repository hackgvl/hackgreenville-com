<div class="row">
    <div class="@if(isset($carousel)) col-md-6 @else col-md-12 @endif">
        <div class="form-group">
            <label for="name">Name</label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name', 'aria-describedby' => 'helpName']) !!}
            <small id="helpName" class="form-text text-muted">Name of carousel</small>
        </div>
    </div>
    @if(isset($carousel))
        <div class="col-md-6">
            <div class="form-group">
                <label for="slug">Slug</label>
                {!! Form::text('slug', null, ['class' => 'form-control', 'id' => 'slug', 'aria-describedby' => 'helpSlug', 'readonly' => true, 'disabled' => true]) !!}
                <small id="helpSlug" class="form-text text-muted">Slug for carousel</small>
            </div>
        </div>
    @endif
</div>




