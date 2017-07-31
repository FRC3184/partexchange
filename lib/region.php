<?php
$regions_map = array(
  "NC" => "North Carolina",
  "CHS" => "FIRST Chesapeake",
  "PCH" => "Peachtree",
  "NED" => "New England",
  "FIM" => "FIRST in Michigan",
  "MAR" => "Mid-Atlantic Robotics",
  "ISR" => "FIRST Israel",
  "IN" => "IndianaFIRST",
  "PNW" => "Pacific Northwest",
  "ONT" => "Ontario",

  "AL" => "Alabama",
  "AK" => "Alaska",
  "AZ" => "Arizona",
  "AR" => "Arkansas",
  "CA" => "California",
  "CO" => "Colorado",
  "FL" => "Florida",
  "HI" => "Hawaii",
  "ID" => "Idaho",
  "IL" => "Illinois",
  "IA" => "Iowa",
  "KS" => "Kansas",
  "KY" => "Kentucky",
  "LA" => "Lousiana",
  "MN" => "Minnesota",
  "MS" => "Mississippi",
  "MO" => "Missouri",
  "MT" => "Montana",
  "NE" => "Nebraska",
  "NV" => "Nevada",
  "NM" => "New Mexico",
  "NY" => "New York",
  "ND" => "North Dakota",
  "OH" => "Ohio",
  "OK" => "Oklahoma",
  "PA" => "Pennsylvania",
  "SC" => "South Carolina",
  "SD" => "South Dakota",
  "TN" => "Tennessee",
  "TX" => "Texas",
  "UT" => "Utah",
  "VA" => "Virginia",
  "WV" => "West Virginia",
  "WI" => "Wisconsin",
  "WY" => "Wyoming",

  "AB" => "Alberta",
  "BC" => "British Columbia",
  "MB" => "Manitoba",
  "NB" => "New Brunswick",
  "NL" => "Newfoundland and Labrador",
  "NT" => "Northwest Territories",
  "NS" => "Nova Scotia",
  "NU" => "Nunavut",
  "PE" => "Prince Edward Island",
  "QC" => "Quebec",
  "SK" => "Saskatchewan",
  "YT" => "Yukon",

  "AUS" => "Australia",
  "BR" => "Brazil",
  "CN" => "China",
  "XXX" => "Other International"
);
$region_types = array(
  "district" => array("NC", "CHS", "PCH", "NED", "FIM", "MAR", "ISR", "IN", "PNW", "ONT"),
  "state" => array(
    "AL",  "AK",
    "AZ",  "AR",
    "CA",  "CO",
    "FL",  "HI",
    "ID",  "IL",
    "IA",  "KS",
    "KY",  "LA",
    "MN",  "MS",
    "MO",  "MT",
    "NE",  "NV",
    "NM",  "NY",
    "ND",  "OH",
    "OK",  "PA",
    "SC",  "SD",
    "TN",  "TX",
    "UT",  "VA",
    "WV",  "WI",
    "WY"),
  "province" => array("AB", "BC", "MB", "NB", "NL", "NT", "NS", "NU", "PE", "QC", "SK", "YT"),
  "international" => array("AUS", "BR", "CN", "XXX")
);

function getRegionName($code) {
  global $regions_map;
  if (isValidRegion($code)) {
    return $regions_map[$code];
  }
  return "Unknown Region";
}
function isValidRegion($code) {
  global $regions_map;
  return in_array($code, array_keys($regions_map));
}
function printRegionOptions($regionType, $selected) {
  global $regions_map, $region_types;
  foreach ($region_types[$regionType] as $region) {
    echo '<option value="' . $region . '"' . ((0 === strcmp($selected, $region)) ? ' selected ' : '') . '>' . $regions_map[$region] . '</option>';
  }
}
function printRegionSelect($selected, $canSelectNone) {
  global $regions_map, $region_types;

  echo '<option value="" ' . (isValidRegion($selected) ? "" : "selected ") .
        ($canSelectNone ? "" : "disabled ") .'>Region...</option>';
  echo '<option disabled>── Districts</option>';
  printRegionOptions('district', $selected);
  echo '<option disabled>── US States</option>';
  printRegionOptions('state', $selected);
  echo '<option disabled>── Canadian Provinces</option>';
  printRegionOptions('province', $selected);
  echo '<option disabled>── International</option>';
  printRegionOptions('international', $selected);
}
?>
