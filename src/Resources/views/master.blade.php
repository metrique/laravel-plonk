<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <style media="screen">
        code {
            word-wrap: break-word;
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="plonk">
        <main>
            <div class="container">
                <div class="row">
                    <div class="page-header">
                        <h1>Plonk <small>Image management</small></h1>
                    </div>
                </div>
            </div>
            <div>
                @yield('body')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        /**
         * Required for destroy/delete functionality.
         * Attaches an event listener and triggers a warning before deleting content.
         *
         * Usage: Add data-role="destroy" and optionally data-route="http://new-route"
         *
         * data-role: Specifies that the element is to be used for destroying data.
         * data-route: Updates the form action attribute
         */

        var destroy = document.querySelectorAll('[data-role="destroy"]');

        for(var i = 0; i < destroy.length; i++) {

            destroy[i].addEventListener('click', function(event) {

                if(!window.confirm('Are you sure you want to delete this?')) {
                    return event.preventDefault();
                }

                // Search for hidden '_method' input.
                var method = document.querySelector('input[name="_method"]');

                if(method == null) {
                    // Create and add '_method' input to form.
                    method = document.createElement(
                        'input'
                    ).setAttribute(
                        'type', 'hidden'
                    ).setAttribute(
                        'name', '_method'
                    ).setAttribute(
                        'value', 'DELETE'
                    );

                    event.target.form.appendChild(method);
                } else {
                    // Update '_method' input to be 'DELETE'.
                    method.setAttribute('value', 'DELETE');
                }

                // Update form action if data-route is given.
                var route = event.target.getAttribute('data-route');

                if(route !== null) {
                    event.target.form.setAttribute('action', route);
                }

                return true;
            });
        }
    </script>
</body>
</html>
