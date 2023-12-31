<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Columns 2 Layout
 * @package    theme_tema_pruebas_lucia
 * @copyright  2015 onwards LMSACE Dev Team (http://www.lmsace.com)
 * @author    LMSACE Dev Team
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');

// Add-a-block in editing mode.
if (isset($PAGE->theme->addblockposition) &&
        $PAGE->user_is_editing() &&
        $PAGE->user_can_edit_blocks() &&
        $PAGE->pagelayout !== 'mycourses'
) {
    $url = new moodle_url($PAGE->url, ['bui_addblock' => '', 'sesskey' => sesskey()]);

    $block = new block_contents;
    $block->content = $OUTPUT->render_from_template('core/add_block_button',
        [
            'link' => $url->out(false),
            'escapedlink' => "?{$url->get_query_string(false)}",
            'pageType' => $PAGE->pagetype,
            'pageLayout' => $PAGE->pagelayout,
        ]
    );

    $PAGE->blocks->add_fake_block($block, BLOCK_POS_RIGHT);
}
$extraclasses = [];

$themestyleheader = theme_tema_pruebas_lucia_get_setting('themestyleheader');
$extraclasses[] = ($themestyleheader) ? 'theme-based-header' : 'moodle-based-header';

$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();

$secondarynavigation = false;
if ($PAGE->has_secondary_navigation()) {
    $moremenu = new \core\navigation\output\more_menu($PAGE->secondarynav, 'nav-tabs');
    $secondarynavigation = $moremenu->export_for_template($OUTPUT);
}

$primary = new core\navigation\output\primary($PAGE);
$renderer = $PAGE->get_renderer('core');
$primarymenu = $primary->export_for_template($renderer);

$buildregionmainsettings = !$PAGE->include_region_main_settings_in_header_actions()  && !$PAGE->has_secondary_navigation();
// If the settings menu will be included in the header then don't add it here.
$regionmainsettingsmenu = $buildregionmainsettings ? $OUTPUT->region_main_settings_menu() : false;

$custommenu = $OUTPUT->custom_menu();

if ($custommenu == "") {
    $navbarclass = "navbar-toggler d-lg-none nocontent-navbar";
} else {
    $navbarclass = "navbar-toggler d-lg-none";
}
// Header Content.
$logourl = get_logo_url();
$phoneno = theme_tema_pruebas_lucia_get_setting('phoneno');
$emailid = theme_tema_pruebas_lucia_get_setting('emailid');
$scallus = get_string('callus', 'theme_tema_pruebas_lucia');
$semail = get_string('email', 'theme_tema_pruebas_lucia');

// Footer Content.
$logourl = get_logo_url();
$footlogo = theme_tema_pruebas_lucia_get_setting('footlogo');
$footlogo = (!$footlogo) ? 0 : 1;
$footnote = theme_tema_pruebas_lucia_get_setting('footnote', 'format_html');
$fburl = theme_tema_pruebas_lucia_get_setting('fburl');
$pinurl = theme_tema_pruebas_lucia_get_setting('pinurl');
$twurl = theme_tema_pruebas_lucia_get_setting('twurl');
$gpurl = theme_tema_pruebas_lucia_get_setting('gpurl');
$address = theme_tema_pruebas_lucia_get_setting('address');
$emailid = theme_tema_pruebas_lucia_get_setting('emailid');
$phoneno = theme_tema_pruebas_lucia_get_setting('phoneno');
$copyrightfooter = theme_tema_pruebas_lucia_get_setting('copyright_footer', 'format_html');
$infolink = theme_tema_pruebas_lucia_get_setting('infolink');
$infolink = theme_tema_pruebas_lucia_infolink();

$sinfo = get_string('info', 'theme_tema_pruebas_lucia');
$scontactus = get_string('contact_us', 'theme_tema_pruebas_lucia');
$phone = get_string('phone', 'theme_tema_pruebas_lucia');
$email = get_string('email', 'theme_tema_pruebas_lucia');
$sfollowus = get_string('followus', 'theme_tema_pruebas_lucia');

$url = ($fburl != '' || $pinurl != '' || $twurl != '' || $gpurl != '') ? 1 : 0;
$block3 = ($address != '' || $phoneno != '' || $emailid != '' || $url != 0) ? 1 : 0;
$footerblock1 = ($footlogo != 0 || $footnote != '' || $infolink != '' || $url != 0 || $block3 != 0) ? 1 : 0;

$footerblock = ($footlogo != 0 || $footnote != '' || $infolink != ''
    || $url != 0 || $block3 != 0 || $copyrightfooter != '') ? 1 : 0;

$block1 = ($footlogo || $footnote) ? 1 : 0;
$infoslink = ($infolink != '') ? 1 : 0;
$blockarrange = $block1 + $infoslink + $block3;

switch ($blockarrange) {
    case 3:
        $colclass = 'col-md-4';
        break;
    case 2:
        $colclass = 'col-md-6';
        break;
    case 1:
        $colclass = 'col-md-12';
        break;
    case 0:
        $colclass = '';
        break;
    default:
        $colclass = 'col-md-4';
        break;
}

$templatecontext = [
    'sitename'       => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output'         => $OUTPUT,
    'sidepreblocks'  => $blockshtml,
    'hasblocks'      => $hasblocks,
    'bodyattributes' => $bodyattributes,

    'primarymoremenu'           => $primarymenu['moremenu'],
    'secondarymoremenu'         => $secondarynavigation ?: false,
    'mobileprimarynav'          => $primarymenu['mobileprimarynav'],
    'usermenu'                  => $primarymenu['user'],
    'langmenu'                  => $primarymenu['lang'],
    'regionmainsettingsmenu'    => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),

    "logourl"          => $logourl,
    "phoneno"          => $phoneno,
    "emailid"          => $emailid,
    "footlogo"         => $footlogo,
    "footnote"         => $footnote,
    "fburl"            => $fburl,
    "pinurl"           => $pinurl,
    "twurl"            => $twurl,
    "gpurl"            => $gpurl,
    "address"          => $address,
    "copyright_footer" => $copyrightfooter,
    "infolink"         => $infolink,
    "s_contact_us"     => $scontactus,
    "phone"            => $phone,
    "email"            => $email,
    "s_followus"       => $sfollowus,
    "socialurl"        => $url,
    "infolink"         => $infolink,
    "navbarclass"      => $navbarclass,
    "block3"           => $block3,
    "footerblock"      => $footerblock,
    "footerblock1"     => $footerblock1,
    "colclass"         => $colclass,
    "block1"           => $block1,

    'themestyleheader' => $themestyleheader
];


echo $OUTPUT->render_from_template('theme_tema_pruebas_lucia/columns2', $templatecontext);
