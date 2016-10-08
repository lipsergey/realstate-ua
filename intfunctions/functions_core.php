<?php
$path_to_packages=$SPUrl.'/intfunctions'; //Путь к директории, содержащей пакеты
$instances=array(); //Массив ссылок на объекты классов
$package_members=array('class'); //Составные пакета
$packages_to_include=array(); //Подключаемые пакеты (данные добавляются по-методу
//функции  registerPackage($indefier)

function registerPackage($indefier){
 global $path_to_packages,$packages_to_include;

 if(file_exists($path_to_packages.'/'.$indefier)){
  $packages_to_include[]=$indefier;
 }else{
  return false;
 }
 return true;
}

//Функция реализующая непосредственное подключение библиотеки к программе
function includePackage($indefier){
 global $instances, $path_to_packages, $package_members;

 if(trim($indefier)!=''){
  //Подключить все компоненты пакета
  foreach($package_members as $k=>$v){
   $member=$path_to_packages.'/'.$indefier.'/'.$v.'.'.$indefier.'.php';
   if(!file_exists($member)){return false;}
   else{
    if(!include($member)) {return false;}
   }
  }

  //Добавить экзмепляр класса в коллекцию $instances[]
  if(class_exists($indefier) && !isset($instances[$indefier])){
   $instances[$indefier] = new $indefier();
  }
 }
 else {return false;}
 return true;
}

//Функция для подключения всех зарегистрированных пакетов
function loadLibs(){
 global $packages_to_include;
 foreach($packages_to_include as $k=>$v){
  if(!includePackage($v)){return false;}
 }
 return true;
}

function addNewLibs($indefier) {
 global $path_to_packages,$packages_to_include;
 if(file_exists($path_to_packages.'/'.$indefier)){
  $packages_to_include[]=$indefier;
  if(!includePackage($indefier)){return false;}
 }else{
  return false;
 }
 return true;
}

/*
 Проверка библиотеки на её статус. Типы проверки:
 1 - подключена ли
 2 - есть ли такая библиотека в комплекте сайта
 3 - 1+2 - ответы:
   1 - есть в комплекте, подключена
   2 - есть в комплекте, неподключена
   3 - нет в комплекте
*/
function CheckLibs($indefier, $type = 1) {
 global $instances, $path_to_packages, $package_members;
 if ($type == 2 || $type == 3) {
  foreach($package_members as $k=>$v){
   if(!file_exists($path_to_packages.'/'.$indefier.'/'.$v.'.'.$indefier.'.php')) {
   	if ($type == 2) {return false;}
   	else {return 3;}
   }
  }
  if ($type == 2) {return true;}
 }
 if ($type == 1 || $type == 3) {
  if(!isset($instances[$indefier])) {
   if ($type == 1) {return false;}
   else {return 2;}
  }
  if ($type == 1) {return true;}
  else {return 1;}
 }
}

?>