<?php
/*
 Finds.org.uk Artefacts and Coins Shortcode Form
 Version 1.0
 Author Mary Chester-Kadwell
 Author URI https://github.com/mchesterkadwell
 */

/*  Copyright 2017  Mary Chester-Kadwell  (email : mchester-kadwell@britishmuseum.org)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License version 3 as published by
 the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

$br = "<br />";
$p_open = "<p>";
$p_close = "<p>";

$shortcode_form = "<form id='fouaac_shortcode' name='fouaac_shortcode' method='POST' action=''>";
$shortcode_form.= $p_open;
// id (from url)
$shortcode_form.= "<label for='id'>" . 'URL of the finds.org.uk artefact you want to insert' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<input type='text' id='id' name='id' size='50' />";
$shortcode_form.= $br;
// caption-option
$shortcode_form.= "<label for='caption-option'>" . 'Automatic caption or no caption?' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<select id='caption-option' name='caption-option'>";
$shortcode_form.= "<option value='auto' selected='selected'>" . 'auto' . "</option>";
$shortcode_form.= "<option value='none'>" . 'none' . "</option>";
$shortcode_form.= "</select>";
$shortcode_form.= $br;
// caption-text
$shortcode_form.= "<label for='caption-text'>" . 'Optional caption text' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<input type='text' id='caption-text' name='caption-text' size='40'/>";
$shortcode_form.= $br;
// figure-size
$shortcode_form.= "<label for='figure-size'>" . 'Figure size (medium is the default)' . "</label>";
$shortcode_form.= $br;
$shortcode_form.= "<select id='figure-size' name='figure-size'>";
$shortcode_form.= "<option value='small'>" . 'small' . "</option>";
$shortcode_form.= "<option value='medium' selected='selected'>" . 'medium' . "</option>";
$shortcode_form.= "<option value='large'>" . 'large' . "</option>";
$shortcode_form.= "</select>";
$shortcode_form.= $br;
$shortcode_form.= $br;
$shortcode_form.= "<input type='button' id='fouaac_shortcode_submit' value='"."Insert Shortcode"."' />";
$shortcode_form.= $p_close;
$shortcode_form.= "</form>";
echo $shortcode_form;
?>

