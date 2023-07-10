<?php 


global $library;

$fileLoaderObj = new fileLoader("crud");
$fileLoaderObj->addFile("application/crud/controller/crud.service.class.inc");
 $fileLoaderObj->addFile("application/crud/model/crud.class.inc");
$library->addLibrary($fileLoaderObj);


 
if($library->addLibrary($fileLoaderObj) !== true){
    console(LOG_LEVEL_ERROR,"Unable to create library Service");
    return false;
}

$library->loadLibrary("crud");
$library->loadLibrary("dbqryconstructor");
$library->loadLibrary("dbvalidator");

 
$crud = new CrudController();
echo $crud->router();

?>