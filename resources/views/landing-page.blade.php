<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <div>
        <form action="wp/store" method="POST">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">SSH Source Hosting</th>
                        <th scope="col">SSH Target Hosting</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <label for="">Source Host</label>
                            <input type="text" name="source_host" id="">
                        </td>
                        <td>
                            <label for="">Target Host</label>
                            <input type="text" name="target_host" id="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Source Username</label>
                            <input type="text" name="source_username" id="">
                        </td>
                        <td>
                            <label for="">Target Username</label>
                            <input type="text" name="target_username" id="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Source Password</label>
                            <input type="text" name="source_password" id="">
                        </td>
                        <td>
                            <label for="">Target Password</label>
                            <input type="text" name="target_password" id="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Source path</label>
                            <input type="text" name="source_path" id="">
                        </td>
                        <td>
                            <label for="">Target path</label>
                            <input type="text" name="target_path" id="">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="">Source Port</label>
                            <input type="number" name="source_port" id="">
                        </td>
                        <td>
                            <label for="">Target Port</label>
                            <input type="number" name="target_port" id="">
                        </td>
                    </tr>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>