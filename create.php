<?
session_start(); 
require_once "classes/func.php";
$func = new GlobalFunction($_GET['id']);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.autocomplete.js"></script>
  <script type="text/javascript" src="js/function.js"></script>
  <script type="text/javascript" src="js/save_data.js"></script>
  <title>Складання розкладу</title>
 </head>
 <body>
    <div class = "wrapper">
        <? if($_GET['sem']=='fill') { ?> <h1>Осінній семестр</h1>
            <? $gr = $func->createGroups(0); $subj = $func->createSubject(0);}
        elseif($_GET['sem']=='spring'){ ?> <h1>Весняний семестр</h1>
            <?  $gr = $func->createGroups(1); $subj = $func->createSubject(1);}
            $table_width=0;
            foreach($gr as $kurs)
                foreach ($kurs as $grupa) $table_width++;
            $table_width = $table_width * 225;
            $audience = $func->getAudience();
            $arr_audience = $func->getFullAudiences();
            $arr_data_timetable = $func->getTimetable($_GET["id"],$_GET["sem"]);
        ?>
        <script>var year=<?=json_encode($_GET['id'])?>; var sem=<?=json_encode($_GET['sem'])?>;</script>
        <div id = "button_save" onclick="save_timetable()">Зберегти</div>
        <div id = "buttons_table">
            <div id = "button_numerator" onclick="hoverNumerator(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати чисельник</div>
            <div id= "button_denumerator" onclick="hoverDenumerator(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати знаменник</div>
            <div id= "button_course1" onclick="hoverCourse(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати 1 курс</div>
            <div id= "button_course2" onclick="hoverCourse(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати 2 курс</div>
            <div id= "button_course3" onclick="hoverCourse(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати 3 курс</div>
            <div id= "button_course4" onclick="hoverCourse(this)" style="background-color: #CB5151" onmouseover="if(this.style.backgroundColor=='rgb(203, 81, 81)') this.style.backgroundColor='#ee5700'; else this.style.backgroundColor='#229435' " onmouseout="if(this.style.backgroundColor=='rgb(238, 87, 0)') this.style.backgroundColor='#CB5151'; else this.style.backgroundColor='green'">Сховати 4 курс</div>
        </div>
            <h2 class="type_table">Чисельник</h2>
            <div id="header" style="width: <?=$table_width+65;?>px;"></div>
            <table class="rozklad" id="numerator" style="width: <?=$table_width+65;?>px;"> <thead><tr><th></th><th class="number"></th>
            <?
            foreach($gr as $kurs)
                foreach ($kurs as $grupa){ ?>
                        <th id="id_group:<?=$grupa['id']?>" class="course:<?=$grupa['course']?>" colspan="2"><?=$grupa['title']?></th>
                <? } ?></tr>
                </thead>
                <script>
                    var data = <?=json_encode($gr)?>;
                    var predmets = <?=json_encode($subj)?>;
                    var auditor = <?=json_encode($audience)?>.split(' ');
                    var arr_auditor = <?=json_encode($arr_audience)?>;
                    var group; var para; var prev_subject_id=-1; var prev_arr_index=''; var prev_pract=false;
                    var input_sel; var prev_input_sel; var input_value;
                </script>
                <? $row=0;
                   $tmp_arr=array();
                   $count_reset = false;
                for($i=0; $i<5; $i++){ ?>
                <tr class="row:<?=$row?>" id="tr_begin">
                    <?if($i==0) { ?> <td rowspan="12">Пн.</td> <? } ?>
                    <?if($i==1) { ?> <td rowspan="12">Вт.</td> <? } ?>
                    <?if($i==2) { ?> <td rowspan="12">Ср.</td> <? } ?>
                    <?if($i==3) { ?> <td rowspan="12">Чт.</td> <? } ?>
                    <?if($i==4) { ?> <td rowspan="12">Пт.</td> <? } ?>

                    <td rowspan="3" class="number">1-2</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                    <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                        <select class="subjects" id="para:1-2" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                            <option value="-1">Виберіть предмет</option>
                            <?
                            foreach($arr_data_timetable as $dat)
                                if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==0)) {
                                    for($z=0; $z<count($subj); $z++) {
                                        if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                    }
                                    $tmp_arr[]=$dat; ?>
                                <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                <? } ?>
                        </select>
                    </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                    <?$row++; $tmp_arr=array();?>

                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">3-4</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:3-4" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==0)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                    <?$row++;?>


                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">5-6</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:5-6" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==0)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++;?>

                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">7-8</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:7-8" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==0)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==0)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++;?>
                <? } ?>
            </table>


        <h2 class="type_table">Знаменник</h2>
        <table class="rozklad"  id="denumerator" style="width: <?=$table_width+65;?>px;"> <tr><th></th><th class="number"></th>
                <?
                foreach($gr as $kurs)
                    foreach ($kurs as $grupa){ ?>

                        <th id="id_group:<?=$grupa['id']?>" class="course:<?=$grupa['course']?>" colspan="2"><?=$grupa['title']?></th>
                    <? } ?></tr>
            <? $row=0;
            for($i=0; $i<5; $i++){ ?>
                <tr class="row:<?=$row?>" id="tr_begin">
                    <?if($i==0) { ?> <td rowspan="12">Пн.</td> <? } ?>
                    <?if($i==1) { ?> <td rowspan="12">Вт.</td> <? } ?>
                    <?if($i==2) { ?> <td rowspan="12">Ср.</td> <? } ?>
                    <?if($i==3) { ?> <td rowspan="12">Чт.</td> <? } ?>
                    <?if($i==4) { ?> <td rowspan="12">Пт.</td> <? } ?>

                    <td rowspan="3" class="number">1-2</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:1-2" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==1)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="1-2")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++; $tmp_arr=array();?>

                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">3-4</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:3-4" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==1)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="3-4")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++;?>


                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">5-6</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:5-6" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==1)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="5-6")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++;?>

                <tr class="row:<?=$row?>">
                    <td rowspan="3" class="number">7-8</td>
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td class="id_group:<?=$grupa['id']?>" id="border_lft_rht" colspan="2">
                            <select class="subjects" id="para:7-8" onmouseover="getSubjects(this)" onclick="clickSubject(this)">
                                <option value="-1">Виберіть предмет</option>
                                <?
                                foreach($arr_data_timetable as $dat)
                                    if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==1)) {
                                        for($z=0; $z<count($subj); $z++) {
                                            if(($dat["group_id"]==$subj[$z]["group_id"])&&($dat["subject_id"]==$subj[$z]["id"])&&($dat["teacher1_id"]==$subj[$z]["teacher_id"])) { if($dat["teacher2_name"]!='') $subj[$z]["count_dual"] = $subj[$z]["count_dual"]-0.5; else $subj[$z]["count_first"] = $subj[$z]["count_first"]-0.5; $count_reset = true; break; }
                                        }
                                        $tmp_arr[]=$dat; ?>
                                        <option value="<?=$dat['subject_id']?>" selected><?=$dat['subject']?></option>
                                    <? } ?>
                            </select>
                        </td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher1_name"]!='')) {echo $dat['teacher1_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience1_number"]!='')) {$audit=$dat['audience1_number']; break;}?>
                        <td id="bd_right"><input class="audience:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <tr class="row:<?=$row?>">
                    <? foreach($gr as $kurs) foreach ($kurs as $grupa){ ?>
                        <td id="bd_left" class="teacher_pract:<?=$grupa['id']?>"><?foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["teacher2_name"]!='')) {echo $dat['teacher2_name']; break;}?></td>
                        <?$audit = ''; foreach($tmp_arr as $dat) if(($dat["group_id"]==$grupa['id'])&&($dat["para"]=="7-8")&&($dat["day"]==($i+1))&&($dat["type"]==1)&&($dat["audience2_number"]!='')) {$audit=$dat['audience2_number']; break;}?>
                        <td id="bd_right"><input class="audience_pract:<?=$grupa['id']?>" id="audiens" type="text" onkeyup="input_sel=this" value="<?=$audit?>" <?if($audit=='') echo 'readonly';?>/></td>
                    <? } ?>
                </tr>
                <?$row++;?>
            <? } ?>
        </table>
    </div>

    <?if($count_reset){?><script>predmets = <?=json_encode($subj)?>;</script><?}?>
 <script>
     paintCells();
     input_sel=document.getElementsByClassName("row:0")[1].getElementsByTagName("input")[0];
     $("input").autocomplete({
         serviceUrl: auditor, // Страница для обработки запросов автозаполнения
         minChars: 1, // Минимальная длина запроса для срабатывания автозаполнения
         delimiter: /(,|;)\s*/, // Разделитель для нескольких запросов, символ или регулярное выражение
         maxHeight: 30, // Максимальная высота списка подсказок, в пикселях
         width: 50, // Ширина списка
         zIndex: 9999, // z-index списка
         deferRequestBy: 0, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
         onSelect: function(data, value){ }, // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
         lookup: auditor // Список вариантов для локального автозаполнения
     });

     jQuery(document).ready(function($){
         var $table = $('#numerator'),
             $header = $('#header'),
             $thead = $('thead');
         $header.hide();
         $thead.find('th').each(function(){
             var $newdiv = $('<div />', {
                 style: 'width:'+ $(this).width()+'px'

             });
             $newdiv.text($(this).text());
             $header.append($newdiv);
         });

         if($(this).scrollTop()>183) $header.show();

         var $viewport = $(window);
         $viewport.scroll(function(){
             if($(this).scrollTop()>183) $header.show();
             else $header.hide();
             $header.css({
                 left: -$(this).scrollLeft()
             });
         });

     });

 </script>
</body>
</html>