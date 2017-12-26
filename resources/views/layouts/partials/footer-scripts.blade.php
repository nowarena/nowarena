<!-- Bootstrap core JavaScript
================================================= -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!--script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script-->
<script src="//cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

<script>
alert('hi');
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