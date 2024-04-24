@extends('users.base')
 
@section('content')
<div class="jumbotron">
    <div class="container">
        <p>{{ $message }}</p>
 
        @if ($redirect)
        <script type="application/javascript">
            setTimeout(
                function() {
                    location.href = '{{ $redirect }}';
                },
                10000
            );
        </script>
        <p class="like-h">������� <a href="{{ $redirect }}">��� ������</a>, ���� ��� ������� �� ������������ �������������� ��������.</p>
        @endif
    </div>
</div>
@stop