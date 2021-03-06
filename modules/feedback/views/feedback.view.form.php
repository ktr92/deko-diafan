<?php
/**
 * Шаблон формы добавления сообщения в обратной связи
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
	$path = __FILE__;
	while(! file_exists($path.'/includes/404.php'))
	{
		$parent = dirname($path);
		if($parent == $path) exit;
		$path = $parent;
	}
	include $path.'/includes/404.php';
}

if(! empty($result["text"]))
{
	echo $result["text"];
	return;
}

echo '
<div class="feedback_form">
<form method="POST" enctype="multipart/form-data" action="" class="ajax">
<input placeholder="'.$row["name"].'" type="hidden" name="module" value="feedback">
<input placeholder="'.$row["name"].'" type="hidden" name="action" value="add">
<input placeholder="'.$row["name"].'" type="hidden" name="form_tag" value="'.$result["form_tag"].'">
<input placeholder="'.$row["name"].'" type="hidden" name="site_id" value="'.$result["site_id"].'">
<input placeholder="'.$row["name"].'" type="hidden" name="tmpcode" value="'.md5(mt_rand(0, 9999)).'">';


//заголовок блока
if (! empty($result["name"]))
{
	echo '<div class="pageform__title">'.$result["name"].'</div>
	<div class="pageform__text">Оставьте заявку, мы Вам перезвоним</div>';
}
echo '<div class="pageform__form">';
						
						

$required = false;
echo '<div class="infofields">';
if(! empty($result["rows"]))
{
	foreach ($result["rows"] as $row) //вывод полей из конструктора форм
	{
		if($row["required"])
		{
			$required = true;
		}
		echo '<div class="feedback_form_param'.$row["id"].'">';

		switch ($row["type"])
		{
			case 'title':
				echo '<div class="infoform">'.$row["name"].':</div>';
				break;

			case 'text':
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input placeholder="'.$row["name"].'" type="text" name="p'.$row["id"].'" value="">'; echo '</div>';
				break;

			case "email":
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input placeholder="'.$row["name"].'" type="email" name="p'.$row["id"].'" value="">'; echo '</div>';
				break;

			case "phone":
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input placeholder="'.$row["name"].'" type="tel" name="p'.$row["id"].'" value="">'; echo '</div>';
				break;

			case 'textarea':
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<textarea name="p'.$row["id"].'" cols="66" rows="10"></textarea>'; echo '</div>';
				break;

			case 'date':
			case 'datetime':
				$timecalendar  = true;
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
					<input placeholder="'.$row["name"].'" type="text" name="p'.$row["id"].'" value="" class="timecalendar" showTime="'
					.($row["type"] == 'datetime'? 'true' : 'false').'">'; echo '</div>';
				break;

			case 'numtext':
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input placeholder="'.$row["name"].'" type="number" name="p'.$row["id"].'" value="">'; echo '</div>';
				break;

			case 'checkbox':
				echo '<input placeholder="'.$row["name"].'" name="p'.$row["id"].'" id="feedback_p'.$row["id"].'" value="1" type="checkbox" ><label for="feedback_p'.$row["id"].'">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').'</label>'; echo '</div>';
				break;

			case 'radio':
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>';
				foreach ($row["select_array"] as $select)
				{
					echo '<input placeholder="'.$row["name"].'" name="p'.$row["id"].'" type="radio" value="'.$select["id"].'" id="feedback_form_p'.$row["id"].'_'.$select["id"].'"> <label for="feedback_form_p'.$row["id"].'_'.$select["id"].'">'.$select["name"].'</label>';
				}
				 echo '</div>';
				break;

			case 'select':
				echo '<div class="pageform__input"><div class="custom-select"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<select name="p'.$row["id"].'" class="inpselect">
					<option value="">'.$row["name"].'</option>';
				foreach ($row["select_array"] as $select)
				{
					echo '<option value="'.$select["id"].'">'.$select["name"].'</option>';
				}
				echo '</select>';
				echo '</div>'; echo '</div>';
				break;

			case 'multiple':
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>';
				foreach ($row["select_array"] as $select)
				{
					echo '<input placeholder="'.$row["name"].'" name="p'.$row["id"].'[]" id="feedback_p'.$select["id"].'[]" value="'.$select["id"].'" type="checkbox"><label for="feedback_p'.$select["id"].'[]">'.$select["name"].'</label><br>';
				}echo '</div>';
				break;

			case "attachments":
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>';
				echo '<div class="inpattachment"><input placeholder="'.$row["name"].'" type="file" name="attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				echo '<div class="inpattachment" style="display:none"><input placeholder="'.$row["name"].'" type="file" name="hide_attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				if ($row["attachment_extensions"])
				{
					echo '<div class="attachment_extensions">('.$this->diafan->_('Доступные типы файлов').': '.$row["attachment_extensions"].')</div>';
				}echo '</div>';
				break;

			case "images":
				echo '<div class="pageform__input"><div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div><div class="images"></div>';
				echo '<input placeholder="'.$row["name"].'" type="file" name="images'.$row["id"].'" param_id="'.$row["id"].'" class="inpimages">';echo '</div>';
				break;
		}

		if($row["text"])
		{
			echo '<div class="feedback_form_param_text">'.$row["text"].'</div>';
		}

		echo '</div>';

		if($row["type"] != 'title')
		{
			echo '<div class="errors error_p'.$row["id"].'"'.($result["error_p".$row["id"]] ? '>'.$result["error_p".$row["id"]] : ' style="display:none">').'</div>';
		}
	}
}
echo '</div>';
//Защитный код
echo $result["captcha"];

//Кнопка Отправить
echo '<div class="pageform__input pageform__input_submit">
<input placeholder="'.$row["name"].'" type="submit" value="'.$this->diafan->_('Записаться', false).'" class="btn btn_red">
</div>';

echo '<div class="pageform__info">'.$this->diafan->_('Отправляя форму, я даю согласие на <a href="%s">обработку персональных данных</a>.', true, BASE_PATH_HREF.'privacy'.ROUTE_END).'</div>';

if($required)
{
	echo '<div class="required_field"><span style="color:red;">*</span> — '.$this->diafan->_('Поля, обязательные для заполнения').'</div>';
}

echo '</form>';
echo '</div>';
echo '<div class="errors error"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>
';
echo '</div>';