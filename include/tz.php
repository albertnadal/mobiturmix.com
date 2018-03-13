<?php
  /* 
    tzphp - A small php library for looking up time in remote timezones.
    Copyright (C) 2005 Donald Becker

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
  */

  $tz_version = "0.1.3";
  $tz_zone = array();
  $tz_zone['Africa/Abidjan'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Accra'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Addis_Ababa'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Algiers'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Asmara'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Bamako'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Bangui'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Banjul'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Bissau'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Blantyre'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Brazzaville'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Bujumbura'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Cairo'] = array(120, array(0,0,23,5,8,0,4,0), 0, array(0,0,0,5,3,0,5,0), 60);
  $tz_zone['Africa/Casablanca'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Ceuta'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Africa/Conakry'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Dakar'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Dar_es_Salaam'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Djibouti'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Douala'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/El_Aaiun'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Freetown'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Gaborone'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Harare'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Johannesburg'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Kampala'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Khartoum'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Kigali'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Kinshasa'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Lagos'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Libreville'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Lome'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Luanda'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Lubumbashi'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Lusaka'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Malabo'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Maputo'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Maseru'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Mbabane'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Mogadishu'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Monrovia'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Nairobi'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Ndjamena'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Niamey'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Nouakchott'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Ouagadougou'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Porto-Novo'] = array(60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Sao_Tome'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Tripoli'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Africa/Tunis'] = array(60, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Africa/Windhoek'] = array(60, array(0,0,2,1,8,0,0,0), 60, array(0,0,2,1,3,0,0,0), 0);
  $tz_zone['America/Adak'] = array(-600, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Anchorage'] = array(-540, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Anguilla'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Antigua'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Araguaina'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Buenos_Aires'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Catamarca'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Cordoba'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Jujuy'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/La_Rioja'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Mendoza'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Rio_Gallegos'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/San_Juan'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Tucuman'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Argentina/Ushuaia'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Aruba'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Asuncion'] = array(-240, array(0,0,0,2,2,0,0,0), 0, array(0,0,0,3,9,0,0,0), 60);
  $tz_zone['America/Atikokan'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Bahia'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Barbados'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Belem'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Belize'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Blanc-Sablon'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Boa_Vista'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Bogota'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Boise'] = array(-420, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Cambridge_Bay'] = array(-420, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Campo_Grande'] = array(-240, array(0,0,0,1,10,0,0,0), 60, array(0,0,0,5,1,0,0,0), 0);
  $tz_zone['America/Cancun'] = array(-360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Caracas'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Cayenne'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Cayman'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Chicago'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Chihuahua'] = array(-420, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Costa_Rica'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Cuiaba'] = array(-240, array(0,0,0,1,10,0,0,0), 60, array(0,0,0,5,1,0,0,0), 0);
  $tz_zone['America/Curacao'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Danmarkshavn'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Dawson'] = array(-480, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Dawson_Creek'] = array(-420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Denver'] = array(-420, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Detroit'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Dominica'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Edmonton'] = array(-420, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Eirunepe'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/El_Salvador'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Fortaleza'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Glace_Bay'] = array(-240, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Godthab'] = array(-180, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['America/Goose_Bay'] = array(-240, array(0,1,0,2,2,0,0,0), 60, array(0,1,0,1,10,0,0,0), 0);
  $tz_zone['America/Grand_Turk'] = array(-300, array(0,0,0,5,9,0,0,0), 0, array(0,0,0,1,3,0,0,0), 60);
  $tz_zone['America/Grenada'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Guadeloupe'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Guatemala'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Guayaquil'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Guyana'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Halifax'] = array(-240, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Havana'] = array(-300, array(0,0,0,5,9,0,0,0), 0, array(0,0,0,1,3,0,0,0), 60);
  $tz_zone['America/Hermosillo'] = array(-420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Indiana/Indianapolis'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Indiana/Knox'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Indiana/Marengo'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Indiana/Petersburg'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Indiana/Vevay'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Indiana/Vincennes'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Inuvik'] = array(-420, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Iqaluit'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Jamaica'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Juneau'] = array(-540, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Kentucky/Louisville'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Kentucky/Monticello'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/La_Paz'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Lima'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Los_Angeles'] = array(-480, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Maceio'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Managua'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Manaus'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Martinique'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Mazatlan'] = array(-420, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Menominee'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Merida'] = array(-360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Mexico_City'] = array(-360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Miquelon'] = array(-180, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Moncton'] = array(-240, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Monterrey'] = array(-360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Montevideo'] = array(-180, array(0,0,2,1,9,0,0,0), 60, array(0,0,2,2,2,0,0,0), 0);
  $tz_zone['America/Montreal'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Montserrat'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Nassau'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/New_York'] = array(-300, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Nipigon'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Nome'] = array(-540, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Noronha'] = array(-120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/North_Dakota/Center'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/North_Dakota/New_Salem'] = array(-360, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Panama'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Pangnirtung'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Paramaribo'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Phoenix'] = array(-420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Port-au-Prince'] = array(-300, array(0,0,0,5,9,0,0,0), 0, array(0,0,0,1,3,0,0,0), 60);
  $tz_zone['America/Port_of_Spain'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Porto_Velho'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Puerto_Rico'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Rainy_River'] = array(-360, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Rankin_Inlet'] = array(-360, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Recife'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Regina'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Rio_Branco'] = array(-300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Santiago'] = array(-240, array(0,0,3,2,2,0,0,0), 0, array(0,0,4,2,9,0,0,0), 60);
  $tz_zone['America/Santo_Domingo'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Sao_Paulo'] = array(-180, array(0,0,0,1,10,0,0,0), 60, array(0,0,0,5,1,0,0,0), 0);
  $tz_zone['America/Scoresbysund'] = array(-60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['America/St_Johns'] = array(-210, array(0,1,0,2,2,0,0,0), 60, array(0,1,0,1,10,0,0,0), 0);
  $tz_zone['America/St_Kitts'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/St_Lucia'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/St_Thomas'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/St_Vincent'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Swift_Current'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Tegucigalpa'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Thule'] = array(-240, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Thunder_Bay'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Tijuana'] = array(-480, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,1,3,0,0,0), 60);
  $tz_zone['America/Toronto'] = array(-300, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Tortola'] = array(-240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['America/Vancouver'] = array(-480, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Whitehorse'] = array(-480, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Winnipeg'] = array(-360, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['America/Yakutat'] = array(-540, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['America/Yellowknife'] = array(-420, array(0,0,2,1,10,0,0,0), 0, array(0,0,2,2,2,0,0,0), 60);
  $tz_zone['Antarctica/Casey'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/Davis'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/DumontDUrville'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/Mawson'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/McMurdo'] = array(720, array(0,0,2,1,9,0,0,0), 60, array(0,0,2,3,2,0,0,0), 0);
  $tz_zone['Antarctica/Palmer'] = array(-240, array(0,0,4,2,9,0,0,0), 60, array(0,0,3,2,2,0,0,0), 0);
  $tz_zone['Antarctica/Rothera'] = array(-180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/Syowa'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Antarctica/Vostok'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Aden'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Almaty'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Amman'] = array(120, array(0,0,0,5,2,0,4,0), 60, array(0,0,0,5,9,0,5,0), 0);
  $tz_zone['Asia/Anadyr'] = array(720, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Aqtau'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Aqtobe'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Ashgabat'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Baghdad'] = array(180, array(0,0,3,1,3,1,7,0), 60, array(0,0,3,1,9,1,7,0), 0);
  $tz_zone['Asia/Bahrain'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Baku'] = array(240, array(0,0,5,5,9,0,0,0), 0, array(0,0,4,5,2,0,0,0), 60);
  $tz_zone['Asia/Bangkok'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Beirut'] = array(120, array(0,0,0,5,2,0,0,0), 60, array(0,0,0,5,9,0,0,0), 0);
  $tz_zone['Asia/Bishkek'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Brunei'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Calcutta'] = array(330, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Choibalsan'] = array(540, array(0,0,2,5,2,0,6,0), 60, array(0,0,2,5,8,0,6,0), 0);
  $tz_zone['Asia/Chongqing'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Colombo'] = array(330, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Damascus'] = array(120, array(0,0,0,1,9,1,7,0), 0, array(0,0,0,1,3,1,7,0), 60);
  $tz_zone['Asia/Dhaka'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Dili'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Dubai'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Dushanbe'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Gaza'] = array(120, array(0,0,0,1,3,1,7,0), 60, array(0,0,0,3,9,0,5,0), 0);
  $tz_zone['Asia/Harbin'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Hong_Kong'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Hovd'] = array(420, array(0,0,2,5,2,0,6,0), 60, array(0,0,2,5,8,0,6,0), 0);
  $tz_zone['Asia/Irkutsk'] = array(480, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Jakarta'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Jayapura'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Jerusalem'] = array(120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Kabul'] = array(270, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Kamchatka'] = array(720, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Karachi'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Kashgar'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Katmandu'] = array(345, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Krasnoyarsk'] = array(420, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Kuala_Lumpur'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Kuching'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Kuwait'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Macau'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Magadan'] = array(660, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Makassar'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Manila'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Muscat'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Nicosia'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Asia/Novosibirsk'] = array(360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Omsk'] = array(360, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Oral'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Phnom_Penh'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Pontianak'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Pyongyang'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Qatar'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Qyzylorda'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Rangoon'] = array(390, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Riyadh'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Saigon'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Sakhalin'] = array(600, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Samarkand'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Seoul'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Shanghai'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Singapore'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Taipei'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Tashkent'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Tbilisi'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Tehran'] = array(210, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Thimphu'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Tokyo'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Ulaanbaatar'] = array(480, array(0,0,2,5,2,0,6,0), 60, array(0,0,2,5,8,0,6,0), 0);
  $tz_zone['Asia/Urumqi'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Vientiane'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Asia/Vladivostok'] = array(600, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Yakutsk'] = array(540, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Yekaterinburg'] = array(300, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Asia/Yerevan'] = array(240, array(0,0,2,5,2,0,0,0), 60, array(0,0,2,5,9,0,0,0), 0);
  $tz_zone['Atlantic/Azores'] = array(-60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Atlantic/Bermuda'] = array(-240, array(0,0,2,2,2,0,0,0), 60, array(0,0,2,1,10,0,0,0), 0);
  $tz_zone['Atlantic/Canary'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Atlantic/Cape_Verde'] = array(-60, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Atlantic/Faroe'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Atlantic/Madeira'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Atlantic/Reykjavik'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Atlantic/South_Georgia'] = array(-120, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Atlantic/St_Helena'] = array(0, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Atlantic/Stanley'] = array(-240, array(0,0,2,1,8,0,0,0), 60, array(0,0,2,3,3,0,0,0), 0);
  $tz_zone['Australia/Adelaide'] = array(570, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,5,9,0,0,0), 60);
  $tz_zone['Australia/Brisbane'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Australia/Broken_Hill'] = array(570, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,5,9,0,0,0), 60);
  $tz_zone['Australia/Currie'] = array(600, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,1,9,0,0,0), 60);
  $tz_zone['Australia/Darwin'] = array(570, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Australia/Eucla'] = array(525, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Australia/Hobart'] = array(600, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,1,9,0,0,0), 60);
  $tz_zone['Australia/Lindeman'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Australia/Lord_Howe'] = array(630, array(0,0,2,5,9,0,0,0), 30, array(0,0,2,5,2,0,0,0), 0);
  $tz_zone['Australia/Melbourne'] = array(600, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,5,9,0,0,0), 60);
  $tz_zone['Australia/Perth'] = array(480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Australia/Sydney'] = array(600, array(0,0,2,5,2,0,0,0), 0, array(0,0,2,5,9,0,0,0), 60);
  $tz_zone['Europe/Amsterdam'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Andorra'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Athens'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Belgrade'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Berlin'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Brussels'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Bucharest'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Budapest'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Chisinau'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Copenhagen'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Dublin'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Gibraltar'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Helsinki'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Istanbul'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Kaliningrad'] = array(120, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Europe/Kiev'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Lisbon'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/London'] = array(0, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Luxembourg'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Madrid'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Malta'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Minsk'] = array(120, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Europe/Monaco'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Moscow'] = array(180, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Europe/Oslo'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Paris'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Prague'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Riga'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Rome'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Samara'] = array(240, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Europe/Simferopol'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Sofia'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Stockholm'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Tallinn'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Tirane'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Uzhgorod'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Vaduz'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Vienna'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Vilnius'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Volgograd'] = array(180, array(0,0,2,5,9,0,0,0), 0, array(0,0,2,5,2,0,0,0), 60);
  $tz_zone['Europe/Warsaw'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Zaporozhye'] = array(120, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Europe/Zurich'] = array(60, array(0,0,1,5,2,0,0,0), 60, array(0,0,1,5,9,0,0,0), 0);
  $tz_zone['Indian/Antananarivo'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Chagos'] = array(360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Christmas'] = array(420, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Cocos'] = array(390, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Comoro'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Kerguelen'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Mahe'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Maldives'] = array(300, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Mauritius'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Mayotte'] = array(180, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Indian/Reunion'] = array(240, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Apia'] = array(-660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Auckland'] = array(720, array(0,0,2,1,9,0,0,0), 60, array(0,0,2,3,2,0,0,0), 0);
  $tz_zone['Pacific/Chatham'] = array(765, array(0,45,2,3,2,0,0,0), 0, array(0,45,2,1,9,0,0,0), 60);
  $tz_zone['Pacific/Easter'] = array(-360, array(0,0,3,2,2,0,0,0), 0, array(0,0,4,2,9,0,0,0), 60);
  $tz_zone['Pacific/Efate'] = array(660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Enderbury'] = array(780, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Fakaofo'] = array(-600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Fiji'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Funafuti'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Galapagos'] = array(-360, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Gambier'] = array(-540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Guadalcanal'] = array(660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Guam'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Honolulu'] = array(-600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Johnston'] = array(-600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Kiritimati'] = array(840, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Kosrae'] = array(660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Kwajalein'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Majuro'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Marquesas'] = array(-570, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Midway'] = array(-660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Nauru'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Niue'] = array(-660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Norfolk'] = array(690, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Noumea'] = array(660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Pago_Pago'] = array(-660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Palau'] = array(540, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Pitcairn'] = array(-480, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Ponape'] = array(660, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Port_Moresby'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Rarotonga'] = array(-600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Saipan'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Tahiti'] = array(-600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Tarawa'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Tongatapu'] = array(780, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Truk'] = array(600, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Wake'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);
  $tz_zone['Pacific/Wallis'] = array(720, array(0,0,0,0,0,0,0,0), 0, array(0,0,0,0,0,0,0,0), 0);

  function tz_gmtime($time=0) {
    if($time == 0) $time = time();
    $lt = localtime($time);
    $lt[5] += 1900; $lt[4]++;
    $gmtime = gmmktime($lt[2], $lt[1], $lt[0], $lt[4], $lt[3], $lt[5], $lt[8]);
    $offset = ($gmtime - $time) / 60;
    $rtime = $time - $offset * 60;
    return localtime($rtime);
  }

  function tz_is_leap_year($year) {
    if($year % 4) return 0;
    if($year % 100) return 1;
    if($year % 400) return 0;
    return 1;
  }

  function tz_days_in_month($month, $year) {
    $days = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    if($month >= 12) $month %= 12;
    if($month == 1) return (tz_is_leap_year($year) ? 29 : 28);
    return $days[$month];
  }

  function tz_checkdate($date, $dst) {
    // Quick check on months
    if($date[4] > $dst[4]) return 1;
    if($date[4] < $dst[4]) return -1;

    if($dst[6] <= 6) {
      $tmp = $date;
      $tmp[3] = 1;
      if($dst[3] == 5) {
        $tmp[3] = tz_days_in_month($tmp[4], $tmp[5] + 1900);
      }

      $tmp = tz_gmtime(gmmktime($tmp[2], $tmp[1], $tmp[0], $tmp[4], $tmp[3], $tmp[5]));

      if($dst[3] == 5) {
        $i = $tmp[6] - $dst[6];
        if($i < 0) $i += 7;
        $day = $tmp[3] - i;
      } else {
        $i = $dst[6] - $tmp[6];
        if($i < 0) $i += 7;
        $day = $tmp[3] + (7 * ($dst[3] - 1)) + i;
      }
    } else {
      // Use day of month
      $day = $dst[3];
    }

    $day = (($day * 24) + $dst[2]) * 60 + $dst[1] * 60;
    $daytime = (($date[3] * 24 + $date[2]) * 60 + $date[1]) * 60 + $date[0];

    if($daytime < $day) return -1;
    if($daytime > $day) return 1;
    return 0;
  }

  function tz_localtime($time, $zone) {
    global $tz_zone;

    // If zone doesnt exist, return local time
    if(!isset($tz_zone[$zone])) return localtime($time);

    // Adjust for initial offset
    $rtime = $time + ($tz_zone[$zone][0] * 60);
    $tmout = tz_gmtime($rtime);

    // return if zone does not practice DST
    if($tz_zone[$zone][3][4] == 0) return $tmout;

    // Check for daylight time
    $rtime = $time + (($tz_zone[$zone][0] + $tz_zone[$zone][2]) * 60);
    $dtm = tz_gmtime($rtime);
    $dtm[9] = true;
    $st = tz_checkdate($dtm, $tz_zone[$zone][3]);

    // Check for standard time
    $rtime = $time + (($tz_zone[$zone][0] + $tz_zone[$zone][4]) * 60);
    $stm = tz_gmtime($rtime);
    $stm[9] = false;
    $dt = tz_checkdate($stm, $tz_zone[$zone][1]);

    // Now check if we are in DST or not
    if($tz_zone[$zone][1][4] < $tz_zone[$zone][3][4]) { /* northern hemisphere */
      if($st < 0 && $dt >= 0) $tmout = $dtm;
      else $tmout = $stm;
    } else {
      if($st < 0 || $dt >= 0) $tmout = $stm;
      else $tmout = $dtm;
    }
    return $tmout;
  }
?>
