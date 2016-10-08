<?php
class coreparsers {
 var $_package='Core parsers for all site HTML generation';
 var $_version=0.1;

 /*
   Function MakeParse

   Штатный парсер
   Параметры работы :
   $HtmIst - URL файла с HTML и кодами
   $DataToReplace - Массив данных для подставновки
    состоит из пары Код -> Значение
   Возвращает HTML с подставленными значениями
 */
 function MakeParse($HtmIst, $DataToReplace) {
  $SearchCodes = array();
  $ResultCodes = array();

  $fgt = 'inc/'. $HtmIst;
  $handle = fopen($fgt, "rb");
  $SourseHTM = fread($handle, filesize($fgt));
  fclose($handle);

  $mr = preg_match_all('!@(.*?)@!si', $SourseHTM, $scodeshtm);
  foreach ($scodeshtm[0] as $key => $value) {
   $SearchCodes[] = $value;
   if (isset($DataToReplace[$value])) {
    $ResultCodes[] = $DataToReplace[$value];
   }
   else {
    $ResultCodes[] = "";
   }
  }
  return str_replace($SearchCodes, $ResultCodes, $SourseHTM)."\n";
 }
}
?>
