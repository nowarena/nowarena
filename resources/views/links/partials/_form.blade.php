<div class="form-group">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name') !!}
</div>
<div class="form-group">
    {!! Form::label('link', 'Link:') !!}
    {!! Form::text('link') !!}
</div>

<div class="form-group">
    {!! Form::submit('Submit Link') !!}
</div>

{!! Form::hidden('link_id', $id) !!}

<br>
{!! link_to_route('links.index', '&laquo;Back to Links') !!}
 {{--&#183; &nbsp; {!! link_to_route('admin', 'Admin') !!} &nbsp; &#183; --}}
{!! link_to_route('links.create', 'Create a Link&raquo;') !!}

<script src='/js/admin.js'></script>