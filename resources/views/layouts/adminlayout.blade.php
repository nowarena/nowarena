<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.partials.head')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @if (App::environment('local'))
        @include('layouts.partials.adminheader-scripts-dev')
    @else
        @include('layouts.partials.adminheader-scripts')
    @endif
</head>
<body>
@include('partials.nav')
@yield('content')
{{-- js scripts included at top so that jquery is always available --}}
{{--@include('layouts.partials.footer-scripts')--}}

<script>

    $(document).ready(function() {
        //
        // UPDATE NAV LINKS ACTIVE STATUS
        //
        function getPathFromUrl(url) {
            return url.split(/[?#]/)[0];
        }

        function getUrlParts() {
            var url_full = location.href;
            // remove # and ? querystring stuff
            url_full = getPathFromUrl(url_full);
            console.log(url_full);
            var url_parts = url_full.split('/');
            console.log(url_parts);
            return url_parts;
        }

        url_parts = getUrlParts();

        if (url_parts.length == 6) {
            // eg /tasks/6/edit
            url = '/' + url_parts[3] + '/' + url_parts[4] + '/' + url_parts[5];
        } else if (url_parts.length == 5) {
            // eg /tasks/create
            url = '/' + url_parts[3] + '/' + url_parts[4];
        } else if (url_parts.length == 4) {
            // eg. /tasks
            url = '/' + url_parts[3];
        }
        console.log(url);
        $('.nav-pills a[href="' + url + '"]').parents('li').addClass('active');
        //
        // END UPDATE NAV LINKS ACTIVE STATUS
        //

    });

</script>

</body>
</html>
