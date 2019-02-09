<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
        <title>Plonk</title>
        <style media="screen">

            form {
                margin-bottom: 0;
            }

            .row {
                margin-bottom: 1em;
            }

            .table > tbody > tr > td {
                 vertical-align: middle;
            }
        </style>
    </head>
    <main>
        <nav class="navbar navbar-light bg-light mb-4">
            <span class="navbar-brand">Plonk</span>
        </nav>
        
        @include('laravel-building::partial.message')
        
        <div class="container">
            @yield('content')
        </div>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

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
</html>
