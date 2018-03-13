<<<<<<< HEAD
# ABQ Public Schools - Special Needs and ESY Transportation Planning

The `/public/` directory was renamed from `/wwwroot/` before the initial Git commit of the project.

The aspect of the application that is being refactored is <http://dev.aps.local/transportation/>

The default index and other functional areas of the app that exist in the legacy app are deprecated and should be
removed.

## Deployment

For initial deployment, after the project is cloned, and the vhost is configured for `public/`, you must copy
`public/FX/server_data.php.dist` as `public/FX/server_data.php` and ensuer that the settings are correct for your
environment. If you are installing via Vagrant, the provisioner automatically deploys a `public/FX/server_data.php`
that is configured to connect to Dionysus.

Poor man's Dump:
`echo '<pre>';var_dump($searchData);die;`

### FileMaker file name references

Note: The legacy system included the file extension on all of the $myDB settings. This breaks when we switch to the
new file format, and the file extension is optional anyway. `'Transportation_Special_Needs.fp7'` has already been
updated, but be aware this was an issue in case anything was missed.
  
#### Example

Replace this:
```
$myDB = 'Transportation_Special_Needs.fp7';
```

... with this:
```
$myDB = 'Transportation_Special_Needs';
```



### Vagrant issue with CURL

Note: If you're having issues with FX requests silently failing, it may be because of a CURL issue on the starter_box.

The workaround is to set your FX instances with `$instance->FMUseCURL(false);`

This has already been done in `public/transportation`, but if you need to create new instances, or if there are cases
that matter outside of this directory, you may need to set them so they use the setting from `server_data` include.

#### Example

Replace this:
```
$search = new FX($serverIP,$webCompanionPort);
```

... with this:
```
$search = new FX($serverIP,$webCompanionPort); $search->FMUseCURL($fxUseCurl);
```
=======
# Dev_iepweb2
The repository for iepweb02.unl.edu as a development environment in Lincoln
>>>>>>> c99fc680e31a64f32840bbe7662049cec9cdb9f4
